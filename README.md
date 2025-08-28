# ForgeFlow: 3D Printing Operations CRM

## Introduction

Welcome to **ForgeFlow**, a comprehensive management tool designed specifically for the operational needs of a 3D printing business. ForgeFlow acts as a central hub for your team, allowing you to manage the entire lifecycle of a customer's print order—from the initial request to the final shipment. It is an internal tool built to streamline your production pipeline, enhance team collaboration, and provide clear insights into your business performance.

---

## ✨ Complete PHP Implementation

This repository now contains a **fully functional PHP-based ForgeFlow CRM system** that implements all the features described in the original README specifications.

### 🚀 Live Demo
- **URL**: Start the application with `php -S localhost:8000 -t public/`
- **Login**: `admin@forgeflow.com` / `admin123`

### 📸 Screenshot
![ForgeFlow Dashboard](https://github.com/user-attachments/assets/08af95d4-0471-40da-8304-ad4fafb2870d)

---

## The ForgeFlow Workflow

The application is designed to mirror the natural progression of a 3D printing job. Every order moves through a clear and defined set of statuses, ensuring that every team member knows the exact stage of any project at a glance.

The typical order lifecycle is as follows:

1.  **Pending**: A new order has been created and is awaiting review and confirmation.
2.  **Processing**: The order has been approved, and the 3D model is being prepared for printing (e.g., slicing, checking for errors).
3.  **Printing**: The order is actively being printed on a 3D printer.
4.  **Post-Processing**: The print has finished, and it is now undergoing finishing touches like support removal, sanding, or cleaning.
5.  **Ready for Steadfast**: The order is complete, packaged, and waiting to be dispatched to the courier.
6.  **Shipped**: The package has been handed over to the courier and is on its way to the customer.
7.  **Completed**: The order has been successfully delivered to the customer.

---

## Key Features

ForgeFlow is equipped with a suite of powerful features to manage every aspect of your 3D printing service.

### Order Management

This is the core of ForgeFlow. Team members can create new print orders with all the necessary details, including customer information, the 3D design file, a reference image, material and color choices, and pricing. All orders are displayed in a central dashboard that can be easily **searched, sorted, and filtered** by their current status, giving a complete overview of the entire production queue.

### Automated Courier Integration

To save time and reduce manual entry, ForgeFlow integrates directly with courier services.

* **Delivery Pre-Check**: When creating an order, the system can automatically check the customer's phone number to retrieve their past delivery history, helping the team assess the reliability of the provided address.
* **Automated Dispatch**: Once an order is marked as "Ready for Steadfast," the system automatically sends all necessary shipping details to the courier. It then retrieves and saves the tracking information, and updates the order's status to "Shipped."

### Dashboard & Reporting

* **At-a-Glance Dashboard**: The main dashboard provides a high-level overview of the business, showing key metrics like the number of active orders, the size of the production queue, and recent shipments. It also features a chart for tracking monthly order volume.
* **Data Exports**: The Reports section allows you to generate and export data summaries, such as order histories, into CSV files for business analysis or record-keeping.

### User Management

An administrator can easily manage the internal team by **adding, editing, or deleting team members**. Each user can be assigned a specific role (e.g., Admin, Team Member), which will determine their access level and permissions within the application.

### Centralized Customer Communication

ForgeFlow includes a dedicated messaging center that allows the team to **communicate directly with customers about specific orders**. This keeps all conversations organized and linked to the relevant order, eliminating the need to search through external emails or messages.

---

## 🛠 Technical Implementation

### Backend Architecture
- **PHP 8.0+**: Modern object-oriented PHP with MVC pattern
- **Database**: SQLite with automatic migration (MySQL compatible)
- **Authentication**: Secure session-based authentication with password hashing
- **File Uploads**: Support for 3D models (.stl, .obj, .3mf, .gcode) and reference images

### Frontend Technology
- **Bootstrap 5**: Responsive, mobile-first design
- **Chart.js**: Interactive data visualization and reporting charts
- **Vanilla JavaScript**: Lightweight, no heavy frameworks

### Security Features
- Role-based access control (Admin vs Team Member)
- CSRF protection and XSS prevention
- Secure file upload handling with type validation
- Password hashing with PHP's built-in functions

---

## 🚀 Quick Start

### Requirements
- PHP 8.0 or higher
- Web server with URL rewriting support
- SQLite extension (usually included)

### Installation
1. Clone the repository
2. Point your web server document root to the `public/` directory
3. Ensure `uploads/` and `database/` directories are writable
4. Visit the application - database will be created automatically
5. Login with: `admin@forgeflow.com` / `admin123`

### Development Server
```bash
cd forgeflow
php -S localhost:8000 -t public/
```

---

## 📋 Features Implemented

### ✅ Core Functionality
- [x] **Complete Order Management** - Create, view, edit, delete orders
- [x] **7-Stage Workflow** - All status states with transition tracking
- [x] **File Upload System** - 3D models and reference images
- [x] **Customer Database** - Automatic customer detection and management
- [x] **Search & Filtering** - Advanced order filtering by status and content

### ✅ User Interface
- [x] **Responsive Dashboard** - Mobile-friendly interface
- [x] **Navigation System** - Role-based menu structure
- [x] **Data Visualization** - Charts for monthly trends and status distribution
- [x] **Status Management** - Visual status badges with color coding

### ✅ User Management
- [x] **Authentication System** - Secure login/logout
- [x] **Role-Based Access** - Admin and Team Member permissions
- [x] **User Administration** - CRUD operations for team members

### ✅ Communication
- [x] **Messaging System** - Order-linked customer communications
- [x] **Message Threading** - Complete conversation history
- [x] **Team Collaboration** - Internal notes and status updates

### ✅ Reporting & Analytics
- [x] **Business Dashboard** - Key performance indicators
- [x] **Data Export** - CSV export for orders and customers
- [x] **Status Analytics** - Visual breakdown of order pipeline
- [x] **Revenue Tracking** - Financial performance metrics

### ✅ File Management
- [x] **Secure Uploads** - Type validation and organized storage
- [x] **Download System** - Direct access to customer files
- [x] **Image Preview** - Reference image display

---

## 🎯 Usage Guide

### Creating Your First Order
1. Click "New Order" from the dashboard
2. Enter customer details (system will detect existing customers)
3. Upload 3D design file and optional reference image
4. Set material, color, and pricing
5. Submit to create order in "Pending" status

### Managing the Production Pipeline
- Use the dashboard to see order distribution across all statuses
- Filter orders by status or search by customer/order details
- Click "View" on any order to see full details and history
- Use "Edit" to update order information and advance status

### Team Administration (Admin Only)
- Navigate to Users section
- Add new team members with appropriate roles
- Admins have full access, Team Members can manage orders and communicate

### Customer Communication
- From any order view, access the messaging system
- Record both team messages and customer communications
- All messages are linked to specific orders for easy reference

---

## 📁 Project Structure

```
forgeflow/
├── public/                 # Web root
│   ├── index.php          # Application entry point & routing
│   └── .htaccess          # URL rewriting rules
├── src/                   # Application source
│   ├── Controllers/       # Request handlers
│   ├── Models/           # Database models
│   └── Views/            # HTML templates
├── config/               # Configuration
├── database/             # Schema & SQLite database
├── uploads/              # File storage
└── README.md             # This file
```

---

## 🔧 Customization

The application is built with extensibility in mind:

- **Add Order Statuses**: Modify `Order::getStatuses()` 
- **Custom Fields**: Extend database schema and models
- **New User Roles**: Update `User::getRoles()` and permissions
- **UI Themes**: Customize Bootstrap variables and CSS
- **Database**: Switch to MySQL/PostgreSQL for production

---

## 🌟 Future Enhancements

The foundation is in place for additional features:
- Real-time notifications and email alerts
- Advanced reporting with date ranges and filters  
- Customer portal for order tracking
- Inventory management for materials
- Mobile app integration
- API endpoints for third-party integrations

---

**ForgeFlow** - Streamlining 3D printing operations, one order at a time.