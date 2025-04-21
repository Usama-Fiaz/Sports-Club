# SportsSync - Sports Club Management System

## Overview
SportsSync is a comprehensive sports club management system that provides a platform for managing sports facilities, events, training programs, merchandise, and member interactions. The system is built with PHP and MySQL, offering both user and administrative interfaces. This project was developed as a semester project to demonstrate full-stack web development skills.

## Demo
Check out the live demo of the project: [SportsSync Demo](https://drive.google.com/file/d/11lHPAuv3JTHwk4GdolXHRJbUxU8PfYXX/view?usp=drive_link)

## Key Features

### 1. User Management
- User registration and authentication
- Profile management
- Password recovery system
- Multiple user roles (Admin, Members, Trainers)

### 2. Sports Programs
- Multiple sports categories including:
  - Swimming
  - Tennis
  - Golf
  - Cycling
  - Soccer
  - Basketball
- Professional coaching programs
- Training session bookings
- Training progress tracking

### 3. Event Management
- Event calendar
- Event categories
- Event registration
- Event details and scheduling
- Category-wise event filtering

### 4. E-commerce Integration
- Sports merchandise shop
- Shopping cart functionality
- Wishlist feature
- Multiple payment options
- Order tracking system
- Product categories and subcategories
- Product search and filtering

### 5. Booking System
- Training session bookings
- Facility reservations
- Booking history
- Booking status tracking

### 6. Club Management
- Club registration
- Club listings
- Club details and facilities
- Membership management

### 7. Trainer Portal
- Trainer registration
- Trainer profiles
- Training schedule management
- Session management

### 8. Order Management
- Shopping cart
- Multiple shipping addresses
- Order tracking
- Order history
- Payment processing
- Cancellation handling

### 9. News and Updates
- Club news section
- Event announcements
- Email subscription system
- Newsletter management

### 10. Administrative Features
- User management
- Content management
- Order management
- Event management
- Product management
- Trainer management
- Reports and analytics
- General settings configuration

## Technical Specifications

### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap framework
- jQuery
- Responsive design
- Modern UI/UX
- Animated components
- Interactive carousels

### Backend
- PHP
- MySQL database
- PDO for database operations
- Session management
- Secure authentication

### Security Features
- Password encryption
- Session management
- SQL injection prevention
- XSS protection
- CSRF protection

### Database Structure
- Normalized database design
- Multiple related tables for:
  - Users
  - Products
  - Orders
  - Events
  - Bookings
  - Categories
  - Trainers
  - News
  - Settings

## Installation Requirements
- PHP 8.2.4 or higher
- MySQL 10.4.28 or higher
- Apache/Nginx web server
- mod_rewrite enabled
- GD Library
- PDO extension
- MySQL extension

## Directory Structure
```
SportsClubCode/
├── admin/           # Administrative interface
├── css/            # Stylesheet files
├── js/             # JavaScript files
├── images/         # Image assets
├── includes/       # PHP includes
├── vendor/         # Third-party libraries
├── dist/           # Compiled assets
├── fonts/          # Font files
└── sql/            # Database schema
```

## Setup Instructions
1. Clone the repository
2. Import the database schema from `sql/clubsports.sql`
3. Configure database connection in `includes/config.php`
4. Set up virtual host or configure web server
5. Access the application through web browser

## Default Access
- Admin Panel:
  - URL: `/admin`
  - Username: admin
  - Email: admin@gmail.com

## License
Proprietary software. All rights reserved.

## Support
For technical support or queries, please contact the system administrator. 