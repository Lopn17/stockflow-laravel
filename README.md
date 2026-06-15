# StockFlow

A modern, portfolio-grade Inventory Management System built with Laravel using clean architecture principles, Repository Pattern, Service Layer, Events, Policies, and Role-Based Access Control (RBAC).

Designed to showcase production-level Laravel development practices while solving real-world inventory management problems.

---

## Features

### Inventory Management
- Stock In
- Stock Out
- Stock Adjustment
- Inventory Transaction History
- Low Stock Detection
- Out of Stock Monitoring

### Product Management
- Product CRUD
- SKU Management
- Barcode Support
- Product Images
- Category Assignment
- Supplier Assignment
- Soft Deletes

### Dashboard
- Total Products
- Total Categories
- Total Suppliers
- Stock Value
- Low Stock Alerts
- Out of Stock Statistics
- Stock Movement Charts
- Top Products by Value

### Reporting
- Inventory Reports
- PDF Export
- Excel Export
- Transaction Reports

### User Management
- Admin
- Staff
- Viewer

### Notifications
- Low Stock Alerts
- Activity Logging
- Event-Based Notifications

---

# Tech Stack

## Backend
- Laravel
- PHP 8+
- MySQL

## Frontend
- Blade
- Tailwind CSS
- Alpine.js
- Chart.js

## Architecture
- Service Layer Pattern
- Repository Pattern
- Event Driven Architecture
- Policy-Based Authorization
- Form Request Validation
- Dependency Injection

---

# Architecture

The project follows Separation of Concerns.

## Controllers
Responsible only for handling HTTP requests and responses.

## Services
Contain business logic.

Examples:
- Stock movements
- Report generation
- Activity logging

## Repositories
Handle database operations and data access.

## Models
Represent database entities and relationships.

## Policies
Handle authorization logic.

## Events & Listeners
Handle side effects such as:
- Logging
- Notifications
- Low stock checks

---

# Project Structure

```bash
stockflow/
├── app/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Listeners/
│   ├── Models/
│   ├── Notifications/
│   ├── Policies/
│   ├── Repositories/
│   └── Services/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
└── resources/
    └── views/
```

---

# Database Design

## Categories
Stores product categories.

## Suppliers
Stores supplier information.

## Products
Stores inventory items.

Important fields:

- SKU
- Barcode
- Purchase Price
- Selling Price
- Minimum Stock
- Current Stock

## Inventory Transactions

Tracks all inventory movements.

Types:

- stock_in
- stock_out
- adjustment

## Activity Logs

Stores user activity history.

---

# Role-Based Access Control (RBAC)

## Admin

Can:
- Manage products
- Manage suppliers
- Manage categories
- Manage users
- Manage inventory
- Access activity logs

## Staff

Can:
- Manage inventory
- View products
- View reports

## Viewer

Can:
- View products
- View reports

Cannot:
- Modify data

---

# Inventory Workflow

## Stock In

1. User submits stock-in request
2. InventoryService validates data
3. Product stock increases
4. Transaction recorded
5. Event dispatched
6. Activity logged

## Stock Out

1. Validate stock availability
2. Reduce stock
3. Save transaction
4. Dispatch event
5. Log activity

## Low Stock Detection

Whenever stock changes:

```php
if ($product->current_stock <= $product->minimum_stock)
```

The system automatically:

- Sends notifications
- Displays dashboard alerts

---

# Event-Driven Design

## Events

- StockUpdated
- ProductCreated

## Listeners

### CheckLowStock

Checks inventory thresholds.

### LogStockUpdate

Creates activity log entries.

Benefits:

- Decoupled architecture
- Easier maintenance
- Easier feature expansion

---

# Security

## Authorization

Implemented using Laravel Policies.

Examples:

```php
ProductPolicy
InventoryPolicy
SupplierPolicy
```

## Validation

Implemented using Form Requests.

Examples:

```php
StoreProductRequest
UpdateProductRequest
StoreInventoryTransactionRequest
```

---

# Dashboard Features

- Total Products
- Total Categories
- Total Suppliers
- Inventory Value
- Low Stock Alerts
- Out of Stock Monitoring
- Recent Transactions
- Inventory Charts

---

# API & Routes

## Products

```http
GET     /products
POST    /products
PUT     /products/{id}
DELETE  /products/{id}
```

## Categories

```http
GET     /categories
POST    /categories
PUT     /categories/{id}
DELETE  /categories/{id}
```

## Suppliers

```http
GET     /suppliers
POST    /suppliers
PUT     /suppliers/{id}
DELETE  /suppliers/{id}
```

## Inventory

```http
POST /inventory/stock-in/{product}
POST /inventory/stock-out/{product}
POST /inventory/adjustment/{product}
```

---

# Installation

Clone the repository:

```bash
git clone https://github.com/yourusername/stockflow.git
```

Navigate into the project:

```bash
cd stockflow
```

Install dependencies:

```bash
composer install
npm install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database settings inside:

```env
DB_DATABASE=stockflow
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Start the development server:

```bash
php artisan serve
```

Compile frontend assets:

```bash
npm run dev
```

---

# Testing

Run all tests:

```bash
php artisan test
```

### Unit Tests

- Services
- Policies
- Repositories

### Feature Tests

- Controllers
- Authentication
- Authorization
- Inventory Workflow

---

# Architectural Decisions

### Why Repository Pattern?

- Easier testing
- Database abstraction
- Cleaner business logic

### Why Service Layer?

- Keeps controllers thin
- Centralizes business rules

### Why Events?

- Decouples side effects
- Easier scaling

### Why Soft Deletes?

Inventory history must never lose references to products.

### Why Database Transactions?

Ensures inventory consistency.

```php
DB::transaction(...)
```

Either all operations succeed or none do.

---
