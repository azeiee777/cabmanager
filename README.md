CabManager Elite

CabManager Elite is a premium, fully responsive financial tracking dashboard designed exclusively for professional cab and fleet drivers. Built with Laravel 12 and a highly optimized Blade + jQuery + AJAX frontend, it acts as a lightweight Single Page Application (SPA) without the heavy overhead of a traditional JavaScript framework.

Features

Real Net Profit Tracking: Automatically deducts MCD taxes and paid tolls from gross fares to calculate true take-home earnings.

Deadhead Analysis: Logs empty kilometers driven between pickups to help drivers understand their true per-kilometer profitability.

Expense Management: Categorizes and tracks fuel, maintenance, EMIs, and challans.

Toll Analytics: Dedicated section to balance collected tolls versus out-of-pocket toll expenses.

Responsive Design: Features a mobile-first premium UI that seamlessly transitions into a professional SaaS sidebar layout on desktop screens.

Indian Vehicle Number Validation: Built-in support and validation for standard Indian state codes (e.g., DL, UP, HR) and the BH series.

API-Driven Architecture: All frontend interactions are powered by asynchronous jQuery requests communicating with secure Laravel API endpoints.

Tech Stack

Backend: Laravel 12, MySQL 8

Frontend: Blade Templating, Tailwind CSS (via CDN), jQuery, Lucide Icons

Authentication: Session-based authentication via AJAX

Installation

Follow these steps to set up the project locally:

Clone the repository:

git clone <[Repo](https://github.com/azeiee777/cabmanager)>
cd cabmanager


Install PHP dependencies:

composer install


Copy the environment file and configure your database credentials:

cp .env.example .env


Generate the application key:

php artisan key:generate


Run the database migrations:

php artisan migrate


Start the local development server:

php artisan serve
