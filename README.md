<picture>
    <source srcset="public/images/logo.png"  
            media="(prefers-color-scheme: dark)">
    <img src="public/images/logo-dark.png" alt="App Logo">
</picture>

> **Important Note:** This Project is ready for Production.
# Local Installation

- run `` git clone https://github.com/iamfaliqhh/sales-inventory.git ``
- run ``composer install `` 
- run `` npm install ``
- run ``npm run dev``
- copy .env.example to .env
- run `` php artisan key:generate ``
- set up your database in the .env
- run `` php artisan migrate --seed ``
- run `` php artisan storage:link ``
- run `` php artisan serve ``
- then visit `` http://localhost:8000 or http://127.0.0.1:8000 ``.

> **Important Note:** "Sales & Inventory" uses Laravel Snappy Package for PDFs. If you are using Linux then no configuration is needed. But in other Operating Systems please refer to [Laravel Snappy Documentation](https://github.com/barryvdh/laravel-snappy).

# Docker Installation

This will start the application along with the mysql database using docker compose. Note that the `DB_HOST` variable must be the mysql docker container name, in this case `db`.

- run `` docker build -t sales-inventory . `` 
- run `` docker compose up ``
- then visit `` http://localhost:8000 or http://127.0.0.1:8000 ``.

# Admin Credentials
> Email: super.admin@test.com || Password: 12345678

## Triangle POS Features

- **Products Management & Barcode Printing**
- **Stock Management**
- **Make Quotation & Send Via Email**
- **Purchase Management**
- **Sale Management**
- **Purchase & Sale Return Management**
- **Expense Management**
- **Customer & Supplier Management**
- **User Management (Roles & Permissions)**
- **Product Multiple Images**
- **Multiple Currency Settings**
- **Unit Settings**
- **System Settings**
- **Reports**

# License
**[Creative Commons Attribution 4.0	cc-by-4.0](https://creativecommons.org/licenses/by/4.0/)**
