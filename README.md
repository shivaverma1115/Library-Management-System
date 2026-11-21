# Library Management System (PHP + MySQL)

A responsive academic project built with PHP 8+, MySQL, HTML5, CSS3 and vanilla JavaScript.

## Modules
- Admin authentication and session handling
- Dashboard with books, active members, issued books and overdue counts
- Book catalogue: add, edit, search and delete; tracks total and available copies
- Member directory: register, edit, search and deactivate/delete members
- Circulation: issue books, return books, due dates and automatic late-fine calculation
- Reports: all / issued / overdue / returned transactions and print view
- Password change page (`change-password.php`)

## Requirements
- XAMPP / WAMP / LAMP
- PHP 8.0 or later with PDO MySQL extension
- MySQL 5.7+ or MariaDB 10.4+
- Modern browser

## Installation (XAMPP on Windows)
1. Install and start **Apache** and **MySQL** in XAMPP.
2. Copy the `Library_Management_System` folder into `C:\xampp\htdocs\`.
3. Open `http://localhost/phpmyadmin`.
4. Create/import the database by selecting **Import** and choosing `database.sql` from this project. The script creates `library_management`.
5. Open `config/config.php`. Set `$user` and `$pass` to match your local MySQL account. XAMPP defaults are usually `root` and an empty password.
6. Visit `http://localhost/Library_Management_System/login.php`.
7. Sign in with the initial credentials below and immediately change the password.

**Initial administrator:** username `admin`, password `password`.

## Important
- The SQL seed contains a default admin password hash for `password`. Change it after the first login.
- Change database credentials before deployment. Do not use the development defaults on a public server.
- Run over HTTPS in production, disable PHP error display, configure backups, and restrict access to the admin area.
- The fine rate is seeded as ₹5/day and the loan period as 14 days. Adjust `settings` table values as needed.
- A book with active loans cannot be deleted. A member with active loans cannot be deleted. Historical transaction records are retained.
- This is a local academic starter application; add role-based access, audit logging, automated backups and stronger deployment hardening before real institutional use.

## Main folder structure
```
Library_Management_System/
  assets/css/style.css
  assets/js/app.js
  config/config.php
  includes/functions.php
  includes/header.php
  includes/footer.php
  database.sql
  login.php
  logout.php
  index.php
  books.php
  members.php
  issue.php
  reports.php
  change-password.php
  README.md
  PROJECT_REPORT.md
```

## Test checklist
1. Login with the initial admin credentials.
2. Add a book with two copies; confirm it appears in the catalogue.
3. Register an active member.
4. Issue the book to that member; available copies should decrease by one.
5. Return the book; available copies should increase by one.
6. Set a loan due date in the past in the database to test overdue status and late fine.
7. Search books by title, author or ISBN and members by name, member code or phone.
8. Try deleting a book/member with an active loan; the system should prevent it.
9. Change the admin password and sign in again.

## Database tables
- `admins`: administrator credentials
- `categories`: book categories
- `books`: catalogue and copy inventory
- `members`: registered library members
- `loans`: issue/return transaction history and fines
- `settings`: configurable loan period and fine rate
