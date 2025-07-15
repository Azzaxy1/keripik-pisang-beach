@echo off
echo Starting Laravel E-Commerce Setup...
echo.

echo [1/8] Installing Composer dependencies...
call composer install
if %errorlevel% neq 0 (
    echo Error: Composer install failed
    pause
    exit /b 1
)

echo [2/8] Installing NPM dependencies...
call npm install
if %errorlevel% neq 0 (
    echo Error: NPM install failed
    pause
    exit /b 1
)

echo [3/8] Generating application key...
call php artisan key:generate
if %errorlevel% neq 0 (
    echo Error: Key generation failed
    pause
    exit /b 1
)

echo [4/8] Running database migrations...
call php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo Error: Database migration failed
    pause
    exit /b 1
)

echo [5/8] Creating storage link...
call php artisan storage:link
if %errorlevel% neq 0 (
    echo Error: Storage link creation failed
    pause
    exit /b 1
)

echo [6/8] Compiling frontend assets...
call npm run dev
if %errorlevel% neq 0 (
    echo Error: Asset compilation failed
    pause
    exit /b 1
)

echo [7/8] Clearing application cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear

echo [8/8] Setting permissions...
mkdir storage\app\public\categories 2>nul
mkdir storage\app\public\products 2>nul
mkdir storage\app\public\brands 2>nul

echo.
echo ========================================
echo Laravel E-Commerce Setup Complete!
echo ========================================
echo.
echo Application URL: http://localhost:8000
echo Admin Panel: http://localhost:8000/admin/dashboard
echo.
echo Default Admin Credentials:
echo Email: admin@ecommerce.com
echo Password: password
echo.
echo Default Customer Credentials:
echo Email: customer@ecommerce.com
echo Password: password
echo.
echo To start the application, run:
echo php artisan serve
echo.
pause
