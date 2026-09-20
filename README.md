Markdown
# 🛒 Scalable E-Commerce API & FilamentPHP Admin Dashboard

A production-grade, highly scalable E-Commerce RESTful API built with **Laravel 11** and **FilamentPHP v3**. Designed with clean code practices, modular payment integration based on the **Open/Closed Principle (OCP)** using **Stripe API**, and optimized for production using **Eloquent API Resources**.

---

## 📸 Admin Dashboard Overview (FilamentPHP)

| Dashboard Overview | Order & Payment Management |
| :---: | :---: |
| ![Chat Overview](/images/chat.png) | ![Orders Management](/images/orders.png) |
  ![Categoris Management](/images/categoris.png)

*(Note: Place your screenshots inside `docs/images/` directory with the names `dashboard.png` and `orders.png`)*

---

## ✨ Key Features & Architecture

* **Decoupled Payment Architecture:** Built using `PaymentGatewayInterface` following the Open/Closed Principle (OCP). Adding new payment providers (e.g., Fawry, Paymob) requires zero modifications to existing controllers.
* **Stripe REST Integration:** Native API integration using Laravel's `Http` client to eliminate heavy SDK dependencies and version conflicts.
* **Real-time Webhook Listener:** Automated status transition (`unpaid` ➔ `paid`) upon receiving verified Stripe webhook signatures.
* **Interactive Admin Panel:** Full CRM management using **FilamentPHP v3** with real-time status badges for payment and order processing.
* **Clean API Resources:** Standardized API responses via `ProductResource` and `CategoryResource` ensuring sanitized JSON, relative image URL transformation, and clean data pagination.
* **Advanced Query Filtering:** Full support for searching, price filtering, and category scoping.

---

## 🛠️ Tech Stack

* **Backend Framework:** Laravel 11.x
* **Admin Panel:** FilamentPHP v3.x
* **Payment Gateway:** Stripe API (v1 Checkout Sessions & Webhooks)
* **Database:** MySQL / SQLite
* **Architecture:** Repository Pattern, Service Layer, SOLID (OCP)

---

## 📂 Project Structure Highlights

```text
app/
├── Contracts/
│   └── PaymentGatewayInterface.php     # Core Interface for Gateways
├── Services/
│   └── Payments/
│       └── StripePaymentService.php    # Stripe Implementation (HTTP Client)
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── PaymentController.php   # Handles Checkout & Webhooks
│   │       └── ProductController.php   # API Resource & Filters
│   └── Resources/
│       ├── ProductResource.php         # Clean API JSON Formatting
│       └── CategoryResource.php
└── Filament/
    └── Resources/
        └── OrderResource.php           # Admin Dashboard Order Tracking

```
## 🚀 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/Mastermohamedsaleh/your-repo-name.git](https://github.com/Mastermohamedsaleh/your-repo-name.git)
   cd your-repo-name

**Install Composer dependencies:**
composer install

**Configure Environment File:**
cp .env.example .env
php artisan key:generate


**Set Up Database & Run Seeders:**
php artisan migrate --seed

**Link Storage Directory:**
php artisan storage:link


**Start Local Server:**
php artisan serve