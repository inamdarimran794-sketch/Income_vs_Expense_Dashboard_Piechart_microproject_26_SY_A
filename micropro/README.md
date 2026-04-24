# Income vs Expense Pie Chart Dashboard

A simple PHP + MySQL + JavaScript dashboard that shows **Income vs Expense** in a pie chart.

## Requirements

- PHP (with mysqli)
- MySQL (or MariaDB) — use phpMyAdmin to create the database
- A web server (XAMPP, WAMP, or PHP built-in server)

## Setup

1. **Create the database**
   - Open **phpMyAdmin** (e.g. `http://localhost/phpmyadmin`).
   - Go to **Import** or **SQL** tab.
   - Run the contents of `setup.sql` (creates database `income_expense`, table `transactions`, and sample data).

2. **Configure database connection** (if needed)
   - Edit `config/db.php` and set `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` to match your MySQL setup.

3. **Run the project**
   - **Option A:** Put the project folder under your XAMPP/WAMP `htdocs` and open:  
     `http://localhost/Test/` (or your folder name).
   - **Option B:** From the project folder run:
     ```bash
     php -S localhost:8000
     ```
     Then open: `http://localhost:8000`

## What you get

- **Total Income** and **Total Expense** cards.
- **Pie chart** (Chart.js) showing Income vs Expense.
- **Net balance** (Income − Expense) with color (green/red).

Data is read from the `transactions` table. Add or edit rows in phpMyAdmin to see the dashboard update after refresh.
