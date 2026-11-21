# LIBRARY MANAGEMENT SYSTEM
## Project Report and Implementation Guide

**Technology:** PHP, MySQL, HTML5, CSS3 and JavaScript  
**Project type:** Web-based Database Management System

> This report adapts the academic-project structure of the supplied 76-page reference document (abstract, contents, introduction, problem statement, objectives, scope, requirements, architecture, implementation, testing, results, advantages, limitations, future scope, conclusion and references). The uploaded document itself describes Fake News Detection; its subject matter and Python/ML code are not part of this Library Management System.

## ABSTRACT
The Library Management System is a web-based application designed to computerize routine library operations. It maintains book details, member information, book issue and return transactions, due dates and late fines. The application uses PHP for server-side processing, MySQL for relational data storage, HTML and CSS for the user interface, and JavaScript for client-side interactions. An administrator can manage the catalogue, register members, issue available books, process returns and view circulation reports. Database constraints, prepared statements and session-based authentication help maintain data consistency and protect administrative pages. The system reduces manual registers, improves record retrieval and provides a simple foundation that can be extended for institutional use.

## TABLE OF CONTENTS
1. Introduction
2. Problem Statement
3. Objectives
4. Scope of Project
5. Existing and Proposed System
6. System Requirements
7. Database Design and Data Dictionary
8. System Architecture
9. Data Flow and Process Logic
10. Modules of the System
11. Source Code and Implementation
12. Installation and User Manual
13. Testing and Evaluation
14. Results and Discussion
15. Advantages
16. Limitations
17. Future Enhancements
18. Conclusion
19. References

## 1. INTRODUCTION
A library manages books, readers and circulation records. In a manual environment, staff maintain registers for catalogue entries, membership and issue/return dates. Searching records, checking available copies and identifying overdue books can take time. A computerized Library Management System stores these records in a central relational database and makes common operations available through a browser.

## 2. PROBLEM STATEMENT
Manual library processes may cause duplicate entries, misplaced records, delayed searches and errors in tracking issued copies. Staff may find it difficult to know which copies are available, which member has borrowed a book and which books are overdue. The proposed system provides a centralized database and controlled workflows for catalogue management, member registration, circulation and reporting.

## 3. OBJECTIVES
- Maintain accurate book and member records.
- Search books by title, author and ISBN.
- Track total and available copies.
- Issue books only to active members when copies are available.
- Record issue date, due date and return date.
- Calculate late fines using a configurable per-day rate.
- Generate circulation and overdue reports.
- Reduce repetitive paperwork and improve retrieval time.

## 4. SCOPE OF PROJECT
The application is intended for a small institutional or departmental library. It provides an administrator login, book catalogue, member directory, issue/return desk and reports. The current version is a single-administrator application. Barcode scanning, member self-service, email/SMS alerts and multiple branches are outside the current implementation and are listed as future enhancements.

## 5. EXISTING AND PROPOSED SYSTEM
**Existing system:** Paper registers or disconnected spreadsheets are used to store catalogue and circulation information. Availability checks and fine calculations are performed manually.

**Proposed system:** A PHP web application uses MySQL tables linked by primary and foreign keys. The interface provides forms and searchable tables. The server validates requests and updates book stock and loan records in database transactions.

## 6. SYSTEM REQUIREMENTS
### Hardware
- Processor: Intel Core i3 or equivalent and above
- RAM: 4 GB minimum (8 GB recommended)
- Storage: 1 GB free for application/database, plus room for backups
- Display: 1366×768 or higher recommended

### Software
- Operating system: Windows, Linux or macOS
- Web server: Apache (XAMPP/WAMP/LAMP)
- Server language: PHP 8.0+
- Database: MySQL 5.7+ / MariaDB 10.4+
- Browser: Chrome, Firefox, Edge or equivalent
- Editor: VS Code or any text editor

## 7. DATABASE DESIGN AND DATA DICTIONARY
The database is named `library_management`.

| Table | Purpose | Main fields |
|---|---|---|
| admins | Admin login | id (PK), name, username (unique), password_hash |
| categories | Book categories | id (PK), name (unique) |
| books | Catalogue and inventory | id (PK), isbn (unique), title, author, category_id (FK), total_copies, available_copies |
| members | Library members | id (PK), member_code (unique), name, email, phone, status |
| loans | Issue and return history | id (PK), book_id (FK), member_id (FK), issue_date, due_date, return_date, fine_amount, status |
| settings | Configurable values | setting_key (PK), setting_value |

### Relationships
- One category can contain many books (1:M).
- One book can appear in many loan transactions over time (1:M).
- One member can have many loan transactions over time (1:M).
- One administrator can issue many loans (1:M).

### Integrity rules
- ISBN and member code are unique when supplied.
- `available_copies` cannot be negative or exceed `total_copies`.
- A loan references an existing book and member.
- Active loans prevent deletion of their book/member.
- Issue and return operations update the loan and stock records within a database transaction.

## 8. SYSTEM ARCHITECTURE
Browser (HTML/CSS/JavaScript) → Apache web server → PHP application and validation → PDO prepared statements → MySQL database. The server returns the generated HTML page to the browser. Authentication is maintained using a PHP session.

## 9. DATA FLOW AND PROCESS LOGIC
### Book issue
1. Administrator selects an available book and an active member.
2. PHP validates the submitted identifiers and loan period.
3. A database transaction locks the selected book row.
4. The system confirms that at least one copy is available.
5. A loan row is inserted with issue and due dates.
6. Available copies are reduced by one.
7. The transaction is committed.

### Book return
1. Administrator selects an active loan and confirms return.
2. The system locks the loan row.
3. Late days are calculated from due date to current date.
4. Fine = late days × configured fine per day.
5. Loan status and return date are updated.
6. Available copies are increased by one.
7. The transaction is committed.

## 10. MODULES
### 10.1 Authentication
Checks the administrator username and password hash, regenerates the session ID on login and protects administrative pages.

### 10.2 Dashboard
Shows catalogue count, total copies, active members, active loans and overdue loans, with recent circulation activity.

### 10.3 Book management
Adds, edits, searches and deletes catalogue records. It prevents reducing total stock below the number of copies currently on loan.

### 10.4 Member management
Registers members, updates contact information and changes account status. Inactive members cannot receive new loans.

### 10.5 Issue and return
Issues available copies to active members and processes returns. The configured loan period and fine rate are stored in the settings table.

### 10.6 Reports
Displays all transactions and filters issued, overdue and returned records. A print view is available through the browser.

## 11. SOURCE CODE AND IMPLEMENTATION
The accompanying project ZIP contains the complete PHP source, SQL schema, CSS, JavaScript, setup guide and this report. Important files:
- `config/config.php`: database connection using PDO.
- `includes/functions.php`: escaping, authentication, CSRF and helper functions.
- `database.sql`: database and seed records.
- `login.php`: administrator login.
- `index.php`: dashboard.
- `books.php`: catalogue CRUD.
- `members.php`: member CRUD.
- `issue.php`: issue and return processing.
- `reports.php`: circulation reports.
- `change-password.php`: password update.

## 12. INSTALLATION AND USER MANUAL
1. Install XAMPP and start Apache and MySQL.
2. Copy the project directory into `htdocs`.
3. Open phpMyAdmin and import `database.sql`.
4. Confirm database username/password in `config/config.php`.
5. Open `http://localhost/Library_Management_System/login.php`.
6. Sign in using the initial credentials documented in README.md.
7. Change the default password immediately.
8. Add books and register members.
9. Use Issue / Return to manage circulation.
10. Use Reports to review transactions and print the register.

## 13. TESTING AND EVALUATION
| Test case | Input/action | Expected result |
|---|---|---|
| Login valid | Correct admin credentials | Dashboard opens |
| Login invalid | Incorrect password | Access denied with error |
| Add book | Valid title, author and copies | Book saved |
| Duplicate ISBN | Existing ISBN | Save rejected with message |
| Search book | Title/author/ISBN keyword | Matching books displayed |
| Register member | Unique member code and name | Member saved |
| Issue book | Available copy + active member | Loan created; stock decreases |
| Issue unavailable book | Zero available copies | Issue rejected |
| Issue to inactive member | Inactive member | Issue rejected |
| Return book | Active loan | Loan closed; stock increases |
| Overdue calculation | Due date earlier than return date | Fine calculated at configured rate |
| Delete active-loan book | Book currently on loan | Deletion prevented |
| CSRF validation | Missing/invalid token on POST | Request rejected |

## 14. RESULTS AND DISCUSSION
The implemented modules support the principal workflow of a small library: maintaining books and members, issuing copies, accepting returns and reviewing circulation records. The dashboard provides a quick operational summary. Actual performance depends on the server and database configuration; no measured benchmark or institutional user study is claimed in this report.

## 15. ADVANTAGES
- Centralized and searchable records.
- Faster book and member lookup.
- Better visibility of available copies.
- Consistent issue and return workflow.
- Automatic due-date and late-fine calculation.
- Reports for operational monitoring.
- Responsive browser-based interface.
- Relational constraints and prepared statements improve data integrity and security.

## 16. LIMITATIONS
- Current release is designed for a single administrator account.
- No barcode/RFID integration.
- No member-facing portal or online reservations.
- No email/SMS reminders.
- Fine payment reconciliation is not included.
- Deployment security, backups and access policies must be configured by the institution.

## 17. FUTURE ENHANCEMENTS
- Multiple roles (administrator, librarian and member).
- Member portal with personal borrowing history.
- Barcode/QR scanning and RFID support.
- Email/SMS due-date reminders.
- Online book reservations and renewals.
- Fine payment records and receipts.
- Export reports to PDF/Excel.
- Multi-branch support and audit logs.
- Automated backup and restore facility.

## 18. CONCLUSION
The Library Management System demonstrates how PHP and MySQL can be used to automate common library activities. It provides catalogue management, member registration, book issue and return, fine calculation and reports through a responsive web interface. The system reduces dependence on manual registers and establishes a structured database foundation. With additional roles, notifications, barcode integration and production hardening, it can be extended for larger library environments.

## 19. REFERENCES
1. PHP Documentation, https://www.php.net/docs.php
2. MySQL Reference Manual, https://dev.mysql.com/doc/
3. MDN Web Docs – HTML, CSS and JavaScript, https://developer.mozilla.org/
4. OWASP Cheat Sheet Series – SQL Injection Prevention and CSRF Prevention, https://cheatsheetseries.owasp.org/
5. XAMPP Documentation, https://www.apachefriends.org/
