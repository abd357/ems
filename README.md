# EMS (Employee Management System)

A full-stack Employee Management System built with **Laravel (backend)** and **Vue.js (frontend)**. The system allows for managing users, roles, employees, and provides a clean user interface powered by Vue.js.

---

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Vue.js (inside `resources/js`)
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Seeder Support**: Yes

---

## Installation Instructions

### Clone the Repository

```bash
git clone https://github.com/abd357/ems.git
cd ems

### Setting up the project
composer install
cp .env.example .env
php artisan key:generate
npm install
php artisan migrate --seed
php artisan serve
npm run dev