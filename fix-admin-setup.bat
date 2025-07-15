@echo off
echo Running E-Commerce Application Setup...

echo.
echo 1. Adding missing database columns...
php artisan migrate

echo.
echo 2. Seeding brands data...
php artisan db:seed --class=BrandSeeder

echo.
echo 3. Creating storage directories...
if not exist "storage\app\public\brands" mkdir "storage\app\public\brands"
if not exist "storage\app\public\products" mkdir "storage\app\public\products"
if not exist "storage\app\public\categories" mkdir "storage\app\public\categories"

echo.
echo 4. Creating storage link...
php artisan storage:link

echo.
echo 5. Clearing application cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo.
echo Setup completed! You can now:
echo - Add/Edit/Delete Brands at: http://localhost:8000/admin/brands
echo - Create Products with Categories and Brands at: http://localhost:8000/admin/products
echo.
echo Starting development server on port 8000...
php artisan serve --port=8000

pause
