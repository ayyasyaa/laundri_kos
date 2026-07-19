# 🧺 Lestari Laundry & Kost

A comprehensive, modern management system for managing room rentals (Kost) and laundry services. Built on top of Laravel, Tailwind CSS, and Vite.

---

## ⚡ Quick Start (Recommended)

You can set up the entire project with a single command if you have PHP, Composer, and Node.js installed globally:

```bash
# 1. Clone the repository
git clone <repository-url>
cd CAPSTONE

# 2. Run the automatic setup script
composer run setup

# 3. Seed the database with demo data
php artisan db:seed

# 4. Start the development server
composer run dev
```

The application will be running at [http://localhost:8000](http://localhost:8000).

---

## 🛠️ Requirements

Ensure your local development environment meets the following specifications:
* **PHP**: `^8.3`
* **Composer**: Latest version
* **Node.js**: `^18.x` or `^20.x` (with NPM)
* **Database**: SQLite (default, database file is auto-created) or MySQL

---

## 📋 Step-by-Step Manual Setup

If you prefer to configure the application manually, follow these steps:

### 1. Install Dependencies
Install PHP dependencies via Composer and frontend assets via NPM:
```bash
composer install
npm install
```

### 2. Environment Configuration
Copy the template environment file to create your local `.env`:
```bash
copy .env.example .env     # Windows
# OR
cp .env.example .env       # macOS / Linux
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Database Setup (SQLite)
By default, the application is configured to use SQLite.
```bash
# Create the SQLite database file
copy NUL database\database.sqlite   # Windows PowerShell/CMD
# OR
touch database/database.sqlite      # macOS / Linux / Git Bash
```
> [!NOTE]
> If you wish to use MySQL, update the `DB_*` variables in your `.env` file before migrating.

### 5. Run Migrations & Seeders
Run the migrations to create the database schema and populate the database with default roles, settings, services, and demo records:
```bash
php artisan migrate --seed
```

### 6. Build Assets
Compile the CSS and JS assets using Vite:
```bash
# Build for production
npm run build

# OR start Vite live reload for development
npm run dev
```

---

## 👥 Demo User Accounts

The database seeders configure three default accounts with the password `password`:

| Role | Username | Email | Permissions |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin@laundrykost.com` | Full system administration and settings control |
| **Owner** | `owner` | `owner@laundrykost.com` | Access to financial transactions and business analytics |
| **Staff** | `staff` | `staff@laundrykost.com` | Operational access to manage tenants, rooms, and laundry |

---

## 🚀 Running the App

Run both the Laravel server and frontend compiler concurrently:
```bash
composer run dev
```
This runs the local PHP server, queue workers, log stream, and Vite asset compiler all in one terminal.

---

## 🧪 Testing

To run the suite of feature and unit tests:
```bash
composer run test
```
