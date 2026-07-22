<?php
/**
 * Account Controller (Customer)
 * Restaurant Management System
 */

namespace Controllers;

class AccountController extends BaseController
{
    public function dashboard(): void
    {
        $userId = \auth()->id;
        $db = \db();

        $stats = [
            'orders' => $db->fetch("SELECT COUNT(*) as c FROM orders WHERE user_id = ?", [$userId])->c ?? 0,
            'reservations' => $db->fetch("SELECT COUNT(*) as c FROM reservations WHERE user_id = ?", [$userId])->c ?? 0,
            'favorites' => $db->fetch("SELECT COUNT(*) as c FROM favorites WHERE user_id = ?", [$userId])->c ?? 0,
            'loyalty' => \getLoyaltyPoints($userId),
        ];

        $recentOrders = $db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
            [$userId]
        );

        $user = \auth();

        $this->renderWithLayout('account/dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'user' => $user,
            'metaTitle' => 'My Account - DzieRes',
        ]);
    }

    public function profile(): void
    {
        $this->renderWithLayout('account/profile', [
            'user' => \auth(),
            'metaTitle' => 'Profile - DzieRes',
        ]);
    }

    public function updateProfile(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $userId = \auth()->id;
        $data = [
            'firstname' => \sanitize($_POST['firstname'] ?? ''),
            'lastname' => \sanitize($_POST['lastname'] ?? ''),
            'phone' => \sanitize($_POST['phone'] ?? ''),
            'address' => \sanitize($_POST['address'] ?? ''),
            'city' => \sanitize($_POST['city'] ?? ''),
            'state' => \sanitize($_POST['state'] ?? ''),
            'zip' => \sanitize($_POST['zip'] ?? ''),
            'country' => \sanitize($_POST['country'] ?? 'Ghana'),
        ];

        $errors = $this->validate($data, [
            'firstname' => 'required|min:2|max:50',
            'lastname' => 'required|min:2|max:50',
            'phone' => 'phone',
        ]);

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            \sessionFlash('old', $data);
            $this->back();
        }

        \db()->update('users', $data, 'id = :id', ['id' => $userId]);

        // Optional password change
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 8) {
                \sessionFlash('error', 'Password must be at least 8 characters');
                $this->back();
            }
            if ($_POST['password'] !== ($_POST['password_confirm'] ?? '')) {
                \sessionFlash('error', 'Passwords do not match');
                $this->back();
            }
            \db()->update('users', [
                'password' => \password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12])
            ], 'id = :id', ['id' => $userId]);
        }

        \logActivity('profile_update', 'account', 'User updated profile', $userId);
        \sessionFlash('success', 'Profile updated successfully');
        $this->redirect(\baseUrl('account/profile'));
    }

    public function orders(): void
    {
        $orders = \db()->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            [\auth()->id]
        );
        $this->renderWithLayout('account/orders', [
            'orders' => $orders,
            'metaTitle' => 'My Orders - DzieRes',
        ]);
    }

    public function orderDetail(int $id): void
    {
        $order = \db()->fetch(
            "SELECT * FROM orders WHERE id = ? AND user_id = ?",
            [$id, \auth()->id]
        );
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        $items = \db()->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$id]);

        $this->renderWithLayout('account/order-detail', [
            'order' => $order,
            'items' => $items,
            'metaTitle' => 'Order #' . $order->order_number . ' - DzieRes',
        ]);
    }

    public function reservations(): void
    {
        $reservations = \db()->fetchAll(
            "SELECT r.*, t.table_number FROM reservations r
             LEFT JOIN tables t ON r.table_id = t.id
             WHERE r.user_id = ? ORDER BY r.reservation_date DESC",
            [\auth()->id]
        );
        $this->renderWithLayout('account/reservations', [
            'reservations' => $reservations,
            'metaTitle' => 'My Reservations - DzieRes',
        ]);
    }

    public function favorites(): void
    {
        $favorites = \db()->fetchAll(
            "SELECT f.*, c.name as category_name FROM favorites fav
             JOIN foods f ON fav.food_id = f.id
             JOIN categories c ON f.category_id = c.id
             WHERE fav.user_id = ?
             ORDER BY fav.created_at DESC",
            [\auth()->id]
        );
        $this->renderWithLayout('account/favorites', [
            'favorites' => $favorites,
            'metaTitle' => 'My Favorites - DzieRes',
        ]);
    }

    public function toggleFavorite(): void
    {
        if (!\auth()) {
            $this->error('Please login first', 401);
            return;
        }
        $foodId = (int)($_POST['food_id'] ?? 0);
        $userId = \auth()->id;

        $existing = \db()->fetch(
            "SELECT id FROM favorites WHERE user_id = ? AND food_id = ?",
            [$userId, $foodId]
        );

        if ($existing) {
            \db()->delete('favorites', 'id = ?', [$existing->id]);
            $this->success(['favorited' => false], 'Removed from favorites');
        } else {
            \db()->insert('favorites', ['user_id' => $userId, 'food_id' => $foodId]);
            $this->success(['favorited' => true], 'Added to favorites');
        }
    }

    public function reviews(): void
    {
        $reviews = \db()->fetchAll(
            "SELECT fr.*, f.name as food_name FROM food_reviews fr
             JOIN foods f ON fr.food_id = f.id
             WHERE fr.user_id = ?
             ORDER BY fr.created_at DESC",
            [\auth()->id]
        );
        $this->renderWithLayout('account/reviews', [
            'reviews' => $reviews,
            'metaTitle' => 'My Reviews - DzieRes',
        ]);
    }

    public function submitReview(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $foodId = (int)($_POST['food_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        $name = \sanitize($_POST['name'] ?? '');
        $phone = \sanitize($_POST['phone'] ?? '');
        $title = \sanitize($_POST['title'] ?? '');
        $comment = \sanitize($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $this->error('Please provide a rating between 1 and 5');
            return;
        }

        if (empty($name)) {
            $this->error('Please provide your name');
            return;
        }

        \db()->insert('food_reviews', [
            'food_id' => $foodId,
            'user_id' => \auth() ? \auth()->id : null,
            'guest_name' => $name,
            'guest_phone' => $phone,
            'rating' => $rating,
            'title' => $title,
            'comment' => $comment,
            'status' => 'pending',
        ]);

        $this->success([], 'Review submitted for moderation');
    }

    public function wishlist(): void
    {
        $wishlist = \db()->fetchAll(
            "SELECT w.*, f.name, f.slug, f.final_price, f.image, f.availability
             FROM wishlists w
             JOIN foods f ON w.food_id = f.id
             WHERE w.user_id = ? ORDER BY w.created_at DESC",
            [\auth()->id]
        );
        $this->renderWithLayout('account/wishlist', [
            'wishlist' => $wishlist,
            'metaTitle' => 'My Wishlist - DzieRes',
        ]);
    }

    public function loyalty(): void
    {
        $userId = \auth()->id;
        $points = \getLoyaltyPoints($userId);
        $history = \db()->fetchAll(
            "SELECT * FROM loyalty_points WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
            [$userId]
        );
        $rewards = \db()->fetchAll(
            "SELECT * FROM rewards WHERE status = 'active' ORDER BY points_required ASC"
        );
        $this->renderWithLayout('account/loyalty', [
            'points' => $points,
            'history' => $history,
            'rewards' => $rewards,
            'metaTitle' => 'Loyalty Points - DzieRes',
        ]);
    }

    public function addresses(): void
    {
        $this->renderWithLayout('account/addresses', [
            'user' => \auth(),
            'metaTitle' => 'Addresses - DzieRes',
        ]);
    }

    public function saveAddress(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $address = \sanitize($_POST['address'] ?? '');
        \db()->update('users', ['address' => $address], 'id = :id', ['id' => \auth()->id]);
        \sessionFlash('success', 'Address saved');
        $this->redirect(\baseUrl('account/addresses'));
    }
}
