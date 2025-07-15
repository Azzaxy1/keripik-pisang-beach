# Laravel E-Commerce Platform

A full-featured e-commerce application built with Laravel, featuring a modern frontend shop, comprehensive admin panel, and secure payment integration.

## Features

### Frontend Features
- **Homepage**: Hero section, featured categories, products showcase
- **Product Catalog**: Browse products with filters and search
- **Shopping Cart**: Add/remove items, quantity management
- **User Authentication**: Registration, login, profile management
- **Checkout Process**: Multi-step checkout with address management
- **Payment Integration**: Razorpay payment gateway
- **Order Tracking**: View order history and status
- **Wishlist**: Save favorite products
- **Product Reviews**: Rate and review products

### Admin Panel Features
- **Dashboard**: Sales overview, analytics, and quick stats
- **Product Management**: CRUD operations for products, categories, brands
- **Order Management**: Process orders, update status, manage inventory
- **User Management**: Customer accounts and roles management
- **Content Management**: Manage site content and settings
- **Reports**: Sales reports, inventory reports, customer analytics

### Technical Features
- **Responsive Design**: Mobile-first Bootstrap UI
- **Role-based Access Control**: Using Spatie Laravel Permission
- **Image Management**: Intervention/Image for product images
- **DataTables**: Advanced admin tables with sorting and filtering
- **Security**: CSRF protection, input validation, secure authentication
- **Database**: Optimized MySQL with proper indexing
- **File Storage**: Laravel storage with public disk linking

## Technology Stack

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, jQuery
- **Database**: MySQL
- **Payment**: Razorpay
- **Image Processing**: Intervention/Image
- **Permissions**: Spatie Laravel Permission
- **DataTables**: Yajra Laravel DataTables

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or 8.0+
- Node.js and npm

### Quick Setup (Windows)
1. Clone the repository
2. Run the setup script:
   ```bash
   setup.bat
   ```

### Manual Setup
1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd e-commerce
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Configure Razorpay (for payments)**
   ```env
   RAZORPAY_KEY_ID=your_razorpay_key_id
   RAZORPAY_KEY_SECRET=your_razorpay_key_secret
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Compile assets**
   ```bash
   npm run dev
   ```

10. **Start the server**
    ```bash
    php artisan serve
    ```

## Default Login Credentials

After running the seeders, you can use these credentials:

**Admin Account:**
- Email: admin@example.com
- Password: password

**Customer Account:**
- Email: customer@example.com
- Password: password

## Configuration

### Payment Gateway
Update your `.env` file with Razorpay credentials:
```env
RAZORPAY_KEY_ID=your_key_id
RAZORPAY_KEY_SECRET=your_key_secret
```

### Mail Configuration
Configure mail settings for order notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store"
```

## Usage

### Customer Flow
1. Browse products on the homepage
2. Add products to cart
3. Proceed to checkout
4. Enter shipping address
5. Complete payment via Razorpay
6. Receive order confirmation

### Admin Flow
1. Login to admin panel at `/admin`
2. Manage products, categories, and brands
3. Process customer orders
4. View analytics and reports
5. Manage user accounts and permissions

## API Endpoints

The application includes API endpoints for:
- Product catalog
- Cart management
- Order processing
- User authentication

Documentation available at `/api/documentation` (if API docs are implemented)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Security

- All forms include CSRF protection
- User input is validated and sanitized
- Passwords are hashed using Laravel's bcrypt
- Payment processing uses secure Razorpay integration
- File uploads are validated and secured

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email sunnyjonwal76@gmail.com or create an issue in the repository.
