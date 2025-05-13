College Admission Portal
This project is a web-based College Admission Portal built using PHP and MySQL. It provides functionality for student registration, admin management, document upload, payment verification, and reporting.

Features
Student registration and login

Admin dashboard for managing applications

Document upload and verification

Payment receipt checking

Data export and report generation

SQL script for setting up the database

Folder Structure
bash
Copy
Edit
College_admission_portal/
├── admin_dashboard.php         # Admin interface
├── admin_login.php             # Admin login
├── check_payment_receipt.php   # Validate student payment
├── college_portal.sql          # MySQL database schema
├── config.php                  # Configuration settings
├── db_connection.php           # Database connection script
├── documentation.docx          # Project documentation
├── export_data.php             # Export student data
├── generate_reports.php        # Report generation
├── index.php                   # Landing page
...
Requirements
PHP 7.x or later

MySQL or MariaDB

Web server (e.g., Apache or Nginx)

Setup Instructions
Clone or extract the project:

bash
Copy
Edit
git clone <repo-url> OR extract the ZIP file
Import the Database:

Create a MySQL database (e.g., college_portal)

Import college_portal.sql using phpMyAdmin or MySQL CLI:

bash
Copy
Edit
mysql -u root -p college_portal < college_portal.sql
Configure the Database Connection:

Open db_connection.php and config.php

Update with your DB host, username, password, and database name

Run the Application:

Place the project folder inside your web server directory (e.g., htdocs for XAMPP)

Start your server and open http://localhost/College_admission_portal/index.php

License
This project is for educational purposes, feel free to use it.

Author
Sudais Khan

