<?php
/**
 * Menu Controller
 * Restaurant Management System
 */

namespace Controllers;

class MenuController extends BaseController
{
    public function index(): void
    {
        $db = \db();
        
        $categorySlug = $_GET['category'] ?? '';
        $search = \sanitize($_GET['search'] ?? '');
        $sort = $_GET['sort'] ?? 'name_asc';
        $spiceLevel = $_GET['spice'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';
        
        // Build query
        $where = "WHERE f.status = 'active' AND f.availability = 'available'";
        $params = [];
        
        if ($categorySlug) {
            $where .= " AND c.slug = ?";
            $params[] = $categorySlug;
        }
        
        if ($search) {
            $where .= " AND (f.name LIKE ? OR f.description LIKE ? OR f.tags LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($spiceLevel) {
            $where .= " AND f.spice_level = ?";
            $params[] = $spiceLevel;
        }
        
        if ($minPrice !== '') {
            $where .= " AND f.final_price >= ?";
            $params[] = (float)$minPrice;
        }
        
        if ($maxPrice !== '') {
            $where .= " AND f.final_price <= ?";
            $params[] = (float)$maxPrice;
        }
        
        // Sorting
        $orderBy = match($sort) {
            'price_asc' => 'f.final_price ASC',
            'price_desc' => 'f.final_price DESC',
            'rating' => 'f.id DESC', // Would need avg rating join
            'newest' => 'f.created_at DESC',
            'popular' => 'f.id DESC',
            default => 'f.name ASC'
        };
        
        $categories = $db->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM foods WHERE category_id = c.id AND status = 'active' AND availability = 'available') as food_count 
             FROM categories c WHERE c.status = 'active' ORDER BY c.sort_order ASC"
        );
        
        // Get foods with pagination
        $countQuery = "SELECT COUNT(*) as count FROM foods f JOIN categories c ON f.category_id = c.id {$where}";
        $query = "SELECT f.*, c.name as category_name, c.slug as category_slug 
                  FROM foods f 
                  JOIN categories c ON f.category_id = c.id 
                  {$where} 
                  ORDER BY {$orderBy}";
        
        $perPage = \config('pagination.per_page', 12);
        $paginator = \paginate($query, $countQuery, $params, $perPage);
        
        $this->renderWithLayout('menu/index', [
            'categories' => $categories,
            'foods' => $paginator['items'],
            'paginator' => $paginator,
            'currentCategory' => $categorySlug,
            'search' => $search,
            'sort' => $sort,
            'spiceLevel' => $spiceLevel,
            'metaTitle' => 'Our Menu - DzieRes Restaurant',
            'metaDescription' => 'Explore our diverse menu featuring breakfast, lunch, dinner, desserts, drinks, and more.',
        ]);
    }

    public function category(string $slug): void
    {
        $_GET['category'] = $slug;
        $this->index();
    }

    public function show(string $slug): void
    {
        $db = \db();
        
        $food = $db->fetch(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.slug = ? AND f.status = 'active'",
            [$slug]
        );
        
        if (!$food) {
            \showError(404, 'Food item not found');
            return;
        }
        
        // Parse JSON fields
        $food->tags = json_decode($food->tags ?? '[]', true);
        $food->ingredients_list = json_decode($food->ingredients ?? '[]', true);
        
        // Get nutrition info
        $nutrition = $db->fetch("SELECT * FROM food_nutrition WHERE food_id = ?", [$food->id]);
        
        // Get related foods
        $relatedFoods = $db->fetchAll(
            "SELECT f.*, c.name as category_name 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.category_id = ? AND f.id != ? AND f.status = 'active' AND f.availability = 'available'
             ORDER BY RANDOM() 
             LIMIT 4",
            [$food->category_id, $food->id]
        );
        
        // Get reviews
        $reviews = $db->fetchAll(
            "SELECT fr.*, CONCAT(u.firstname, ' ', u.lastname) as user_name 
             FROM food_reviews fr 
             LEFT JOIN users u ON fr.user_id = u.id 
             WHERE fr.food_id = ? AND fr.status = 'approved' 
             ORDER BY fr.created_at DESC 
             LIMIT 10",
            [$food->id]
        );
        
        $this->renderWithLayout('menu/show', [
            'food' => $food,
            'nutrition' => $nutrition,
            'relatedFoods' => $relatedFoods,
            'reviews' => $reviews,
            'metaTitle' => $food->name . ' - DzieRes Menu',
            'metaDescription' => \truncate($food->description ?? '', 160),
        ]);
    }

    public function search(): void
    {
        $query = \sanitize($_GET['q'] ?? '');
        
        if (empty($query)) {
            $this->json([]);
            return;
        }
        
        $results = \db()->fetchAll(
            "SELECT f.id, f.name, f.slug, f.price, f.final_price, f.image, f.preparation_time,
                    c.name as category_name
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             WHERE f.status = 'active' AND f.availability = 'available'
             AND (f.name LIKE ? OR f.description LIKE ? OR f.tags LIKE ?)
             LIMIT 10",
            ["%{$query}%", "%{$query}%", "%{$query}%"]
        );
        
        $this->success($results);
    }

    public function filter(): void
    {
        $category = \sanitize($_GET['category'] ?? '');
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';
        $spiceLevel = $_GET['spice'] ?? '';
        $sort = $_GET['sort'] ?? 'name_asc';
        
        $where = "WHERE f.status = 'active' AND f.availability = 'available'";
        $params = [];
        
        if ($category) {
            $where .= " AND c.slug = ?";
            $params[] = $category;
        }
        
        if ($minPrice !== '') {
            $where .= " AND f.final_price >= ?";
            $params[] = (float)$minPrice;
        }
        
        if ($maxPrice !== '') {
            $where .= " AND f.final_price <= ?";
            $params[] = (float)$maxPrice;
        }
        
        if ($spiceLevel) {
            $where .= " AND f.spice_level = ?";
            $params[] = $spiceLevel;
        }
        
        $orderBy = match($sort) {
            'price_asc' => 'f.final_price ASC',
            'price_desc' => 'f.final_price DESC',
            'rating' => 'f.id DESC',
            'newest' => 'f.created_at DESC',
            default => 'f.name ASC'
        };
        
        $foods = \db()->fetchAll(
            "SELECT f.*, c.name as category_name, c.slug as category_slug 
             FROM foods f 
             JOIN categories c ON f.category_id = c.id 
             {$where} 
             ORDER BY {$orderBy} 
             LIMIT 50",
            $params
        );
        
        $this->success($foods);
    }
}