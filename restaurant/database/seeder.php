<?php
/**
 * Database Seeder
 * Restaurant Management System
 * 
 * Run: php database/seeder.php
 */

require_once __DIR__ . '/../config/Database.php';

use Config\Database;

class Seeder
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        echo "🌱 Seeding database...\n\n";
    }

    public function seed(): void
    {
        $this->seedRoles();
        $this->seedPermissions();
        $this->seedUsers();
        $this->seedSettings();
        $this->seedCategories();
        $this->seedFoods();
        $this->seedTables();
        $this->seedTestimonials();
        $this->seedGallery();
        $this->seedBlogCategories();
        $this->seedBlogPosts();
        $this->seedCoupons();
        $this->seedEvents();
        $this->seedJobListings();
        $this->seedSuppliers();
        $this->seedIngredients();
        $this->seedRewards();
        
        echo "\n✅ Database seeded successfully!\n";
    }

    private function seedRoles(): void
    {
        echo "  → Seeding roles...\n";
        // Already inserted in schema.sql
    }

    private function seedPermissions(): void
    {
        echo "  → Seeding permissions...\n";
        $modules = ['dashboard', 'orders', 'reservations', 'menu', 'categories', 'customers', 'employees', 'inventory', 'suppliers', 'tables', 'coupons', 'promotions', 'reviews', 'gallery', 'blog', 'settings', 'users', 'roles', 'reports', 'backups'];
        
        foreach ($modules as $module) {
            $actions = ['view', 'create', 'edit', 'delete'];
            foreach ($actions as $action) {
                $name = ucfirst($action) . ' ' . ucfirst($module);
                $slug = $module . '.' . $action;
                try {
                    $this->db->insert('permissions', [
                        'name' => $name,
                        'slug' => $slug,
                        'module' => $module,
                        'description' => "Allows user to {$action} {$module}"
                    ]);
                } catch (\Exception $e) {
                    // Skip duplicates
                }
            }
        }
    }

    private function seedUsers(): void
    {
        echo "  → Seeding users...\n";
        
        // Admin
        $adminId = $this->db->insert('users', [
            'role_id' => 1,
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@dzieres.com',
            'phone' => '+233 50 000 0001',
            'password' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status' => 'active',
        ]);
        
        // Staff
        $staffUsers = [
            ['firstname' => 'Kwame', 'lastname' => 'Asante', 'email' => 'chef@dzieres.com', 'phone' => '+233 50 000 0002'],
            ['firstname' => 'Ama', 'lastname' => 'Serwaa', 'email' => 'waiter@dzieres.com', 'phone' => '+233 50 000 0003'],
            ['firstname' => 'Kofi', 'lastname' => 'Mensah', 'email' => 'cashier@dzieres.com', 'phone' => '+233 50 000 0004'],
            ['firstname' => 'Esi', 'lastname' => 'Boateng', 'email' => 'manager@dzieres.com', 'phone' => '+233 50 000 0005'],
        ];
        
        foreach ($staffUsers as $staff) {
            $userId = $this->db->insert('users', [
                'role_id' => 2,
                'firstname' => $staff['firstname'],
                'lastname' => $staff['lastname'],
                'email' => $staff['email'],
                'phone' => $staff['phone'],
                'password' => password_hash('staff123', PASSWORD_BCRYPT, ['cost' => 12]),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 'active',
            ]);
            
            // Create employee record
            $positions = ['chef', 'waiter', 'cashier', 'manager'];
            $this->db->insert('employees', [
                'user_id' => $userId,
                'employee_code' => 'EMP-' . str_pad($userId, 3, '0', STR_PAD_LEFT),
                'position' => $positions[array_search($staff, $staffUsers)],
                'department' => 'Operations',
                'hire_date' => date('Y-m-d', strtotime('-1 year')),
                'salary' => 2500 + (array_search($staff, $staffUsers) * 500),
                'employment_type' => 'full_time',
                'status' => 'active',
            ]);
        }
        
        // Customers
        $customers = [
            ['firstname' => 'Sarah', 'lastname' => 'Johnson', 'email' => 'sarah@email.com'],
            ['firstname' => 'James', 'lastname' => 'Mensah', 'email' => 'james@email.com'],
            ['firstname' => 'Emily', 'lastname' => 'Osei', 'email' => 'emily@email.com'],
            ['firstname' => 'Michael', 'lastname' => 'Addo', 'email' => 'michael@email.com'],
            ['firstname' => 'Grace', 'lastname' => 'Amoako', 'email' => 'grace@email.com'],
        ];
        
        foreach ($customers as $customer) {
            $userId = $this->db->insert('users', [
                'role_id' => 3,
                'firstname' => $customer['firstname'],
                'lastname' => $customer['lastname'],
                'email' => $customer['email'],
                'phone' => '+233 50 ' . str_pad(mt_rand(100, 999), 3, '0', STR_PAD_LEFT) . ' ' . str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'password' => password_hash('customer123', PASSWORD_BCRYPT, ['cost' => 12]),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 'active',
            ]);
            
            // Add loyalty points
            $this->db->insert('loyalty_points', [
                'user_id' => $userId,
                'points' => mt_rand(50, 500),
                'type' => 'earned',
                'description' => 'Welcome bonus and initial orders',
            ]);
        }
        
        echo "    Admin: admin@dzieres.com / admin123\n";
        echo "    Staff: chef@dzieres.com / staff123\n";
        echo "    Customer: sarah@email.com / customer123\n";
    }

    private function seedSettings(): void
    {
        echo "  → Seeding settings...\n";
        
        $settings = [
            ['key' => 'restaurant_name', 'value' => 'DzieRes', 'group_name' => 'general'],
            ['key' => 'restaurant_tagline', 'value' => 'Where Every Meal Tells a Story', 'group_name' => 'general'],
            ['key' => 'restaurant_email', 'value' => 'info@dzieres.com', 'group_name' => 'general'],
            ['key' => 'restaurant_phone', 'value' => '+233 50 000 0000', 'group_name' => 'general'],
            ['key' => 'restaurant_address', 'value' => '123 Independence Avenue, Accra, Ghana', 'group_name' => 'general'],
            ['key' => 'opening_hours', 'value' => 'Mon-Sun: 7:00 AM - 11:00 PM', 'group_name' => 'general'],
            ['key' => 'tax_rate', 'value' => '12.5', 'group_name' => 'billing'],
            ['key' => 'currency_symbol', 'value' => '₵', 'group_name' => 'billing'],
            ['key' => 'currency_code', 'value' => 'GHS', 'group_name' => 'billing'],
            ['key' => 'delivery_fee', 'value' => '15.00', 'group_name' => 'delivery'],
            ['key' => 'free_delivery_above', 'value' => '100.00', 'group_name' => 'delivery'],
            ['key' => 'service_charge', 'value' => '5.00', 'group_name' => 'billing'],
            ['key' => 'loyalty_points_per_ghs', 'value' => '10', 'group_name' => 'loyalty'],
            ['key' => 'welcome_points', 'value' => '100', 'group_name' => 'loyalty'],
            ['key' => 'primary_color', 'value' => '#1a1a2e', 'group_name' => 'appearance'],
            ['key' => 'accent_color', 'value' => '#c9a84c', 'group_name' => 'appearance'],
        ];
        
        foreach ($settings as $setting) {
            try {
                $this->db->insert('settings', $setting);
            } catch (\Exception $e) {
                // Skip duplicates
            }
        }
    }

    private function seedCategories(): void
    {
        echo "  → Seeding categories...\n";
        
        $categories = [
            ['name' => 'Breakfast', 'slug' => 'breakfast', 'icon' => 'coffee', 'sort_order' => 1, 'is_featured' => 1],
            ['name' => 'Lunch', 'slug' => 'lunch', 'icon' => 'hamburger', 'sort_order' => 2, 'is_featured' => 1],
            ['name' => 'Dinner', 'slug' => 'dinner', 'icon' => 'wine-glass-alt', 'sort_order' => 3, 'is_featured' => 1],
            ['name' => 'Desserts', 'slug' => 'desserts', 'icon' => 'ice-cream', 'sort_order' => 4, 'is_featured' => 1],
            ['name' => 'Drinks', 'slug' => 'drinks', 'icon' => 'glass-cheers', 'sort_order' => 5, 'is_featured' => 1],
            ['name' => 'Cocktails', 'slug' => 'cocktails', 'icon' => 'cocktail', 'sort_order' => 6],
            ['name' => 'Wine', 'slug' => 'wine', 'icon' => 'wine-bottle', 'sort_order' => 7],
            ['name' => 'Pizza', 'slug' => 'pizza', 'icon' => 'pizza-slice', 'sort_order' => 8, 'is_featured' => 1],
            ['name' => 'Burgers', 'slug' => 'burgers', 'icon' => 'hamburger', 'sort_order' => 9],
            ['name' => 'Seafood', 'slug' => 'seafood', 'icon' => 'fish', 'sort_order' => 10, 'is_featured' => 1],
            ['name' => 'Local Meals', 'slug' => 'local-meals', 'icon' => 'drumstick-bite', 'sort_order' => 11, 'is_featured' => 1],
            ['name' => 'Appetizers', 'slug' => 'appetizers', 'icon' => 'utensils', 'sort_order' => 12],
        ];
        
        foreach ($categories as $cat) {
            $this->db->insert('categories', $cat);
        }
    }

    private function seedFoods(): void
    {
        echo "  → Seeding foods...\n";
        
        $foods = [
            // Breakfast (category 1)
            ['category_id' => 1, 'name' => 'Continental Breakfast', 'slug' => 'continental-breakfast', 'description' => 'A delightful continental breakfast with fresh pastries, fruits, and premium coffee.', 'ingredients' => '["Croissant","Baguette","Butter","Jam","Fresh Fruits","Coffee","Orange Juice"]', 'price' => 45.00, 'discount_percent' => 0, 'final_price' => 45.00, 'calories' => 450, 'preparation_time' => 15, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["breakfast","continental","pastries"]', 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=400&h=300&fit=crop'],
            ['category_id' => 1, 'name' => 'Ghanaian Breakfast', 'slug' => 'ghanaian-breakfast', 'description' => 'Traditional Ghanaian breakfast with kenkey, fried fish, and pepper sauce.', 'ingredients' => '["Kenkey","Fried Fish","Pepper Sauce","Shito","Onions","Tomatoes"]', 'price' => 35.00, 'discount_percent' => 0, 'final_price' => 35.00, 'calories' => 550, 'preparation_time' => 20, 'spice_level' => 'hot', 'is_featured' => 1, 'is_todays_special' => 1, 'is_chef_recommendation' => 1, 'tags' => '["breakfast","ghanaian","traditional"]', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=300&fit=crop'],
            ['category_id' => 1, 'name' => 'Pancake Stack', 'slug' => 'pancake-stack', 'description' => 'Fluffy pancakes served with maple syrup, fresh berries, and whipped cream.', 'ingredients' => '["Pancake Mix","Maple Syrup","Blueberries","Strawberries","Whipped Cream","Butter"]', 'price' => 38.00, 'discount_percent' => 10, 'final_price' => 34.20, 'calories' => 520, 'preparation_time' => 15, 'spice_level' => 'mild', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["breakfast","pancakes","sweet"]', 'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop'],
            ['category_id' => 1, 'name' => 'Eggs Benedict', 'slug' => 'eggs-benedict', 'description' => 'Poached eggs on English muffins with ham and hollandaise sauce.', 'ingredients' => '["Eggs","English Muffins","Ham","Hollandaise Sauce","Chives","Butter"]', 'price' => 42.00, 'discount_percent' => 0, 'final_price' => 42.00, 'calories' => 480, 'preparation_time' => 20, 'spice_level' => 'mild', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["breakfast","eggs","classic"]', 'image' => 'https://images.unsplash.com/photo-1608039829572-9b18d7b4b82c?w=400&h=300&fit=crop'],
            
            // Lunch (category 2)
            ['category_id' => 2, 'name' => 'Grilled Chicken Salad', 'slug' => 'grilled-chicken-salad', 'description' => 'Fresh garden salad topped with grilled chicken breast and vinaigrette.', 'ingredients' => '["Chicken Breast","Mixed Greens","Cherry Tomatoes","Cucumber","Croutons","Vinaigrette"]', 'price' => 48.00, 'discount_percent' => 0, 'final_price' => 48.00, 'calories' => 380, 'preparation_time' => 15, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["lunch","salad","healthy"]', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop'],
            ['category_id' => 2, 'name' => 'Jollof Rice Special', 'slug' => 'jollof-rice-special', 'description' => 'Premium Ghanaian jollof rice with grilled chicken, plantain, and coleslaw.', 'ingredients' => '["Rice","Tomatoes","Chicken","Plantain","Cabbage","Carrots","Spices"]', 'price' => 55.00, 'discount_percent' => 0, 'final_price' => 55.00, 'calories' => 650, 'preparation_time' => 25, 'spice_level' => 'medium', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["lunch","jollof","ghanaian","popular"]', 'image' => 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&h=300&fit=crop'],
            ['category_id' => 2, 'name' => 'Beef Stir Fry', 'slug' => 'beef-stir-fry', 'description' => 'Tender beef strips wok-fried with vegetables in a savory sauce.', 'ingredients' => '["Beef","Bell Peppers","Broccoli","Carrots","Soy Sauce","Garlic","Ginger"]', 'price' => 52.00, 'discount_percent' => 5, 'final_price' => 49.40, 'calories' => 420, 'preparation_time' => 20, 'spice_level' => 'medium', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["lunch","beef","stir-fry"]', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=300&fit=crop'],
            
            // Dinner (category 3)
            ['category_id' => 3, 'name' => 'Grilled Ribeye Steak', 'slug' => 'grilled-ribeye-steak', 'description' => 'Premium ribeye steak grilled to perfection with herb butter and seasonal vegetables.', 'ingredients' => '["Ribeye Steak","Herb Butter","Asparagus","Cherry Tomatoes","Garlic","Rosemary"]', 'price' => 120.00, 'discount_percent' => 0, 'final_price' => 120.00, 'calories' => 750, 'preparation_time' => 30, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["dinner","steak","premium"]', 'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?w=400&h=300&fit=crop'],
            ['category_id' => 3, 'name' => 'Lobster Thermidor', 'slug' => 'lobster-thermidor', 'description' => 'Classic French lobster dish with creamy mustard sauce and gratinéed cheese.', 'ingredients' => '["Lobster","Mushrooms","Mustard","Cheese","Cream","White Wine","Butter"]', 'price' => 180.00, 'discount_percent' => 0, 'final_price' => 180.00, 'calories' => 620, 'preparation_time' => 40, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 1, 'is_chef_recommendation' => 1, 'tags' => '["dinner","seafood","lobster","premium"]', 'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?w=400&h=300&fit=crop'],
            ['category_id' => 3, 'name' => 'Grilled Salmon', 'slug' => 'grilled-salmon', 'description' => 'Atlantic salmon fillet with lemon butter sauce and dill potatoes.', 'ingredients' => '["Salmon","Lemon","Butter","Dill","Potatoes","Green Beans","Capers"]', 'price' => 85.00, 'discount_percent' => 0, 'final_price' => 85.00, 'calories' => 480, 'preparation_time' => 25, 'spice_level' => 'mild', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["dinner","salmon","seafood","healthy"]', 'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400&h=300&fit=crop'],
            
            // Desserts (category 4)
            ['category_id' => 4, 'name' => 'Chocolate Lava Cake', 'slug' => 'chocolate-lava-cake', 'description' => 'Warm chocolate cake with a molten center, served with vanilla ice cream.', 'ingredients' => '["Dark Chocolate","Butter","Eggs","Flour","Sugar","Vanilla Ice Cream"]', 'price' => 32.00, 'discount_percent' => 0, 'final_price' => 32.00, 'calories' => 450, 'preparation_time' => 20, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["dessert","chocolate","cake"]', 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=400&h=300&fit=crop'],
            ['category_id' => 4, 'name' => 'Tiramisu', 'slug' => 'tiramisu', 'description' => 'Classic Italian tiramisu with mascarpone, espresso, and cocoa.', 'ingredients' => '["Mascarpone","Espresso","Ladyfingers","Cocoa","Eggs","Sugar"]', 'price' => 28.00, 'discount_percent' => 0, 'final_price' => 28.00, 'calories' => 380, 'preparation_time' => 10, 'spice_level' => 'mild', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["dessert","italian","coffee"]', 'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=300&fit=crop'],
            
            // Drinks (category 5)
            ['category_id' => 5, 'name' => 'Fresh Fruit Smoothie', 'slug' => 'fresh-fruit-smoothie', 'description' => 'Blended fresh tropical fruits with yogurt and honey.', 'ingredients' => '["Mango","Pineapple","Banana","Yogurt","Honey","Ice"]', 'price' => 22.00, 'discount_percent' => 0, 'final_price' => 22.00, 'calories' => 180, 'preparation_time' => 5, 'spice_level' => 'mild', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["drinks","smoothie","healthy"]', 'image' => 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?w=400&h=300&fit=crop'],
            ['category_id' => 5, 'name' => 'Special Ginger Drink', 'slug' => 'special-ginger-drink', 'description' => 'Refreshing homemade ginger drink with a hint of lemon and mint.', 'ingredients' => '["Ginger","Lemon","Mint","Sugar","Water","Ice"]', 'price' => 15.00, 'discount_percent' => 0, 'final_price' => 15.00, 'calories' => 80, 'preparation_time' => 5, 'spice_level' => 'medium', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["drinks","ginger","local"]', 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&h=300&fit=crop'],
            
            // Pizza (category 8)
            ['category_id' => 8, 'name' => 'Margherita Pizza', 'slug' => 'margherita-pizza', 'description' => 'Classic Neapolitan pizza with San Marzano tomatoes, mozzarella, and basil.', 'ingredients' => '["Pizza Dough","San Marzano Tomatoes","Fresh Mozzarella","Basil","Olive Oil","Salt"]', 'price' => 45.00, 'discount_percent' => 0, 'final_price' => 45.00, 'calories' => 680, 'preparation_time' => 20, 'spice_level' => 'mild', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["pizza","italian","classic"]', 'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=300&fit=crop'],
            ['category_id' => 8, 'name' => 'Pepperoni Pizza', 'slug' => 'pepperoni-pizza', 'description' => 'Loaded with pepperoni, mozzarella, and our signature tomato sauce.', 'ingredients' => '["Pizza Dough","Pepperoni","Mozzarella","Tomato Sauce","Oregano","Olive Oil"]', 'price' => 52.00, 'discount_percent' => 0, 'final_price' => 52.00, 'calories' => 720, 'preparation_time' => 20, 'spice_level' => 'medium', 'is_featured' => 0, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["pizza","pepperoni","popular"]', 'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop'],
            
            // Seafood (category 10)
            ['category_id' => 10, 'name' => 'Grilled Prawns', 'slug' => 'grilled-prawns', 'description' => 'Jumbo prawns marinated in garlic and herbs, grilled to perfection.', 'ingredients' => '["Jumbo Prawns","Garlic","Herbs","Lemon","Butter","Chili Flakes"]', 'price' => 75.00, 'discount_percent' => 0, 'final_price' => 75.00, 'calories' => 320, 'preparation_time' => 20, 'spice_level' => 'medium', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["seafood","prawns","grilled"]', 'image' => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?w=400&h=300&fit=crop'],
            ['category_id' => 10, 'name' => 'Fried Tilapia', 'slug' => 'fried-tilapia', 'description' => 'Crispy fried tilapia served with banku, pepper sauce, and vegetables.', 'ingredients' => '["Tilapia","Banku","Pepper Sauce","Onions","Tomatoes","Oil","Spices"]', 'price' => 48.00, 'discount_percent' => 0, 'final_price' => 48.00, 'calories' => 520, 'preparation_time' => 25, 'spice_level' => 'hot', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 0, 'tags' => '["seafood","tilapia","ghanaian","popular"]', 'image' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?w=400&h=300&fit=crop'],
            
            // Local Meals (category 11)
            ['category_id' => 11, 'name' => 'Fufu with Light Soup', 'slug' => 'fufu-light-soup', 'description' => 'Traditional fufu served with rich light soup and your choice of meat.', 'ingredients' => '["Cassava","Plantain","Goat Meat","Tomatoes","Onions","Ginger","Spices"]', 'price' => 42.00, 'discount_percent' => 0, 'final_price' => 42.00, 'calories' => 580, 'preparation_time' => 30, 'spice_level' => 'medium', 'is_featured' => 1, 'is_todays_special' => 0, 'is_chef_recommendation' => 1, 'tags' => '["local","fufu","ghanaian","traditional"]', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=300&fit=crop'],
            ['category_id' => 11, 'name' => 'Waakye', 'slug' => 'waakye', 'description' => 'Ghanaian rice and beans with shito, spaghetti, gari, and boiled egg.', 'ingredients' => '["Rice","Beans","Shito","Spaghetti","Gari","Egg","Fish","Vegetables"]', 'price' => 35.00, 'discount_percent' => 0, 'final_price' => 35.00, 'calories' => 620, 'preparation_time' => 20, 'spice_level' => 'medium', 'is_featured' => 1, 'is_todays_special' => 1, 'is_chef_recommendation' => 0, 'tags' => '["local","waakye","ghanaian","popular"]', 'image' => 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&h=300&fit=crop'],
        ];
        
        foreach ($foods as $food) {
            $this->db->insert('foods', $food);
        }
    }

    private function seedTables(): void
    {
        echo "  → Seeding tables...\n";
        
        $locations = ['indoor', 'outdoor', 'vip', 'bar'];
        $tableNum = 1;
        
        for ($i = 1; $i <= 20; $i++) {
            $capacity = $i <= 4 ? 2 : ($i <= 10 ? 4 : ($i <= 15 ? 6 : 8));
            $location = $i <= 8 ? 'indoor' : ($i <= 14 ? 'outdoor' : ($i <= 18 ? 'vip' : 'bar'));
            
            $this->db->insert('tables', [
                'table_number' => 'T' . str_pad($tableNum++, 2, '0', STR_PAD_LEFT),
                'capacity' => $capacity,
                'min_capacity' => $capacity >= 6 ? 4 : 1,
                'location' => $location,
                'status' => 'available',
                'is_available' => 1,
                'sort_order' => $i,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        echo "  → Seeding testimonials...\n";
        
        $testimonials = [
            ['guest_name' => 'Sarah Johnson', 'guest_title' => 'Food Critic', 'content' => 'An absolutely remarkable dining experience. The flavors, presentation, and service were all world-class. DzieRes has set a new standard for fine dining in Accra.', 'rating' => 5, 'is_featured' => 1],
            ['guest_name' => 'James Mensah', 'guest_title' => 'Regular Guest', 'content' => 'I have been coming here for years and the quality never disappoints. The chef\'s special is always a masterpiece. Highly recommended for any special occasion.', 'rating' => 5, 'is_featured' => 1],
            ['guest_name' => 'Emily Osei', 'guest_title' => 'Event Planner', 'content' => 'We hosted our company dinner at DzieRes and it was perfect. The private dining area, the attentive staff, and the incredible menu made it an unforgettable evening.', 'rating' => 5, 'is_featured' => 1],
            ['guest_name' => 'Michael Addo', 'guest_title' => 'Business Executive', 'content' => 'Perfect venue for business lunches. The ambiance is sophisticated yet welcoming, and the service is impeccably professional.', 'rating' => 4, 'is_featured' => 0],
            ['guest_name' => 'Grace Amoako', 'guest_title' => 'Food Blogger', 'content' => 'The fusion of traditional Ghanaian flavors with international techniques is brilliant. Every dish tells a story of culinary excellence.', 'rating' => 5, 'is_featured' => 1],
            ['guest_name' => 'David Osei', 'guest_title' => 'Tourist', 'content' => 'Discovered this gem during my visit to Accra. The jollof rice is the best I have ever tasted! Will definitely come back.', 'rating' => 5, 'is_featured' => 0],
        ];
        
        foreach ($testimonials as $t) {
            $this->db->insert('testimonials', array_merge($t, ['status' => 'active', 'sort_order' => 0]));
        }
    }

    private function seedGallery(): void
    {
        echo "  → Seeding gallery...\n";
        
        $images = [
            ['title' => 'Elegant Dining Room', 'category' => 'interior', 'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop'],
            ['title' => 'Signature Dish', 'category' => 'food', 'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop'],
            ['title' => 'Bar Area', 'category' => 'interior', 'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600&h=400&fit=crop'],
            ['title' => 'Chef at Work', 'category' => 'kitchen', 'image' => 'https://images.unsplash.com/photo-1550966871-3ed3cdb51f3a?w=600&h=400&fit=crop'],
            ['title' => 'Fresh Ingredients', 'category' => 'food', 'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600&h=400&fit=crop'],
            ['title' => 'Outdoor Seating', 'category' => 'interior', 'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600&h=400&fit=crop'],
            ['title' => 'Dessert Platter', 'category' => 'food', 'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop'],
        ];
        
        foreach ($images as $img) {
            $this->db->insert('gallery', array_merge($img, ['status' => 'active', 'sort_order' => 0]));
        }
    }

    private function seedBlogCategories(): void
    {
        echo "  → Seeding blog categories...\n";
        
        $categories = [
            ['name' => 'Culinary Tips', 'slug' => 'culinary-tips'],
            ['name' => 'Restaurant News', 'slug' => 'restaurant-news'],
            ['name' => 'Food Culture', 'slug' => 'food-culture'],
            ['name' => 'Recipes', 'slug' => 'recipes'],
            ['name' => 'Events', 'slug' => 'events'],
        ];
        
        foreach ($categories as $cat) {
            $this->db->insert('blog_categories', $cat);
        }
    }

    private function seedBlogPosts(): void
    {
        echo "  → Seeding blog posts...\n";
        
        $posts = [
            [
                'category_id' => 1, 'user_id' => 1,
                'title' => 'The Art of Plating: How Presentation Elevates Dining',
                'slug' => 'art-of-plating',
                'excerpt' => 'Discover the techniques our chefs use to create visually stunning dishes that delight all the senses.',
                'content' => '<p>In the world of fine dining, presentation is just as important as taste. At DzieRes, our chefs spend years mastering the art of plating to ensure every dish is a visual masterpiece.</p><h2>The Principles of Great Plating</h2><p>Great plating follows several key principles: balance, color contrast, height, and negative space. Our chefs carefully consider each element to create compositions that are both beautiful and appetizing.</p><h2>Color Theory on the Plate</h2><p>Just like a painter uses a palette, our chefs use ingredients to create visual harmony. Bright vegetables, vibrant sauces, and carefully placed garnishes all contribute to the overall aesthetic.</p>',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&h=500&fit=crop',
                'status' => 'published', 'is_featured' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'meta_title' => 'The Art of Plating - DzieRes Blog',
                'meta_description' => 'Learn about the art of food plating from our expert chefs at DzieRes Restaurant.',
            ],
            [
                'category_id' => 2, 'user_id' => 1,
                'title' => 'Introducing Our New Summer Menu',
                'slug' => 'new-summer-menu',
                'excerpt' => 'We are excited to announce our new summer menu featuring fresh seasonal ingredients and innovative dishes.',
                'content' => '<p>Summer has arrived at DzieRes, and with it comes an exciting new menu that celebrates the best of the season\'s bounty. Our culinary team has been working tirelessly to create dishes that are light, refreshing, and bursting with flavor.</p><h2>Seasonal Highlights</h2><p>Our new menu features fresh seafood, vibrant salads, and tropical fruit desserts that capture the essence of summer. Each dish is designed to be shared and enjoyed in the warm evening air.</p>',
                'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=500&fit=crop',
                'status' => 'published', 'is_featured' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'meta_title' => 'New Summer Menu - DzieRes Restaurant',
                'meta_description' => 'Explore our exciting new summer menu at DzieRes Restaurant.',
            ],
            [
                'category_id' => 3, 'user_id' => 1,
                'title' => 'A Journey Through Ghanaian Cuisine',
                'slug' => 'journey-through-ghanaian-cuisine',
                'excerpt' => 'Explore the rich flavors and traditions of Ghanaian cuisine, from street food to fine dining.',
                'content' => '<p>Ghanaian cuisine is a vibrant tapestry of flavors, colors, and traditions. From the bustling streets of Accra to the elegant dining rooms of DzieRes, Ghanaian food tells the story of our culture and heritage.</p><h2>Traditional Staples</h2><p>Dishes like jollof rice, fufu, and waakye are more than just food - they are expressions of Ghanaian hospitality and community. At DzieRes, we honor these traditions while adding our own creative touch.</p>',
                'image' => 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=800&h=500&fit=crop',
                'status' => 'published', 'is_featured' => 0,
                'published_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'meta_title' => 'Ghanaian Cuisine Journey - DzieRes Blog',
                'meta_description' => 'Discover the rich traditions of Ghanaian cuisine at DzieRes Restaurant.',
            ],
        ];
        
        foreach ($posts as $post) {
            $this->db->insert('blog_posts', $post);
        }
    }

    private function seedCoupons(): void
    {
        echo "  → Seeding coupons...\n";
        
        $coupons = [
            ['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_order_amount' => 50, 'max_discount' => 30, 'usage_limit' => 100, 'description' => '10% off for new customers'],
            ['code' => 'FREEDEL', 'type' => 'free_delivery', 'value' => 0, 'min_order_amount' => 30, 'usage_limit' => 50, 'description' => 'Free delivery on your order'],
            ['code' => 'SAVE20', 'type' => 'fixed', 'value' => 20, 'min_order_amount' => 80, 'usage_limit' => 30, 'description' => '₵20 off orders above ₵80'],
            ['code' => 'HALFOFF', 'type' => 'percentage', 'value' => 50, 'min_order_amount' => 100, 'max_discount' => 50, 'usage_limit' => 20, 'description' => '50% off (max ₵50 discount)'],
            ['code' => 'SPECIAL15', 'type' => 'percentage', 'value' => 15, 'min_order_amount' => 40, 'max_discount' => 25, 'usage_limit' => 50, 'description' => '15% off weekend special'],
        ];
        
        foreach ($coupons as $coupon) {
            $this->db->insert('coupons', array_merge($coupon, [
                'start_date' => date('Y-m-d H:i:s'),
                'end_date' => date('Y-m-d H:i:s', strtotime('+6 months')),
                'status' => 'active',
            ]));
        }
    }

    private function seedEvents(): void
    {
        echo "  → Seeding events...\n";
        
        $events = [
            [
                'title' => 'Wine Tasting Evening',
                'slug' => 'wine-tasting-evening',
                'description' => 'An exclusive evening of wine tasting featuring premium wines from around the world.',
                'content' => '<p>Join us for an unforgettable evening of wine tasting at DzieRes. Our sommelier will guide you through a selection of premium wines paired with exquisite canapés.</p>',
                'event_date' => date('Y-m-d', strtotime('+14 days')),
                'event_time' => '18:00',
                'end_time' => '21:00',
                'location' => 'DzieRes Main Hall',
                'capacity' => 50,
                'price' => 150.00,
                'type' => 'public',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Live Jazz Night',
                'slug' => 'live-jazz-night',
                'description' => 'Enjoy an evening of smooth jazz with a specially curated dinner menu.',
                'content' => '<p>Experience the perfect combination of live jazz music and fine dining. Our resident jazz band will perform throughout the evening while you enjoy our special jazz night menu.</p>',
                'event_date' => date('Y-m-d', strtotime('+7 days')),
                'event_time' => '19:00',
                'end_time' => '23:00',
                'location' => 'DzieRes Lounge',
                'capacity' => 80,
                'price' => 0,
                'type' => 'public',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Cooking Masterclass',
                'slug' => 'cooking-masterclass',
                'description' => 'Learn the secrets of Ghanaian cuisine from our head chef.',
                'content' => '<p>Our head chef invites you to an exclusive cooking masterclass where you will learn to prepare signature DzieRes dishes. Limited spots available.</p>',
                'event_date' => date('Y-m-d', strtotime('+21 days')),
                'event_time' => '10:00',
                'end_time' => '14:00',
                'location' => 'DzieRes Kitchen',
                'capacity' => 15,
                'price' => 200.00,
                'type' => 'public',
                'status' => 'upcoming',
            ],
        ];
        
        foreach ($events as $event) {
            $this->db->insert('events', $event);
        }
    }

    private function seedJobListings(): void
    {
        echo "  → Seeding job listings...\n";
        
        $jobs = [
            [
                'title' => 'Executive Chef',
                'slug' => 'executive-chef',
                'department' => 'Kitchen',
                'location' => 'Accra, Ghana',
                'type' => 'full_time',
                'description' => 'We are looking for an experienced Executive Chef to lead our kitchen team and create exceptional dining experiences.',
                'requirements' => "- Minimum 8 years of culinary experience\n- Proven experience as Executive Chef\n- Strong leadership and management skills\n- Knowledge of Ghanaian and international cuisines\n- Culinary degree preferred",
                'salary_range' => '₵8,000 - ₵12,000/month',
                'status' => 'open',
            ],
            [
                'title' => 'Head Waiter',
                'slug' => 'head-waiter',
                'department' => 'Service',
                'location' => 'Accra, Ghana',
                'type' => 'full_time',
                'description' => 'Join our front-of-house team as Head Waiter and ensure our guests receive world-class service.',
                'requirements' => "- Minimum 3 years of fine dining experience\n- Excellent communication skills\n- Knowledge of wine and food pairing\n- Leadership experience\n- Multilingual is a plus",
                'salary_range' => '₵3,000 - ₵4,500/month',
                'status' => 'open',
            ],
            [
                'title' => 'Pastry Chef',
                'slug' => 'pastry-chef',
                'department' => 'Kitchen',
                'location' => 'Accra, Ghana',
                'type' => 'full_time',
                'description' => 'We are seeking a talented Pastry Chef to create beautiful and delicious desserts for our guests.',
                'requirements' => "- Minimum 5 years of pastry experience\n- Creative and artistic skills\n- Knowledge of international pastry techniques\n- Ability to work in a fast-paced environment",
                'salary_range' => '₵5,000 - ₵7,000/month',
                'status' => 'open',
            ],
        ];
        
        foreach ($jobs as $job) {
            $this->db->insert('job_listings', $job);
        }
    }

    private function seedSuppliers(): void
    {
        echo "  → Seeding suppliers...\n";
        
        $suppliers = [
            ['name' => 'Fresh Farms Ghana', 'contact_person' => 'John Doe', 'email' => 'john@freshfarms.com', 'phone' => '+233 50 100 0001', 'city' => 'Accra', 'payment_terms' => 'Net 30'],
            ['name' => 'Ocean Catch Seafood', 'contact_person' => 'Mary Smith', 'email' => 'mary@oceancatch.com', 'phone' => '+233 50 100 0002', 'city' => 'Tema', 'payment_terms' => 'Net 15'],
            ['name' => 'Premium Meats Ltd', 'contact_person' => 'Kwame Asare', 'email' => 'kwame@premiummeats.com', 'phone' => '+233 50 100 0003', 'city' => 'Accra', 'payment_terms' => 'Net 30'],
            ['name' => 'Global Beverages', 'contact_person' => 'Esi Mensah', 'email' => 'esi@globalbeverages.com', 'phone' => '+233 50 100 0004', 'city' => 'Accra', 'payment_terms' => 'Net 45'],
        ];
        
        foreach ($suppliers as $supplier) {
            $this->db->insert('suppliers', $supplier);
        }
    }

    private function seedIngredients(): void
    {
        echo "  → Seeding ingredients...\n";
        
        $ingredients = [
            ['name' => 'Chicken Breast', 'slug' => 'chicken-breast', 'category' => 'Poultry', 'unit' => 'kg', 'unit_price' => 25.00, 'stock_quantity' => 50, 'minimum_stock' => 10],
            ['name' => 'Beef Ribeye', 'slug' => 'beef-ribeye', 'category' => 'Meat', 'unit' => 'kg', 'unit_price' => 80.00, 'stock_quantity' => 30, 'minimum_stock' => 5],
            ['name' => 'Fresh Salmon', 'slug' => 'fresh-salmon', 'category' => 'Seafood', 'unit' => 'kg', 'unit_price' => 65.00, 'stock_quantity' => 20, 'minimum_stock' => 5],
            ['name' => 'Jumbo Prawns', 'slug' => 'jumbo-prawns', 'category' => 'Seafood', 'unit' => 'kg', 'unit_price' => 90.00, 'stock_quantity' => 15, 'minimum_stock' => 3],
            ['name' => 'Fresh Tilapia', 'slug' => 'fresh-tilapia', 'category' => 'Seafood', 'unit' => 'kg', 'unit_price' => 30.00, 'stock_quantity' => 40, 'minimum_stock' => 10],
            ['name' => 'Tomatoes', 'slug' => 'tomatoes', 'category' => 'Vegetables', 'unit' => 'kg', 'unit_price' => 8.00, 'stock_quantity' => 100, 'minimum_stock' => 20],
            ['name' => 'Onions', 'slug' => 'onions', 'category' => 'Vegetables', 'unit' => 'kg', 'unit_price' => 5.00, 'stock_quantity' => 80, 'minimum_stock' => 20],
            ['name' => 'Garlic', 'slug' => 'garlic', 'category' => 'Vegetables', 'unit' => 'kg', 'unit_price' => 12.00, 'stock_quantity' => 15, 'minimum_stock' => 5],
            ['name' => 'Rice', 'slug' => 'rice', 'category' => 'Grains', 'unit' => 'kg', 'unit_price' => 10.00, 'stock_quantity' => 200, 'minimum_stock' => 50],
            ['name' => 'Pizza Dough', 'slug' => 'pizza-dough', 'category' => 'Bakery', 'unit' => 'pcs', 'unit_price' => 5.00, 'stock_quantity' => 100, 'minimum_stock' => 20],
            ['name' => 'Mozzarella Cheese', 'slug' => 'mozzarella-cheese', 'category' => 'Dairy', 'unit' => 'kg', 'unit_price' => 40.00, 'stock_quantity' => 25, 'minimum_stock' => 5],
            ['name' => 'Fresh Cream', 'slug' => 'fresh-cream', 'category' => 'Dairy', 'unit' => 'l', 'unit_price' => 18.00, 'stock_quantity' => 20, 'minimum_stock' => 5],
            ['name' => 'Butter', 'slug' => 'butter', 'category' => 'Dairy', 'unit' => 'kg', 'unit_price' => 22.00, 'stock_quantity' => 15, 'minimum_stock' => 5],
            ['name' => 'Olive Oil', 'slug' => 'olive-oil', 'category' => 'Oils', 'unit' => 'l', 'unit_price' => 35.00, 'stock_quantity' => 20, 'minimum_stock' => 5],
            ['name' => 'Cassava', 'slug' => 'cassava', 'category' => 'Vegetables', 'unit' => 'kg', 'unit_price' => 6.00, 'stock_quantity' => 60, 'minimum_stock' => 15],
            ['name' => 'Plantain', 'slug' => 'plantain', 'category' => 'Vegetables', 'unit' => 'pcs', 'unit_price' => 3.00, 'stock_quantity' => 200, 'minimum_stock' => 50],
        ];
        
        foreach ($ingredients as $ingredient) {
            $this->db->insert('ingredients', $ingredient);
        }
    }

    private function seedRewards(): void
    {
        echo "  → Seeding rewards...\n";
        
        $rewards = [
            ['name' => 'Free Dessert', 'description' => 'Enjoy a complimentary dessert of your choice', 'points_required' => 200, 'type' => 'free_item', 'value' => 32.00],
            ['name' => '₵20 Discount', 'description' => 'Get ₵20 off your next order', 'points_required' => 300, 'type' => 'discount', 'value' => 20.00],
            ['name' => 'Free Delivery', 'description' => 'Free delivery on your next order', 'points_required' => 150, 'type' => 'free_delivery', 'value' => 15.00],
            ['name' => 'Main Course Upgrade', 'description' => 'Upgrade your main course to premium selection', 'points_required' => 500, 'type' => 'upgrade', 'value' => 50.00],
            ['name' => '₵50 Dining Voucher', 'description' => '₵50 voucher for dine-in experience', 'points_required' => 750, 'type' => 'discount', 'value' => 50.00],
        ];
        
        foreach ($rewards as $reward) {
            $this->db->insert('rewards', $reward);
        }
    }
}

// Run seeder
$seeder = new Seeder();
$seeder->seed();