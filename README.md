# E-Commerce System

A modern e-commerce platform built with Laravel 12 and Filament v4, featuring a complete shopping experience with ECPay payment integration.

## 🚀 Features

### Frontend Features
- **Product Catalog**: Browse products by categories with search functionality
- **Shopping Cart**: Add/remove items with real-time quantity updates
- **User Authentication**: Member registration and login system
- **Order Management**: View order history and track order status
- **Checkout Process**: Secure checkout with ECPay payment integration
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

### Admin Panel (Filament v4)
- **Product Management**: CRUD operations for products with image upload
- **Category Management**: Multi-level category system
- **Order Management**: Complete order processing and status tracking
- **Member Management**: Customer account management
- **Dashboard**: Sales statistics and overview widgets

### Payment Integration
- **ECPay Integration**: Secure payment processing
- **Multiple Payment Methods**: Credit card, bank transfer support
- **Order Number Generation**: Daily sequence-based order numbering
- **Payment Status Tracking**: Real-time payment status updates

## 🛠️ Technology Stack

- **Backend**: Laravel 12
- **Admin Panel**: Filament v4
- **Frontend**: Bootstrap 5, jQuery
- **Database**: MySQL/SQLite
- **Payment Gateway**: ECPay (綠界金流)
- **Authentication**: Multi-guard (Admin + Member)

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or SQLite
- Web server (Apache/Nginx) or Laravel Sail

## 🔧 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd shopping-dev2
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` file with your database and ECPay credentials:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# ECPay Configuration
ECPAY_MERCHANT_ID=3002607
ECPAY_HASH_KEY=pwFHCqoQZGmho4w6
ECPAY_HASH_IV=EkRm7iFT261dpevs
ECPAY_PRODUCTION=false

# Session Configuration
SESSION_LIFETIME=120
SESSION_SAME_SITE=lax
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed Database with Sample Data

```bash
php artisan db:seed
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Install Filament Admin Panel

```bash
php artisan filament:install --panels
```

### 10. Create Admin User

```bash
php artisan make:filament-user
```

### 11. Build Frontend Assets

```bash
npm run build
```

## 🚀 Quick Start

### Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Access Admin Panel

Navigate to `http://localhost:8000/backend` and login with your admin credentials.

## 📁 Project Structure

```
shopping-dev2/
├── app/
│   ├── Filament/                 # Filament admin panel resources
│   │   ├── Resources/           # Admin resources (Products, Orders, etc.)
│   │   └── Widgets/             # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/         # Application controllers
│   │   └── Middleware/          # Custom middleware
│   ├── Models/                  # Eloquent models
│   └── Services/                # Business logic services
├── config/                      # Configuration files
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── public/                      # Public assets
├── resources/
│   └── views/                   # Blade templates
├── routes/                      # Application routes
└── storage/                     # File storage
```

## 🔐 Authentication

### Member Authentication
- **Registration**: `/register`
- **Login**: `/login`
- **Profile**: `/profile`
- **Orders**: `/orders`

### Admin Authentication
- **Admin Panel**: `/backend`
- **Dashboard**: `/backend/dashboard`

## 🛒 Shopping Flow

1. **Browse Products**: Visit `/products` to view all products
2. **Add to Cart**: Click "Add to Cart" on any product
3. **View Cart**: Visit `/cart` to review cart items
4. **Checkout**: Proceed to `/checkout` to complete purchase
5. **Payment**: Complete payment via ECPay
6. **Order Confirmation**: View order details and status

## 💳 Payment Integration

### ECPay Configuration
The system integrates with ECPay payment gateway:

- **Test Environment**: Uses ECPay test credentials
- **Production**: Update `.env` with production credentials
- **Payment Methods**: Credit card, bank transfer
- **Order Numbers**: Format: `ORDER + Ymd + 0001` (daily sequence)

### Payment Flow
1. User submits checkout form
2. System creates order and redirects to ECPay
3. User completes payment on ECPay
4. ECPay redirects back with payment result
5. System updates order status

## 🎨 Customization

### Styling
- **CSS**: Located in `resources/css/app.css`
- **JavaScript**: Located in `resources/js/app.js`
- **Bootstrap**: Version 5.3.0 included

### Templates
- **Layout**: `resources/views/layouts/app.blade.php`
- **Components**: `resources/views/components/`
- **Pages**: `resources/views/pages/`

## 📊 Admin Panel Features

### Dashboard Widgets
- **Stats Overview**: Sales, orders, members statistics
- **Recent Orders**: Latest order activity
- **Product Performance**: Top-selling products

### Resource Management
- **Products**: Full CRUD with image management
- **Categories**: Hierarchical category system
- **Orders**: Order processing and status management
- **Members**: Customer account management

## 🔧 Configuration

### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### ECPay Configuration
```env
ECPAY_MERCHANT_ID=your_merchant_id
ECPAY_HASH_KEY=your_hash_key
ECPAY_HASH_IV=your_hash_iv
ECPAY_PRODUCTION=false
```

### Session Configuration
```env
SESSION_LIFETIME=120
SESSION_SAME_SITE=lax
```

## 🚀 Deployment

### Production Deployment Steps

1. **Set Environment**
```bash
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Set File Permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

4. **Configure Web Server**
- Point document root to `public/` directory
- Enable URL rewriting for Apache/Nginx

### Server Requirements
- PHP 8.2+
- MySQL 5.7+ or PostgreSQL 10+
- Composer
- Node.js & NPM (for asset compilation)

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Database Testing
```bash
php artisan test --filter=Database
```

## 📝 API Documentation

### Available Routes

#### Public Routes
- `GET /` - Home page
- `GET /products` - Product listing
- `GET /products/{id}` - Product details
- `GET /cart` - Shopping cart
- `POST /cart/add` - Add to cart
- `GET /checkout` - Checkout page
- `POST /checkout` - Process checkout

#### Member Routes
- `GET /login` - Member login
- `POST /login` - Process login
- `GET /register` - Member registration
- `POST /register` - Process registration
- `GET /orders` - Order history
- `GET /orders/{id}` - Order details

#### Admin Routes
- `GET /backend` - Admin panel
- `GET /backend/dashboard` - Admin dashboard

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review Laravel and Filament documentation

## 🔄 Updates

### Updating Dependencies
```bash
composer update
npm update
```

### Updating Filament
```bash
composer update filament/filament
php artisan filament:upgrade
```

## 📈 Performance Optimization

### Caching
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Optimization
- Add indexes to frequently queried columns
- Use eager loading for relationships
- Implement query optimization

### Asset Optimization
```bash
npm run build --production
```

## 🔒 Security

### Best Practices
- Keep dependencies updated
- Use HTTPS in production
- Implement proper authentication
- Validate all user inputs
- Use CSRF protection
- Secure file uploads

### Environment Security
- Never commit `.env` file
- Use strong database passwords
- Secure API keys and credentials
- Implement rate limiting

---

**Built with ❤️ using Laravel 12 and Filament v4**
