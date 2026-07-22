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
            $this->renderWithLayout('checkout/index', [
                'cartItems' => [],
                'subtotal' => 0,
                'taxAmount' => 0,
                'taxRate' => 0,
                'deliveryFee' => \config('delivery.fee', 15),
                'serviceCharge' => 0,
                'couponDiscount' => 0,
                'total' => 0,
                'restaurantLocations' => [
                    ['name' => 'DzieRes - Osu', 'lat' => 5.5560, 'lng' => -0.1969, 'address' => 'Oxford Street, Osu, Accra'],
                    ['name' => 'DzieRes - East Legon', 'lat' => 5.6527, 'lng' => -0.1786, 'address' => 'East Legon, Accra'],
                    ['name' => 'DzieRes - Labone', 'lat' => 5.5833, 'lng' => -0.2000, 'address' => 'Labone, Accra'],
                ],
                'metaTitle' => 'Checkout - DzieRes Restaurant',
            ]);
            return;
        }
        
        $subtotal = array_sum(array_column($cartItems, 'total_price'));
        $deliveryFee = \config('delivery.fee', 15);
        
        $couponDiscount = 0;
        $coupon = \sessionGet('coupon');
        if ($coupon) {
            $couponDiscount = $coupon['discount'] ?? 0;
        }
        
        $total = $subtotal - $couponDiscount;
        
        $this->renderWithLayout('checkout/index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'taxAmount' => 0,
            'taxRate' => 0,
            'deliveryFee' => $deliveryFee,
            'serviceCharge' => 0,
            'couponDiscount' => $couponDiscount,
            'total' => max(0, $total),
            'restaurantLocations' => [
                ['name' => 'DzieRes - Osu', 'lat' => 5.5560, 'lng' => -0.1969, 'address' => 'Oxford Street, Osu, Accra'],
                ['name' => 'DzieRes - East Legon', 'lat' => 5.6527, 'lng' => -0.1786, 'address' => 'East Legon, Accra'],
                ['name' => 'DzieRes - Labone', 'lat' => 5.5833, 'lng' => -0.2000, 'address' => 'Labone, Accra'],
            ],
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
        $pickupLocation = \sanitize($_POST['pickup_location'] ?? '');
        $deliveryLat = $_POST['delivery_lat'] ?? null;
        $deliveryLng = $_POST['delivery_lng'] ?? null;
        $specialNotes = \sanitize($_POST['special_notes'] ?? '');
        $tableId = (int)($_POST['table_id'] ?? 0);
        
        if ($orderType === 'delivery') {
            if (empty($deliveryAddress) || empty($deliveryCity) || empty($deliveryPhone)) {
                \sessionFlash('error', 'Please provide delivery address, city, and phone number.');
                $this->redirect(\baseUrl('checkout'));
                return;
            }
        } elseif ($orderType === 'pickup') {
            if (empty($pickupLocation)) {
                \sessionFlash('error', 'Please select a pickup location or use your location.');
                $this->redirect(\baseUrl('checkout'));
                return;
            }
        } elseif ($orderType === 'dine_in') {
            if (empty($tableId)) {
                \sessionFlash('error', 'Please select a table for dine-in.');
                $this->redirect(\baseUrl('checkout'));
                return;
            }
        }
        
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
        $deliveryFee = ($orderType === 'delivery') ? \config('delivery.fee', 15) : 0;
        
        $couponDiscount = 0;
        $couponCode = null;
        $coupon = \sessionGet('coupon');
        if ($coupon) {
            $couponDiscount = $coupon['discount'] ?? 0;
            $couponCode = $coupon['code'] ?? null;
        }
        
        $total = $subtotal + $deliveryFee - $couponDiscount;
        
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
                'tax_amount' => 0,
                'delivery_fee' => $deliveryFee,
                'service_charge' => 0,
                'total_amount' => $total,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'delivery_address' => $orderType === 'delivery' ? $deliveryAddress : null,
                'delivery_city' => $orderType === 'delivery' ? $deliveryCity : null,
                'delivery_phone' => $orderType === 'delivery' ? $deliveryPhone : null,
                'pickup_location' => $orderType === 'pickup' ? $pickupLocation : null,
                'special_notes' => $specialNotes,
                'is_guest' => $userId ? 0 : 1,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ]);
            
            if ($orderType === 'dine_in' && $tableId) {
                $table = \db()->fetch("SELECT table_number, capacity FROM tables WHERE id = ?", [$tableId]);
                if ($table) {
                    \db()->update('tables', ['status' => 'occupied'], 'id = :id', ['id' => $tableId]);
                    \createNotification(
                        null,
                        'table_booking',
                        'Table ' . $table->table_number . ' Booked',
                        'Dine-in order #' . $orderNumber . ' booked table ' . $table->table_number . ' (' . $table->capacity . ' seats).',
                        \baseUrl('admin/orders/' . $orderId)
                    );
                }
            }
            
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
            
            if ($userId && !empty($cartItems)) {
                $foodIds = array_column($cartItems, 'food_id');
                $placeholders = implode(',', array_fill(0, count($foodIds), '?'));
                $favItems = \db()->fetchAll(
                    "SELECT food_id FROM favorites WHERE user_id = ? AND food_id IN ($placeholders)",
                    array_merge([$userId], $foodIds)
                );
                $favFoodIds = array_column($favItems, 'food_id');
                foreach ($cartItems as $item) {
                    if (in_array((int)$item->food_id, $favFoodIds)) {
                        \createNotification(
                            null,
                            'favorite_purchase',
                            'Favorited Item Purchased',
                            "Order #{$orderNumber} includes a favorited item: {$item->name}",
                            \baseUrl("admin/orders/{$orderId}")
                        );
                    }
                }
            }
            
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

    public function trackApi(string $number): void
    {
        $order = \db()->fetch(
            "SELECT id, status, order_number, updated_at 
             FROM orders WHERE order_number = ?",
            [$number]
        );
        
        if (!$order) {
            $this->success(['found' => false]);
            return;
        }
        
        $tracking = \db()->fetchAll(
            "SELECT * FROM order_tracking WHERE order_id = ? ORDER BY created_at ASC",
            [$order->id]
        );
        
        $this->success([
            'found' => true,
            'status' => $order->status,
            'order_number' => $order->order_number,
            'updated_at' => $order->updated_at,
            'tracking' => $tracking,
        ]);
    }

    public function receipt(string $number): void
    {
        $order = \db()->fetch(
            "SELECT * FROM orders WHERE order_number = ?",
            [$number]
        );
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        $items = \db()->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$order->id]);
        $this->renderWithLayout('order/receipt', [
            'order' => $order,
            'items' => $items,
            'metaTitle' => 'Receipt #' . $order->order_number,
        ]);
    }
}