1. composer create-project laravel/laravel project-name
2. cd project-name
3. Update .env file with database details.
4. php artisan migrate
5. composer require laravel/breeze --dev
6. php artisan breeze:install
7. npm install && npm run dev
8. php artisan make:migration create_roles_table
9. php artisan make:migration add_role_id_to_users_table --table=users
10. php artisan migrate
11. php artisan make:seeder RoleSeeder
12. php artisan db:seed --class=RoleSeeder
13  php artisan tinker
14. php artisan make:middleware RoleMiddleware
15. Register middleware in Kernel.php.
16. Add middleware to routes for role-based access.
17. php artisan db:seed --class=UserSeeder
18. php artisan make:migration create_transactions_table
20  create service file in TransactionService.php
21  php artisan make:controller TransactionController
22. php artisan migrate --path=/database/migrations/2025_01_09_125132_create_transactions_table.php


23. Login User Cred

email : testdev@gmail.com
password : 12345678

