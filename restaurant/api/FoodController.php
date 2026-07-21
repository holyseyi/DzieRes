<?php
/**
 * API: Food Controller
 * REST-style endpoints for foods / menu items.
 */

namespace Api;

use Controllers\BaseController;

class FoodController extends BaseController
{
    public function popular(): void
    {
        $foods = \db()->fetchAll(
            "SELECT f.id, f.name, f.slug, f.price, f.final_price, f.image, f.preparation_time, c.name as category_name
             FROM foods f
             JOIN categories c ON f.category_id = c.id
             WHERE f.status = 'active' AND f.availability = 'available'
             ORDER BY f.is_featured DESC, RANDOM()
             LIMIT 10"
        );
        $this->success($foods);
    }

    public function featured(): void
    {
        $foods = \db()->fetchAll(
            "SELECT f.id, f.name, f.slug, f.price, f.final_price, f.image, f.preparation_time, c.name as category_name
             FROM foods f
             JOIN categories c ON f.category_id = c.id
             WHERE f.is_featured = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC
             LIMIT 12"
        );
        $this->success($foods);
    }

    public function todaysSpecial(): void
    {
        $foods = \db()->fetchAll(
            "SELECT f.id, f.name, f.slug, f.price, f.final_price, f.image, f.preparation_time, c.name as category_name
             FROM foods f
             JOIN categories c ON f.category_id = c.id
             WHERE f.is_todays_special = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC
             LIMIT 8"
        );
        $this->success($foods);
    }

    public function chefRecommendations(): void
    {
        $foods = \db()->fetchAll(
            "SELECT f.id, f.name, f.slug, f.price, f.final_price, f.image, f.preparation_time, c.name as category_name
             FROM foods f
             JOIN categories c ON f.category_id = c.id
             WHERE f.is_chef_recommendation = 1 AND f.status = 'active' AND f.availability = 'available'
             ORDER BY f.sort_order ASC
             LIMIT 8"
        );
        $this->success($foods);
    }
}
