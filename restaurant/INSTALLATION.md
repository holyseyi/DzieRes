# DzieRes Restaurant Management System
## Installation Guide

### System Requirements
- PHP 8.0 or higher
- SQLite3 extension enabled
- Apache with mod_rewrite (or any web server)
- XAMPP / WAMP / LAMP stack

### Quick Installation

#### 1. Copy Files
Copy the entire `restaurant` folder to your web server directory:
- **XAMPP**: `C:\xampp\htdocs\restaurant`
- **LAMP**: `/var/www/html/restaurant`
- **MAMP**: `/Applications/MAMP/htdocs/restaurant`

#### 2. Set Permissions
```bash
chmod -R 755 restaurant/
chmod -R 777 restaurant/database/
chmod -R 777 restaurant/uploads/
chmod -R 777 restaurant/logs/
```

#### 3. Initialize Database
The database is created automatically on first visit. To seed with sample data:

```bash
cd restaurant
php database/seeder.php
```

#### 4. Access the Application
Open your browser and navigate to:
```
http://localhost/restaurant
```

### Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@dzieres.com | admin123 |
| **Staff (Chef)** | chef@dzieres.com | staff123 |
| **Staff (Waiter)** | waiter@dzieres.com | staff123 |
| **Staff (Cashier)** | cashier@dzieres.com | staff123 |
| **Staff (Manager)** | manager@dzieres.com | staff123 |
| **Customer** | sarah@email.com | customer123 |

### Admin Panel
Access the admin panel at:
```
http://localhost/restaurant/admin
```

### Features Overview

#### Customer Website
- **Home**: Hero section, featured meals, today's specials, chef recommendations, testimonials, gallery, newsletter
- **Menu**: Dynamic menu with categories, search, filter, sort, food details
- **Cart**: Add/remove items, apply coupons, quantity management
- **Checkout**: Guest checkout, registered checkout, delivery/pickup/dine-in
- **Reservations**: Date picker, table selection, guest management
- **Account**: Profile, orders, favorites, loyalty points, reviews
- **Blog**: Posts with categories, tags, SEO-friendly URLs
- **Events**: Upcoming events, booking
- **Contact**: Contact form, newsletter subscription
- **Pages**: About, Our Story, Our Chef, FAQs, Privacy Policy, Terms

#### Admin Panel
- **Dashboard**: Statistics, charts, recent orders, upcoming reservations
- **Orders**: View, manage status, print receipts
- **Kitchen Display**: Real-time order tracking, timers, priority indicators
- **Reservations**: Manage bookings, assign tables
- **Menu**: CRUD operations for food items
- **Categories**: Manage menu categories
- **Customers**: View customer information
- **Employees**: Manage staff, attendance
- **Inventory**: Ingredients, stock levels, low stock alerts
- **Tables**: Restaurant floor layout management
- **Coupons**: Discount code management
- **Promotions**: Marketing promotions
- **Reviews**: Moderate customer reviews
- **Gallery**: Image management
- **Blog**: Full CRUD for blog posts
- **Settings**: Restaurant configuration
- **Reports**: Sales and revenue analytics

### Security Features
- CSRF Protection
- XSS Prevention
- SQL Injection Prevention (PDO Prepared Statements)
- Password Hashing (bcrypt)
- Session Security
- Rate Limiting
- Role-based Access Control
- Input Validation

### File Structure
```
restaurant/
├── index.php              # Entry point
├── .htaccess              # URL rewriting & security
├── Router.php             # MVC Router
├── config/                # Configuration files
│   ├── app.php
│   ├── database.php
│   └── Database.php
├── controllers/           # Application controllers
├── models/                # Data models
├── views/                 # View templates
│   ├── layouts/           # Layout templates
│   ├── admin/             # Admin panel views
│   ├── auth/              # Authentication views
│   └── errors/            # Error pages
├── database/              # Database files
│   ├── schema.sql         # Database schema
│   └── seeder.php         # Sample data seeder
├── helpers/               # Helper functions
├── middleware/             # Middleware classes
├── assets/                # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/               # Uploaded files
└── logs/                  # Application logs
```

### Troubleshooting

**Blank page on access:**
- Check PHP error reporting in index.php
- Ensure SQLite extension is enabled in php.ini
- Check file permissions

**Database errors:**
- Delete `database/restaurant.db` and refresh
- Run seeder again: `php database/seeder.php`

**404 errors:**
- Ensure .htaccess is enabled (AllowOverride All in Apache config)
- Check mod_rewrite is enabled

**Theme not loading:**
- Clear browser cache
- Check asset paths in config/app.php

### Support
For issues and support, contact: info@dzieres.com