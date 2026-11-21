CREATE DATABASE IF NOT EXISTS library_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_management;
CREATE TABLE admins (
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, username VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE books (
 id INT AUTO_INCREMENT PRIMARY KEY, isbn VARCHAR(30) UNIQUE, title VARCHAR(200) NOT NULL,
 author VARCHAR(150) NOT NULL, publisher VARCHAR(150), category_id INT NULL,
 published_year SMALLINT NULL, shelf_no VARCHAR(40), total_copies INT NOT NULL DEFAULT 1,
 available_copies INT NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_book_category FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL,
 CHECK(total_copies >= 0), CHECK(available_copies >= 0), CHECK(available_copies <= total_copies),
 INDEX idx_book_title(title), INDEX idx_book_author(author)
) ENGINE=InnoDB;
CREATE TABLE members (
 id INT AUTO_INCREMENT PRIMARY KEY, member_code VARCHAR(30) NOT NULL UNIQUE, name VARCHAR(120) NOT NULL,
 email VARCHAR(150), phone VARCHAR(20), address TEXT, joined_on DATE NOT NULL,
 status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_member_name(name)
) ENGINE=InnoDB;
CREATE TABLE loans (
 id INT AUTO_INCREMENT PRIMARY KEY, book_id INT NOT NULL, member_id INT NOT NULL,
 issue_date DATE NOT NULL, due_date DATE NOT NULL, return_date DATE NULL,
 fine_amount DECIMAL(10,2) NOT NULL DEFAULT 0, status ENUM('Issued','Returned') NOT NULL DEFAULT 'Issued',
 issued_by INT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_loan_book FOREIGN KEY(book_id) REFERENCES books(id),
 CONSTRAINT fk_loan_member FOREIGN KEY(member_id) REFERENCES members(id),
 CONSTRAINT fk_loan_admin FOREIGN KEY(issued_by) REFERENCES admins(id) ON DELETE SET NULL,
 INDEX idx_loan_status(status), INDEX idx_loan_due(due_date)
) ENGINE=InnoDB;
CREATE TABLE settings (setting_key VARCHAR(80) PRIMARY KEY, setting_value VARCHAR(255) NOT NULL) ENGINE=InnoDB;
INSERT INTO categories(name) VALUES ('Computer Science'),('Literature'),('History'),('Mathematics'),('Science'),('General');
INSERT INTO settings VALUES ('fine_per_day','5'),('loan_days','14');
-- Default administrator: username admin, password password (change immediately after first login).
INSERT INTO admins(name,username,password_hash) VALUES
('Library Administrator','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llcZ9w6zVQq2j6zq9s6eK');
INSERT INTO books(isbn,title,author,publisher,category_id,published_year,shelf_no,total_copies,available_copies) VALUES
('9780132350884','Clean Code','Robert C. Martin','Prentice Hall',1,2008,'CS-A1',5,5),
('9780133594140','The C Programming Language','Brian W. Kernighan','Pearson',1,1988,'CS-A2',3,3),
('9780140449136','The Odyssey','Homer','Penguin Classics',2,-700,'LT-B1',4,4);
INSERT INTO members(member_code,name,email,phone,address,joined_on) VALUES
('LIB-1001','Aarav Sharma','aarav@example.com','9876543210','Lucknow, Uttar Pradesh','2026-01-10'),
('LIB-1002','Priya Verma','priya@example.com','9876501234','Lucknow, Uttar Pradesh','2026-02-05');
