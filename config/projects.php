<?php

/*
|--------------------------------------------------------------------------
| Projects shown on the portfolio
|--------------------------------------------------------------------------
| This is the only place you need to edit to change a project. Projects appear
| in the order listed here. After changing anything, run:
|
|     php artisan portfolio:export
|
| to rebuild the static site in dist/ (the folder Vercel serves).
|
| Fields (leave one empty, or remove it, to show a "to be added" placeholder):
|   slug          address of the details page, /projects/{slug}  (letters, numbers, hyphens)
|   title         project name
|   type          'web', 'mobile' or 'desktop'
|   summary       short description (shown on the card)
|   problem       the business problem it solves
|   card_summary  optional shorter description for the card (so it never ends with "..."); the full summary is used when empty
|   card_problem  optional shorter problem statement for the card; the full text is used when empty
|   solution      how the project solves it
|   features      list of features that are actually implemented (the card shows the first 4)
|   technologies  list of technologies you really used, e.g. ['HTML', 'CSS', 'MySQL']
|   contribution  what you personally built (plain text, blank lines allowed)
|   screenshots   [['src' => 'images/projects/file.png', 'alt' => 'What it shows', 'caption' => 'Optional']]
|                 Put the image files in public/images/projects/.
|   github_url    full https:// link to the repository
|   demo_url      full https:// link to the live demo (the button only shows when this is a valid link)
|   published     set to false to hide a project without deleting it
*/

return [

    [
        'slug' => 'max-gym-integrated-management-system',
        'title' => 'MAX Gym Integrated Management System',
        'type' => 'web',
        'summary' => 'A gym management application for managing members, membership payments, attendance, membership expiration, equipment maintenance, partial payments, and reports.',
        'problem' => 'A gym has to keep track of who its members are, what they have paid (including partial payments), when they attend, when memberships expire, and which equipment needs maintenance. This project brings those records into one system and produces reports from them.',
        'card_summary' => 'A gym management app for members, payments, attendance, equipment and reports.',
        'card_problem' => 'A gym needs to keep track of its members, what they have paid, attendance, expirations and equipment maintenance.',
        'solution' => 'One integrated application that handles member records, membership payments and partial payments, attendance, membership expiration, equipment maintenance, and reports.',
        'features' => [
            'Member management',
            'Membership payments',
            'Partial payments',
            'Attendance',
            'Membership expiration',
            'Equipment maintenance',
            'Reports',
        ],
        // Read from the project's own files (composer.json, .env.example, layout). Edit if anything is off.
        'technologies' => ['PHP', 'Laravel', 'Blade', 'MySQL', 'Tailwind CSS'],
        'contribution' => null, // optional: if you fill this in, a "My development contribution" section appears
        'screenshots' => [
            ['src' => 'images/projects/maxgym-dashboard.jpg', 'alt' => 'MAX Gym operations dashboard with membership counts, revenue summary and check-ins', 'caption' => 'Operations dashboard (sample data)'],
            ['src' => 'images/projects/maxgym-members.jpg', 'alt' => 'Members and prepaid memberships list with status and days left', 'caption' => 'Members and memberships (sample data)'],
            ['src' => 'images/projects/maxgym-revenue-reports.jpg', 'alt' => 'Revenue reports page with daily, monthly and yearly views', 'caption' => 'Revenue reports (sample data)'],
            ['src' => 'images/projects/maxgym-equipment.jpg', 'alt' => 'Equipment inventory grouped by category with status', 'caption' => 'Equipment inventory (sample data)'],
            ['src' => 'images/projects/maxgym-maintenance.jpg', 'alt' => 'Equipment maintenance reports with priority and repair status', 'caption' => 'Equipment maintenance (sample data)'],
        ],
        'github_url' => 'https://github.com/Raiha204/max_gym_maximum_fitness',
        'demo_url' => null,
    ],

    [
        'slug' => 'reading-habit-analytics',
        'title' => 'Reading Habit Analytics',
        'type' => 'mobile',
        'summary' => 'A mobile application for tracking reading progress, reading time, book collections, reading streaks, and reading statistics.',
        'problem' => 'Readers who want a steady habit need a simple way to see how far they have read, how long they read, and how consistent they are. This app keeps progress, time, book collection, streaks, and statistics in one place.',
        'card_summary' => 'A mobile app for tracking reading progress, time, book collections, streaks and statistics.',
        'card_problem' => 'Readers who want a steady habit need a simple way to see how far, how long and how consistently they read.',
        'solution' => 'A mobile application that records reading progress and reading time, keeps a book collection, tracks reading streaks, and presents reading statistics.',
        'features' => [
            'Reading progress',
            'Reading time',
            'Book collections',
            'Reading streaks',
            'Reading statistics',
        ],
        // Read from the project's own files (pubspec.yaml, lib/). Edit if anything is off.
        'technologies' => ['Flutter', 'Dart', 'Cloud Firestore', 'Firebase Authentication', 'Google Sign-In'],
        'contribution' => null,
        // Phone screenshots from the real app. Portrait images are shown in a tall frame on the details page.
        'screenshots' => [
            ['src' => 'images/projects/reading-overview.jpg', 'alt' => 'Reading Habit Analytics on a phone: home, analytics and goals screens side by side', 'caption' => 'Home, analytics and goals'],
            ['src' => 'images/projects/reading-home.jpg', 'alt' => 'Home screen with a daily reading streak card, reading time and pages read this week', 'caption' => 'Home: streak and weekly reading rhythm'],
            ['src' => 'images/projects/reading-analytics.jpg', 'alt' => 'Analytics screen with total reading time, sessions, pages read and a reading minutes chart', 'caption' => 'Analytics: reading time and sessions'],
            ['src' => 'images/projects/reading-goals.jpg', 'alt' => 'Goals screen with a monthly reading challenge and active goals with progress bars', 'caption' => 'Goals and challenges'],
            ['src' => 'images/projects/reading-profile.jpg', 'alt' => 'Profile screen with books finished, reading time, best streak and an achievement shelf', 'caption' => 'Profile and achievements'],
        ],
        'github_url' => 'https://github.com/Raiha204/Reading_Analytic_Habit_App',
        'demo_url' => null,
    ],

    [
        'slug' => 'farm-supply-inventory-management-system',
        'title' => 'Farm Supply Inventory Management System',
        'type' => 'web',
        'summary' => 'A system that helps farm supply businesses manage inventory records, organize product information, monitor stock levels, and maintain accurate inventory data.',
        'problem' => 'Farm supply businesses carry many different products and need to know what is in stock. This system keeps inventory records and product information organized so stock levels can be monitored and the data stays accurate.',
        'card_summary' => 'Helps farm supply businesses manage inventory and stock levels.',
        'card_problem' => 'Farm supply businesses carry many different products and need to know what is in stock.',
        'solution' => 'A system that stores inventory records, organizes product information, lets the business monitor stock levels, and keeps inventory data accurate.',
        'features' => [
            'Inventory management',
            'Product tracking',
            'Stock level monitoring',
            'Accurate inventory data',
            // Products and suppliers have add, edit and delete screens in the project's routes and views.
            'CRUD operations (create, read, update, delete)',
            // Add this one only once you have confirmed it is implemented in this project:
            // 'Database management',
        ],
        // Read from the project's own files (composer.json, package.json, .env.example). Edit if anything is off.
        'technologies' => ['PHP', 'Laravel', 'Blade', 'MySQL', 'Tailwind CSS', 'Alpine.js', 'Vite'],
        'contribution' => null,
        'screenshots' => [
            ['src' => 'images/projects/farm-staff-dashboard.jpg', 'alt' => 'Staff dashboard with available products, low stock alerts and daily sales', 'caption' => 'Staff dashboard (sample data)'],
            ['src' => 'images/projects/farm-products.jpg', 'alt' => 'Product management table with category, price, stock and edit or delete actions', 'caption' => 'Product management (sample data)'],
            ['src' => 'images/projects/farm-inventory.jpg', 'alt' => 'Inventory page for recording stock in and stock out transactions', 'caption' => 'Stock in / stock out (sample data)'],
            ['src' => 'images/projects/farm-admin-dashboard.jpg', 'alt' => 'Admin dashboard with total products, sales, low stock queue and supplier count', 'caption' => 'Admin dashboard (sample data)'],
            ['src' => 'images/projects/farm-reports.jpg', 'alt' => 'Reports page with sales totals, items sold by date and export options', 'caption' => 'Reports (sample data)'],
        ],
        'github_url' => 'https://github.com/Raiha204/Farm-Supply-Inventory-System',
        'demo_url' => null,
    ],

    [
        'slug' => 'grocery-store-point-of-sale-system',
        'title' => 'Grocery Store Point-of-Sale System',
        'type' => 'desktop',
        'summary' => 'A desktop application for managing grocery products, sales transactions, and inventory records using a database.',
        'problem' => 'A grocery store needs to record its sales, keep its product list current, and know what it has in inventory. This application handles products, sales transactions, and inventory records together.',
        'card_summary' => 'A desktop application for grocery products, sales transactions and inventory records.',
        'card_problem' => 'A grocery store needs to record its sales, keep its product list current, and know what it has in inventory.',
        'solution' => 'A desktop application that manages grocery products, sales transactions, and inventory records, with all data stored in a database.',
        'features' => [
            'Grocery product management',
            'Sales transactions',
            'Inventory records',
            'Database-backed data storage',
        ],
        // Read from the project's own files (the Python imports). Edit if anything is off.
        'technologies' => ['Python', 'Tkinter', 'MySQL'],
        'contribution' => null,
        'screenshots' => [
            ['src' => 'images/projects/pos-cashier-sale.jpg', 'alt' => 'Cashier point-of-sale screen with product list, shopping cart, discount and payment options', 'caption' => 'Cashier sale screen (sample data)'],
            ['src' => 'images/projects/pos-admin-dashboard.jpg', 'alt' => 'Admin dashboard with total products, daily sales, active cashiers and low stock items', 'caption' => 'Admin dashboard (sample data)'],
            ['src' => 'images/projects/pos-products.jpg', 'alt' => 'Admin product management table with add, edit and delete actions', 'caption' => 'Product management (sample data)'],
            ['src' => 'images/projects/pos-sales.jpg', 'alt' => 'Sales records table filtered by date with receipt details', 'caption' => 'Sales records (sample data)'],
        ],
        'github_url' => 'https://github.com/Raiha204/POS-GROCERY-SYSTEM',
        'demo_url' => null,
    ],

    [
        'slug' => 'nolune-ecommerce-store',
        'title' => 'NOLUNE E-commerce Store',
        'type' => 'web',
        'summary' => 'A fashion e-commerce store with a product catalog, shopping cart, wishlist, checkout, order tracking, customer accounts, and an administration area for products, categories, and orders.',
        'problem' => 'An online fashion store needs to show its products, let customers shop and place orders, and give the owner a way to manage products, categories, stock, and orders. This project provides the storefront, the customer account area, and the administration in one application.',
        'card_summary' => 'A fashion e-commerce store with a product catalog, cart and checkout.',
        'card_problem' => 'An online fashion store needs to show its products, let customers place orders, and let the owner manage products and orders.',
        'solution' => 'A Laravel application with a React and TypeScript storefront served through Inertia.js. Laravel handles the catalog, authentication, cart, wishlist, checkout, orders, customer accounts, and protected administration. Checkout totals and inventory are recalculated on the server inside a database transaction.',
        'features' => [
            'Product catalog with product detail pages',
            'Shopping cart and wishlist',
            'Checkout and order confirmation (payment methods are simulated; no real payments are processed)',
            'Customer registration, login, and profile management',
            'Customer account area: orders, shipped and delivered purchases, return requests, product reviews, and browsing history',
            'Administration area for managing products, categories, stock, and orders, restricted to administrators',
        ],
        // Read from the project's own files (composer.json, package.json and README). Edit if anything is off.
        'technologies' => ['PHP', 'Laravel', 'React', 'TypeScript', 'Inertia.js', 'Vite', 'Tailwind CSS', 'MySQL'],
        'contribution' => null,
        'screenshots' => [
            ['src' => 'images/projects/nolune-home.jpg', 'alt' => 'NOLUNE storefront home page with search, category navigation and a hero banner', 'caption' => 'Storefront home page'],
            ['src' => 'images/projects/nolune-catalog.jpg', 'alt' => 'NOLUNE product catalog with filters for category, price, size, color, sale and stock', 'caption' => 'Product catalog with filters'],
            ['src' => 'images/projects/nolune-register.jpg', 'alt' => 'NOLUNE customer registration page', 'caption' => 'Customer registration'],
        ],
        'github_url' => 'https://github.com/Raiha204/nolune-ecommerce',
        'demo_url' => null,
    ],

];
