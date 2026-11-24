# Cookie & Mable's Secret Society

Also known as the **Puihaha E-Learning Platform: Membership Management and Transaction Processing System**. Built with Laravel 12, MySQL and Blade.

## Features
- Login with Laravel authentication
- Member management: add, view, edit, deactivate, and search by name, category or level
- Membership categories and levels with fees and validity periods
- Transactions: new application and renewal, reference numbers, status tracking (Pending, Paid, Approved, Expired, Cancelled)
- Payments linked to transactions; only paid transactions can be approved
- Transaction history per member and a printable receipt
- Dashboard with totals and a live weather card (OpenWeather)

## Requirements
- PHP 8.2 or higher, Composer, MySQL (XAMPP works)

## Setup
1. Clone the repository and open the folder
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Create a MySQL database named `puihaha_membership` and match the `DB_` values in `.env`
5. Add your own `OPENWEATHER_KEY` in `.env`
6. Run `php artisan key:generate`
7. Run `php artisan migrate`
8. Run `php artisan serve` and open http://127.0.0.1:8000


## Tech
Laravel 12, MySQL, Blade, Bootstrap 5
