# TripTrack 🚌

A comprehensive bus booking and transportation management system built with PHP and MVC architecture.

## 🌟 Features

### For Passengers
- **Guest Booking**: Book tickets without registration
- **User Registration**: Create account for enhanced features
- **Bus Search**: Find buses by route, date, and time
- **Seat Selection**: Choose preferred seats with interactive layout
- **QR Code Tickets**: Digital tickets with QR codes for easy verification
- **PDF Receipts**: Download booking receipts as PDF
- **Payment Integration**: Multiple payment methods support

### For Operators & Administrators
- **Conductor Panel**: Ticket verification and passenger management
- **Regional Admin**: Route and bus management for specific regions
- **Super Admin**: Complete system administration and oversight
- **Bus Management**: Add, edit, and manage bus fleet
- **Schedule Management**: Create and manage bus schedules
- **Customer Support**: Handle customer inquiries and messages

## 🏗️ Technology Stack

- **Backend**: PHP 7+ with custom MVC framework
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Libraries**: 
  - PHPMailer for email functionality
  - QR Code generation library
  - html2pdf for PDF generation
- **Server**: Apache (with .htaccess configuration)

## 📁 Project Structure

```
TripTrack/
├── App/
│   ├── bootloader.php          # Application entry point
│   ├── config/                 # Configuration files
│   ├── controllers/            # MVC Controllers
│   ├── models/                 # MVC Models
│   ├── views/                  # MVC Views
│   ├── libraries/              # Core framework libraries
│   └── helpers/                # Helper functions
├── Public/                     # Public assets
│   ├── CSS/                    # Stylesheets
│   ├── js/                     # JavaScript files
│   ├── images/                 # Image assets
│   └── index.php               # Public entry point
├── vendor/                     # Composer dependencies
└── README.md
```

## 🚀 Installation

### Prerequisites
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache web server
- Composer (for dependency management)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/PasandiRanga/TripTrack.git
   cd TripTrack
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Database Configuration**
   - Create a MySQL database named `triptrack`
   - Import the database schema (if available)
   - Update database credentials in `App/config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'triptrack');
   ```

4. **Web Server Configuration**
   - Place the project in your web server's document root (e.g., `htdocs` for XAMPP)
   - Ensure Apache mod_rewrite is enabled
   - The `.htaccess` files are already configured for URL routing

5. **Email Configuration** (Optional)
   - Update SMTP settings in `App/config/config.php`:
   ```php
   define('SMTP_HOST', 'your_smtp_host');
   define('SMTP_PORT', 587);
   define('SMTP_EMAIL', 'your_email@example.com');
   define('SMTP_PASSWORD', 'your_email_password');
   ```

6. **Access the Application**
   - Navigate to `http://localhost/TripTrack` in your browser
   - The application should be running successfully

## 🎯 Usage

### User Roles & Access

1. **Guest Users**: Can browse schedules and book tickets
2. **Registered Users**: Full booking features with account management
3. **Conductors**: Ticket verification and passenger check-in
4. **Regional Admins**: Manage buses and routes for specific regions
5. **Super Admins**: Complete system administration

### Key URLs
- Home: `/TripTrack/GuestPages/home`
- Registration: `/TripTrack/RegisteredPages/register`
- Login: `/TripTrack/GuestPages/login`
- Admin Panel: `/TripTrack/SuperAdminPages/dashboard`

## 🔧 Configuration

### Environment Setup
Update `App/config/config.php` with your environment-specific settings:
- Database connection details
- Base URL configuration
- Email server settings
- File upload paths

### QR Code Configuration
QR code settings can be modified in `App/libraries/phpqrcode/qrconfig.php`.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PHP PSR standards
- Use meaningful commit messages
- Test your changes thoroughly
- Update documentation as needed

## 📝 License

This project is currently under development. Please contact the repository owner for licensing information.

## 🐛 Issues & Support

If you encounter any issues or need support:
1. Check existing issues in the GitHub repository
2. Create a new issue with detailed description
3. Contact the development team through the support system

## 👥 Team

- **Developer**: PasandiRanga
- **Project**: TripTrack Bus Management System

## 🔄 Version History

- **Current**: Development phase
- Features in active development include enhanced payment processing, real-time bus tracking, and mobile responsiveness

---

**Note**: This project is currently in development. Some features may be incomplete or subject to change.