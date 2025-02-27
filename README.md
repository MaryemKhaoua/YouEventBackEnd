# YouEventBackEnd 🎟️

A Laravel-based event management API for user registration, event creation, ticket booking, and reservations.

## 🚀 Features
- User Authentication (Register, Login, Logout)
- Role-based Access Control (Admin, Organizer, User)
- Event Management (Create, Update, Delete, List)
- Ticketing System (Pricing, Quantity)
- Reservations (User can reserve tickets for events)
- Email Notifications for Password Reset

---

## 🛠️ Installation

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/MaryemKhaoua/YouEventBackEnd.git
cd YouEventBackEnd

### 2️⃣ Install Dependencies

```bash
composer install

### 3️⃣ Set Up Environment Variables
Copy the .env.example file to .env:

```bash
cp .env.example .env

Then, update database credentials in the .env file
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password


### 4️⃣ Generate Application Key

```bash
php artisan key:generate

### 5️⃣ Run Database Migrations & Seeders
```bash
php artisan migrate --seed


