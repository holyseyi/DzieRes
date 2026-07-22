<?php
/**
 * Cart Controller
 * Restaurant Management System
 */

namespace Controllers;

class CartController extends BaseController
{
    public function index(): void
    {
        \redirect(\baseUrl('checkout'));
    }

    public function add(): void
    {
        $foodId = (int)($_POST['food_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        
        $food = \db()->fetch(
            "SELECT id, name, final_price, stock_quantity, availability, max_order_qty 
             FROM foods WHERE id = ? AND status = 'active'",
            [$foodId]
        );
        
        if (!$food || $food->availability !== 'available') {
            $this->error('Food item not available');
            return;
        }
        
        if ($quantity > $food->max_order_qty) {
            $this->error("Maximum order quantity is {$food->max_order_qty}");
            return;
        }
        
        if (\auth()) {
            $existing = \db()->fetch(
                "SELECT id, quantity FROM carts WHERE user_id = ? AND food_id = ?",
                [\auth()->id, $foodId]
            );
            
            if ($existing) {
                $newQty = $existing->quantity + $quantity;
                \db()->update('carts', [
                    'quantity' => $newQty,
                    'total_price' => $food->final_price * $newQty
                ], 'id = :id', ['id' => $existing->id]);
            } else {
                \db()->insert('carts', [
                    'user_id' => \auth()->id,
                    'food_id' => $foodId,
                    'quantity' => $quantity,
                    'unit_price' => $food->final_price,
                    'total_price' => $food->final_price * $quantity
                ]);
            }
        } else {
            $cart = $_SESSION['cart'] ?? [];
            if (isset($cart[$foodId])) {
                $cart[$foodId]['quantity'] += $quantity;
            } else {
                $cart[$foodId] = [
                    'quantity' => $quantity,
                    'price' => $food->final_price,
                ];
            }
            $_SESSION['cart'] = $cart;
        }
        
        $this->success(['count' => \getCartCount()], 'Item added to cart');
    }

    public function update(): void
    {
        $foodId = (int)($_POST['food_id'] ?? 0);
        $quantity = max(0, (int)($_POST['quantity'] ?? 0));
        
        if ($quantity === 0) {
            $this->remove();
            return;
        }
        
        $food = \db()->fetch(
            "SELECT id, final_price, max_order_qty FROM foods WHERE id = ? AND status = 'active'",
            [$foodId]
        );
        
        if (!$food) {
            $this->error('Food item not found');
            return;
        }
        
        if ($quantity > $food->max_order_qty) {
            $this->error("Maximum order quantity is {$food->max_order_qty}");
            return;
        }
        
        if (\auth()) {
            \db()->update('carts', [
                'quantity' => $quantity,
                'total_price' => $food->final_price * $quantity
            ], 'user_id = :user_id AND food_id = :food_id', [
                'user_id' => \auth()->id,
                'food_id' => $foodId
            ]);
        } else {
            $cart = $_SESSION['cart'] ?? [];
            if (isset($cart[$foodId])) {
                $cart[$foodId]['quantity'] = $quantity;
            }
            $_SESSION['cart'] = $cart;
        }
        
        $this->success(['count' => \getCartCount(), 'total' => \getCartTotal()], 'Cart updated');
    }

    public function remove(): void
    {
        $foodId = (int)($_POST['food_id'] ?? 0);
        
        if (\auth()) {
            \db()->delete('carts', 'user_id = ? AND food_id = ?', [\auth()->id, $foodId]);
        } else {
            $cart = $_SESSION['cart'] ?? [];
            unset($cart[$foodId]);
            $_SESSION['cart'] = $cart;
        }
        
        $this->success(['count' => \getCartCount()], 'Item removed from cart');
    }

    public function applyCoupon(): void
    {
        $code = \sanitize($_POST['code'] ?? '');
        
        if (empty($code)) {
            $this->error('Please enter a coupon code');
            return;
        }
        
        $coupon = \db()->fetch(
            "SELECT * FROM coupons 
             WHERE code = ? AND status = 'active' 
             AND (start_date IS NULL OR start_date <= datetime('now'))
             AND (end_date IS NULL OR end_date >= datetime('now'))
             AND used_count < usage_limit",
            [$code]
        );
        
        if (!$coupon) {
            $this->error('Invalid or expired coupon code');
            return;
        }
        
        $subtotal = \getCartTotal();
        
        if ($subtotal < $coupon->min_order_amount) {
            $this->error("Minimum order amount of " . \formatPrice($coupon->min_order_amount) . " required");
            return;
        }
        
        $discount = match($coupon->type) {
            'percentage' => min($subtotal * ($coupon->value / 100), $coupon->max_discount ?? $subtotal),
            'fixed' => min($coupon->value, $subtotal),
            'free_delivery' => \config('delivery.fee', 15),
            default => 0
        };
        
        \sessionSet('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);
        
        $this->success([
            'discount' => $discount,
            'formatted_discount' => \formatPrice($discount),
            'code' => $coupon->code
        ], 'Coupon applied successfully');
    }

    public function getCount(): void
    {
        $this->success(['count' => \getCartCount()]);
    }
}