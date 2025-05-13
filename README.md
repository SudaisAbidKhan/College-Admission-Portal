🎓 College Admission Portal
A web-based application built using PHP and MySQL to manage student admission processes, document uploads, admin validation, and reporting. Ideal for educational institutions seeking a lightweight and structured digital admissions workflow.

📌 Features
👤 Admin Panel
Secure admin login/logout

Dashboard for application monitoring

Student data management

Upload and verify documents

Check payment receipt uploads

Generate detailed reports

Export data as needed

👨‍🎓 Student Functions
Online registration form

Upload required admission documents

Payment proof submission

🛠️ Tech Stack
Layer	Technology
Frontend	HTML, CSS, Bootstrap
Scripting	JavaScript
Backend	PHP
Database	MySQL

📂 File Structure (Flat Layout)

College_admission_portal/
├── admin_dashboard.php
├── admin_login.php
├── check_payment_receipt.php
├── college_portal.sql
├── config.php
├── db_connection.php
├── documentation.docx
├── export_data.php
├── generate_reports.php
├── index.php
...
⚙️ Setup Instructions
Clone or Download the Project


git clone <repository-url>
Or manually extract the ZIP file contents.

Create a MySQL Database

Create a new database named college_portal (or your preferred name).

Import the provided SQL file: college_portal


Make sure you have XAMPP install
Move the project to your web server’s root directory (e.g., htdocs in XAMPP).

Visit in browser:

http://localhost/College_admission_portal/index.php
🔐 Default Admin Credentials
Add an admin user manually to the database in your admin or users table.
Example credentials:

Username: admin

Password: admin123

🧰 Future Improvements
Student login panel to track application status

PDF generation for application summaries

Notification system (email/SMS) for updates

Mobile responsive UI improvements

📄 License
This project is for educational use.
You are free to use or modify it for learning or non-commercial purposes.

✍️ Author
Sudais Khan