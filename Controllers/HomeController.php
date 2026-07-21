<?php
/**
 * Home Controller
 * Restaurant Management System
 */

namespace Controllers;

class HomeController extends BaseController
{
    public function index(): void
    {
        $db = \db();
        
        // Get featured foods
        $featuredMeals = $db->fetchAll(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.is_featured = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC 
             LIMIT 8"
        );
        
        // Get today's special
        $todaysSpecial = $db->fetchAll(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.is_todays_special = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC 
             LIMIT 4"
        );
        
        // Get chef recommendations
        $chefRecommendations = $db->fetchAll(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.is_chef_recommendation = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC 
             LIMIT 4"
        );
        
        // Get categories
        $categories = $db->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM foods WHERE category_id = c.id AND status = 'active') as food_count 
             FROM categories c 
             WHERE c.status = 'active' 
             ORDER BY c.sort_order ASC"
        );
        
        // Get all foods for the home page
        $allFoods = $db->fetchAll(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC, f.name ASC"
        );
        
        // Get testimonials
        $testimonials = $db->fetchAll(
            "SELECT * FROM testimonials WHERE status = 'active' AND is_featured = 1 ORDER BY sort_order ASC LIMIT 6"
        );
        
        // Get gallery images
        $gallery = $db->fetchAll(
            "SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC LIMIT 8"
        );
        
        // Get blog posts
        $blogPosts = $db->fetchAll(
            "SELECT bp.*, bc.name as category_name, bc.slug as category_slug,
                    CONCAT(u.firstname, ' ', u.lastname) as author_name
             FROM blog_posts bp 
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id 
             LEFT JOIN users u ON bp.user_id = u.id 
             WHERE bp.status = 'published' 
             ORDER BY bp.published_at DESC 
             LIMIT 3"
        );
        
        // Get statistics
        $stats = [
            'years' => $db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'delivered'"),
            'meals_served' => $db->fetch("SELECT COALESCE(SUM(quantity), 0) as count FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.status = 'delivered'"),
            'happy_customers' => $db->fetch("SELECT COUNT(DISTINCT user_id) as count FROM orders WHERE user_id IS NOT NULL AND status = 'delivered'"),
            'branches' => (object)['count' => 3],
        ];
        
        $this->renderWithLayout('home', [
            'featuredMeals' => $featuredMeals,
            'todaysSpecial' => $todaysSpecial,
            'chefRecommendations' => $chefRecommendations,
            'categories' => $categories,
            'allFoods' => $allFoods,
            'testimonials' => $testimonials,
            'gallery' => $gallery,
            'blogPosts' => $blogPosts,
            'stats' => $stats,
            'metaTitle' => 'DzieRes - Where Every Meal Tells a Story',
            'metaDescription' => 'Experience fine dining at DzieRes Restaurant. Enjoy exquisite cuisine, elegant ambiance, and exceptional service in the heart of Accra.',
        ]);
    }
}