# Online Computer Shop - Web Technologies Project

An e-commerce platform for PC peripherals and components built with PHP MVC architecture.

## Project Overview

- **Course**: Web Technologies (Project 02)
- **Duration**: 1 week
- **Team Size**: 4 students (task-based division)
- **Architecture**: PHP MVC with PDO database abstraction

## Features

### For Customers
- User registration and authentication with "Remember Me" functionality
- Browse products by category, sub-category, and brand
- Search and filter products with AJAX
- View detailed product information
- Add products to cart with AJAX
- Post and delete product reviews
- Place orders with payment method selection (Cash on Delivery / Online Wallet)
- User profile management

### For Admins
- Manage categories and sub-categories
- Manage brands
- Full product management (CRUD)
- Admin dashboard with inventory stats and low-stock alerts
- Remove customers and their associated reviews

## Task Breakdown

| Student ID | Task | Responsibility |
|-----------|------|-----------------|
| 22-48356-3 | Task 1 | Authentication, Registration, Profile, Home Page, Categories, Featured Products |
| 22-48412-3 | Task 2 | Admin Panel - Category, Brand, Product Management |
| 22-48484-3 | Task 3 | Customer browsing, Search/Filtering (AJAX), Cart Management |
| 22-49926-3 | Task 4 | Reviews, Orders, Admin customer/review removal |

## Setup Instructions

### 1. Database Setup

1. Open phpMyAdmin or your MySQL client
2. Create a new database: `online_computer_shop`
3. Import the SQL schema:
   ```sql
   source database.sql
   ```

### 2. Configuration

1. Update database credentials in `config/db.php` if needed:
   - `DB_HOST`: localhost (default)
   - `DB_USER`: root (default)
   - `DB_PASS`: (leave empty for default XAMPP setup)
   - `DB_NAME`: online_computer_shop

### 3. Directory Permissions

Ensure the following directories are writable:
```bash
public/uploads/
public/uploads/products/
logs/
```

### 4. Access the Application

- **Local URL**: `http://localhost/WEBTECH/Online-Computer-Shop/`
- **Admin Login**: Use admin credentials created during registration
- **Customer Login**: Use customer credentials

## Project Structure

```
Online-Computer-Shop/
├── config/              # Configuration files
│   ├── db.php          # Database connection
├── controllers/         # Request handlers
│   └── Controller.php   # Base controller class
├── models/             # Data models
├── views/              # View templates (HTML)
├── public/             # Public assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── uploads/       # User uploads
│       └── products/  # Product images
├── api/               # AJAX endpoints
├── database.sql       # Database schema
├── index.php          # Application entry point
└── README.md          # This file
```

## Technical Requirements

✅ **Security**
- SQL injection prevention with prepared statements
- XSS protection with output escaping
- CSRF token generation and verification
- Secure password hashing with `password_hash()`

✅ **Database**
- PDO with prepared statements for all queries
- Proper relationships and foreign keys
- Data integrity constraints

✅ **MVC Architecture**
- Clear separation of concerns
- Base controller for common functionality
- Reusable validation and helper classes

✅ **Client-Side & Server-Side Validation**
- JavaScript validation for user feedback
- PHP validation before database operations

✅ **AJAX & JSON**
- Multiple AJAX endpoints for dynamic interactions
- JSON responses with proper error handling

✅ **Authentication & Sessions**
- Session-based authentication
- Role-based access control (Admin/Customer/Guest)
- "Remember Me" functionality with secure tokens

## Git Workflow

1. Create feature branch: `git checkout -b feature/taskX-studentID`
2. Make commits (at least 3 per student)
3. Push to repository
4. Create Pull Request to merge into `main`
5. Final main contains all feature branches with full history

### Example:
```bash
git checkout -b feature/task1-22483563
git add .
git commit -m "Add user authentication system"
git commit -m "Implement registration with validation"
git commit -m "Add profile management and Remember Me"
git push origin feature/task1-22483563
# Create PR on GitHub
```

## Security Checklist

- ✅ Prepared statements on all queries
- ✅ Password hashing with `password_hash()` / `password_verify()`
- ✅ Session management with `session_start()`
- ✅ CSRF tokens on all forms
- ✅ Input sanitization and validation
- ✅ Output escaping for HTML content
- ✅ File upload validation (MIME type, size)
- ✅ Role-based access control
- ✅ Secure cookie configuration
- ✅ Error logging (not displayed to users)

## Testing

### Test Accounts (to be created)
- Admin: admin@example.com (password: password123)
- Customer: customer@example.com (password: password123)

### Key Features to Test
1. User registration with email validation
2. Login with "Remember Me" functionality
3. Category and product browsing
4. Product search and filtering via AJAX
5. Add to cart and cart management
6. Checkout and order placement
7. Product review posting and deletion
8. Admin dashboard and management functions

## Grading Criteria (10 requirements)

1. **Web Security** - SQL injection, XSS, CSRF, password storage ✅
2. **UI** - Clean, responsive HTML/CSS interface ✅
3. **Feature Completeness** - All assigned features working error-free ✅
4. **Database** - Correct schema usage, relationships, integrity ✅
5. **Authentication** - Session/cookie management, role-based access ✅
6. **MVC** - Clear separation of concerns ✅
7. **JS Validation** - Client-side form validation ✅
8. **PHP Validation** - Server-side input validation ✅
9. **AJAX/JSON** - Multiple AJAX endpoints with JSON responses ✅
10. **Git** - Feature branches, meaningful commits, PR merge workflow ✅

## Troubleshooting

### Database Connection Error
- Verify XAMPP MySQL is running
- Check database name in `config/db.php`
- Ensure database user and password match

### Session Issues
- Ensure `config/bootstrap.php` is included before any output
- Check that `session_start()` is called early in the bootstrap

### File Upload Issues
- Verify `public/uploads/` directory exists and is writable
- Check file size and MIME type validation in Validator class

## Support

For issues or questions, contact your instructor or team lead.

---

**Last Updated**: May 2026
**Team Project** - All students working together for final delivery