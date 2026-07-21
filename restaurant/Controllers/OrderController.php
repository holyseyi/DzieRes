<?php
/**
 * Order Controller
 * Restaurant Management System
 */

namespace Controllers;

class OrderController extends BaseController
{
    public function checkout(): void
    {
        $cartItems = [];
        
        if (\auth()) {
            $cartItems = \db()->fetchAll(
                "SELECT c.*, f.name, f.slug, f.image, f.final_price, f.stock_quantity, f.availability
                 FROM carts c 
                 JOIN foods f ON c.food_id = f.id 
                 WHERE c.user_id = ? 
                 ORDER BY c.created_at DESC",
                [\auth()->id]
            );
        } else {
            $sessionCart = $_SESSION['cart'] ?? [];
            foreach ($sessionCart as $foodId => $item) {
                $food = \db()->fetch(
                    "SELECT id, name, slug, image, final_price, stock_quantity, availability 
                     FROM foods WHERE id = ? AND status = 'active'",
                    [$foodId]
                );
                if ($food) {
                    $cartItems[] = (object)[
                        'food_id' => $foodId,
                        'quantity' => $item['quantity'],
                        'unit_price' => $food->final_price,
                        'total_price' => $food->final_price * $item['quantity'],
                        'name' => $food->name,
                        'slug' => $food->slug,
                        'image' => $food->image,
                        'final_price' => $food->final_price,
                    ];
                }
            }
        }
        
        if (empty($cartItems)) {
            \sessionFlash('info', 'Your cart is empty');
            $this->redirect(\baseUrl('cart'));
        }
        
        $subtotal = array_sum(array_column($cartItems, 'total_price'));
        $taxRate = \config('tax.rate', 12.5);
        $taxAmount = $subtotal * ($taxRate / 100);
        $deliveryFee = \config('delivery.fee', 15);
        $serviceCharge = \config('service_charge', 5);
        
        $couponDiscount = 0;
        $coupon = \sessionGet('coupon');
        if ($coupon) {
            $couponDiscount = $coupon['discount'] ?? 0;
        }
        
        $total = $subtotal + $taxAmount + $deliveryFee + $serviceCharge - $couponDiscount;
        
        $this->renderWithLayout('checkout/index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'taxRate' => $taxRate,
            'taxAmount' => $taxAmount,
            'deliveryFee' => $deliveryFee,
            'serviceCharge' => $serviceCharge,
            'couponDiscount' => $couponDiscount,
            'total' => max(0, $total),
            'metaTitle' => 'Checkout - DzieRes Restaurant',
        ]);
    }

    public function placeOrder(): void
    {
        if (!\verifyCsrf()) {
            \sessionFlash('error', 'Invalid security token. Please try again.');
            $this->redirect(\baseUrl('checkout'));
            return;
        }
        
        $orderType = $_POST['order_type'] ?? 'delivery';
        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        $guestName = \sanitize($_POST['guest_name'] ?? '');
        $guestEmail = \sanitize($_POST['guest_email'] ?? '');
        $guestPhone = \sanitize($_POST['guest_phone'] ?? '');
        $deliveryAddress = \sanitize($_POST['delivery_address'] ?? '');
        $deliveryCity = \sanitize($_POST['delivery_city'] ?? '');
        $deliveryPhone = \sanitize($_POST['delivery_phone'] ?? '');
        $specialNotes = \sanitize($_POST['special_notes'] ?? '');
        $tableId = (int)($_POST['table_id'] ?? 0);
        
        // Get cart items
        $cartItems = [];
        if (\auth()) {
            $cartItems = \db()->fetchAll(
                "SELECT c.*, f.name, f.final_price FROM carts c JOIN foods f ON c.food_id = f.id WHERE c.user_id = ?",
                [\auth()->id]
            );
        } else {
            $sessionCart = $_SESSION['cart'] ?? [];
            foreach ($sessionCart as $foodId => $item) {
                $food = \db()->fetch(
                    "SELECT name, final_price FROM foods WHERE id = ? AND status = 'active'",
                    [$foodId]
                );
                if ($food) {
                    $cartItems[] = (object)[
                        'food_id' => $foodId,
                        'quantity' => $item['quantity'],
                        'unit_price' => $food->final_price,
                        'total_price' => $food->final_price * $item['quantity'],
                        'name' => $food->name,
                    ];
                }
            }
        }
        
        if (empty($cartItems)) {
            $this->error('Your cart is empty');
            return;
        }
        
        $subtotal = array_sum(array_column($cartItems, 'total_price'));
        $taxRate = \config('tax.rate', 12.5);
        $taxAmount = $subtotal * ($taxRate / 100);
        $deliveryFee = ($orderType === 'delivery') ? \config('delivery.fee', 15) : 0;
        $serviceCharge = \config('service_charge', 5);
        
        $couponDiscount = 0;
        $couponCode = null;
        $coupon = \sessionGet('coupon');
        if ($coupon) {
            $couponDiscount = $coupon['discount'] ?? 0;
            $couponCode = $coupon['code'] ?? null;
        }
        
        $total = $subtotal + $taxAmount + $deliveryFee + $serviceCharge - $couponDiscount;
        
        \db()->beginTransaction();
        
        try {
            $orderNumber = \generateOrderNumber();
            $user = \auth();
            $userId = $user ? (int)$user->id : null;
            
            $orderId = \db()->insert('orders', [
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'guest_name' => $userId ? null : $guestName,
                'guest_email' => $userId ? null : $guestEmail,
                'guest_phone' => $userId ? null : $guestPhone,
                'order_type' => $orderType,
                'table_id' => $orderType === 'dine_in' ? $tableId : null,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => $couponDiscount,
                'coupon_code' => $couponCode,
                'coupon_discount' => $couponDiscount,
                'tax_amount' => $taxAmount,
                'delivery_fee' => $deliveryFee,
                'service_charge' => $serviceCharge,
                'total_amount' => $total,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'delivery_address' => $deliveryAddress,
                'delivery_city' => $deliveryCity,
                'delivery_phone' => $deliveryPhone,
                'special_notes' => $specialNotes,
                'is_guest' => $userId ? 0 : 1,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ]);
            
            foreach ($cartItems as $item) {
                \db()->insert('order_items', [
                    'order_id' => $orderId,
                    'food_id' => $item->food_id,
                    'food_name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            }
            
            \db()->insert('order_tracking', [
                'order_id' => $orderId,
                'status' => 'pending',
                'description' => 'Order placed successfully',
                'created_by' => $userId,
            ]);
            
            if ($userId) {
                \db()->delete('carts', 'user_id = ?', [$userId]);
            } else {
                unset($_SESSION['cart']);
            }
            
            \sessionRemove('coupon');
            
            if ($userId) {
                $points = (int)($total * \config('loyalty.points_per_ghs', 10));
                \addLoyaltyPoints($userId, $points, 'earned', "Order #{$orderNumber}", 'order', $orderId);
            }
            
            if ($couponCode) {
                \db()->query(
                    "UPDATE coupons SET used_count = used_count + 1 WHERE code = ?",
                    [$couponCode]
                );
            }
            
            \db()->commit();
            
            \logActivity('order_placed', 'orders', "Order #{$orderNumber} placed", $userId);
            
            \createNotification(
                $userId,
                'order',
                'New Order',
                "New {$orderType} order #{$orderNumber} placed" . ($userId ? " by user #{$userId}" : " by guest"),
                \baseUrl("admin/orders/{$orderId}")
            );
            
            \sessionFlash('success', 'Order placed successfully');
            $this->redirect(\baseUrl("order/confirmation/{$orderId}"));
            
        } catch (\PDOException $e) {
            \db()->rollback();
            \sessionFlash('error', 'Failed to place order: ' . $e->getMessage() . ' (Code: ' . $e->getCode() . ')');
            $this->redirect(\baseUrl('checkout'));
        } catch (\Exception $e) {
            \db()->rollback();
            \sessionFlash('error', 'Failed to place order: ' . $e->getMessage());
            $this->redirect(\baseUrl('checkout'));
        }
    }

    public function confirmation(int $id): void
    {
        $order = \db()->fetch(
            "SELECT o.*, CONCAT(u.firstname, ' ', u.lastname) as user_name 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             WHERE o.id = ?",
            [$id]
        );
        
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        
        $orderItems = \db()->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?",
            [$id]
        );
        
        $tracking = \db()->fetchAll(
            "SELECT * FROM order_tracking WHERE order_id = ? ORDER BY created_at ASC",
            [$id]
        );
        
        $this->renderWithLayout('order/confirmation', [
            'order' => $order,
            'orderItems' => $orderItems,
            'tracking' => $tracking,
            'metaTitle' => 'Order Confirmation - DzieRes Restaurant',
        ]);
    }

    public function track(string $number): void
    {
        $order = \db()->fetch(
            "SELECT o.*, CONCAT(u.firstname, ' ', u.lastname) as user_name 
             FROM orders o 
             LEFT JOIN users u ON o.user_id = u.id 
             WHERE o.order_number = ?",
            [$number]
        );
        
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        
        $orderItems = \db()->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?",
            [$order->id]
        );
        
        $tracking = \db()->fetchAll(
            "SELECT * FROM order_tracking WHERE order_id = ? ORDER BY created_at ASC",
            [$order->id]
        );
        
        $this->renderWithLayout('order/track', [
            'order' => $order,
            'orderItems' => $orderItems,
            'tracking' => $tracking,
            'metaTitle' => 'Track Order - DzieRes Restaurant',
        ]);
    }
}