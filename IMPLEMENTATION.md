# ForgeFlow: 3D Printing Operations CRM

A comprehensive management tool designed specifically for the operational needs of a 3D printing business. ForgeFlow acts as a central hub for your team, allowing you to manage the entire lifecycle of a customer's print order—from the initial request to the final shipment.

## Features Implemented

### 🎯 Order Management
- **Complete Order Lifecycle**: 7 distinct status states (Pending, Processing, Printing, Post-Processing, Ready for Steadfast, Shipped, Completed)
- **Order Creation**: Full form with customer information, order details, and file uploads
- **Order Tracking**: Automatic order number generation and status history tracking
- **File Management**: Support for 3D design files (STL, OBJ, 3MF, GCODE) and reference images
- **Search & Filter**: Advanced filtering by status, customer name, and order details

### 👥 User Management
- **Role-Based Access**: Admin and Team Member roles with appropriate permissions
- **User Administration**: Create, edit, and delete team members (Admin only)
- **Authentication**: Secure login system with session management

### 📊 Dashboard & Analytics
- **Real-time Metrics**: Order counts by status, production queue overview
- **Monthly Charts**: Visual tracking of order volume trends
- **Status Distribution**: Pie chart showing current order status breakdown

### 💬 Customer Communication
- **Message Threading**: Organized conversations linked to specific orders
- **Dual Communication**: Handle both team and customer messages
- **Message History**: Complete audit trail of all customer interactions

### 📈 Reports & Data Export
- **CSV Exports**: Generate order and customer data exports for analysis
- **Business Intelligence**: Revenue tracking and performance metrics
- **Data Visualization**: Interactive charts and graphs

### 🎨 User Interface
- **Responsive Design**: Bootstrap-based interface that works on all devices
- **Intuitive Navigation**: Clear sidebar navigation with role-based menu items
- **Modern UI**: Clean, professional design with status badges and visual indicators

## Technical Architecture

### Backend
- **PHP 8.0+**: Modern PHP with object-oriented programming
- **MVC Pattern**: Clean separation of concerns with Models, Views, Controllers
- **Database**: SQLite for easy deployment (MySQL support available)
- **File Handling**: Secure file upload system with type validation

### Frontend
- **Bootstrap 5**: Responsive CSS framework
- **Chart.js**: Interactive data visualization
- **Bootstrap Icons**: Comprehensive icon set
- **Vanilla JavaScript**: No heavy frontend frameworks for better performance

### Security
- **Authentication**: Session-based authentication with secure password hashing
- **Authorization**: Role-based access control
- **File Security**: Secure file upload handling with type validation
- **XSS Protection**: HTML escaping and sanitization

## Installation & Setup

### Requirements
- PHP 8.0 or higher
- Web server (Apache/Nginx) with mod_rewrite
- SQLite extension (usually included with PHP)

### Quick Start
1. Clone the repository
2. Point your web server to the `public/` directory
3. Ensure the `uploads/` and `database/` directories are writable
4. Access the application in your browser
5. Login with demo credentials: `admin@forgeflow.com` / `admin123`

### Database Setup
The application automatically creates the SQLite database on first run with sample data including:
- Default admin user
- Sample customers
- Example orders in different statuses

## Usage Guide

### Creating Orders
1. Navigate to Dashboard → "New Order"
2. Fill in customer information (system will detect existing customers)
3. Add order details (description, material, color, pricing)
4. Upload 3D design files and reference images
5. Submit to create order with "Pending" status

### Managing Order Workflow
1. View orders on the Dashboard or Orders page
2. Click "View" to see order details and status history
3. Click "Edit" to update order information and status
4. Status changes are automatically tracked with timestamps

### Customer Communication
1. From any order view, click "Send Message"
2. Add messages from team members or on behalf of customers
3. View complete conversation history linked to each order
4. Access all recent messages from the Messages page

### User Administration (Admin Only)
1. Navigate to Users page
2. Add new team members with appropriate roles
3. Edit user information and roles as needed
4. Delete users when team members leave

### Reports & Analytics
1. Visit the Reports page for business overview
2. View order status distribution and monthly trends
3. Export order data to CSV for external analysis
4. Track revenue and performance metrics

## File Structure

```
forgeflow/
├── public/                 # Web root directory
│   ├── index.php          # Application entry point and routing
│   └── .htaccess          # URL rewrite rules
├── src/                   # Application source code
│   ├── Controllers/       # Request handlers
│   ├── Models/            # Database models
│   └── Views/             # HTML templates
├── config/                # Configuration files
├── database/              # Database schema and SQLite file
├── uploads/               # File upload storage
└── composer.json          # PHP dependencies
```

## Customization

### Adding New Order Statuses
1. Update the `Order::getStatuses()` method in `src/Models/Order.php`
2. Add corresponding CSS classes in `src/Views/layout.php`
3. Update status transition logic as needed

### Extending User Roles
1. Add new roles to `User::getRoles()` in `src/Models/User.php`
2. Update authorization checks in controllers
3. Modify UI elements based on new role permissions

### Custom Fields
1. Add database columns using SQL migrations
2. Update model classes to handle new fields
3. Modify forms and views to display new fields

## Production Deployment

### Recommended Settings
- Use MySQL/PostgreSQL instead of SQLite for better performance
- Configure proper web server security headers
- Set up SSL/HTTPS for secure communication
- Implement regular database backups
- Configure file upload size limits appropriately

### Environment Configuration
Create a `.env` file with production settings:
```
DB_HOST=your_mysql_host
DB_NAME=forgeflow_production
DB_USER=your_db_user
DB_PASS=your_secure_password
APP_DEBUG=false
APP_URL=https://your-domain.com
```

## Support & Development

This implementation provides a solid foundation for a 3D printing business operations system. The modular architecture allows for easy extension and customization to meet specific business requirements.

### Future Enhancements
- Courier API integration for automated shipping
- Email notification system
- Advanced reporting and analytics
- Mobile app companion
- Inventory management
- Customer portal

## License

This project is developed as part of a demonstration and is available for use and modification according to your needs.

---

**ForgeFlow** - Streamlining 3D printing operations, one order at a time.