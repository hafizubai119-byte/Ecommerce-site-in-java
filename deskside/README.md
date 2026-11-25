# Deskside: Responsive E-Commerce Website (PHP + MySQL)

## Run it (XAMPP / WAMP / Laragon)
1. Copy the `deskside` folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Start Apache and MySQL.
3. In phpMyAdmin, open the Import tab and import `schema.sql` (it creates the `deskside` database and sample products).
4. If your MySQL password is not empty, edit `config.php`.
5. Open http://localhost/deskside/

Or from a terminal inside the folder: `php -S localhost:8000`

## What it demonstrates
- Frontend: CSS Grid/Flexbox responsive layout, custom properties, 3 breakpoints, focus styles, reduced-motion support.
- JavaScript: fetch() calls to a JSON endpoint, live cart badge, toast feedback.
- Backend: PDO prepared statements, session cart, server-side pricing and stock checks.
- Database: 4 related tables, a join for listings, and a transaction at checkout (order + items + stock decrement).

## Structure
- index.php: product listing with search, category filter, sort
- product.php: product detail
- cart.php and cart_action.php: cart page and JSON API
- checkout.php: validation, order saved to MySQL
- schema.sql: tables and seed data
