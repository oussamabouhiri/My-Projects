# 🚀 GearLog - IT Asset Tracker

## 📌 Overview

**GearLog** is a web application designed to manage and track company IT assets such as laptops, monitors, and servers.

It allows users to:

* View all assets in a dashboard
* Add new devices
* Edit existing records
* Delete assets
* Search and filter assets dynamically
* View total inventory value

---

## 🎯 Project Objective

Build a secure and functional asset management system using:

* PHP (Backend)
* MySQL (Database)
* HTML/CSS (Frontend)

---

## 🗂️ Project Structure

```
gearlog/
│
├── db.php        # Database connection (PDO)
├── index.php     # Main dashboard (Read + Search + Filter)
├── add.php       # Add new asset (Create)
├── edit.php      # Edit asset (Update)
├── delete.php    # Delete asset (Delete)
├── style.css     # UI Design
└── index.sql     # Database structure & sample data
```

---

## 🧱 Database Structure

### 1. categories

| Field         | Type     |
| ------------- | -------- |
| id            | INT (PK) |
| hardware_type | VARCHAR  |

### 2. assets

| Field         | Type             |
| ------------- | ---------------- |
| id            | INT (PK)         |
| serial_number | VARCHAR (UNIQUE) |
| device_name   | VARCHAR          |
| price         | DECIMAL          |
| status        | ENUM             |
| category_id   | INT (FK)         |

---

## 🔐 Security Features

✔ PDO Connection with try/catch
✔ Prepared Statements (Prevents SQL Injection)
✔ Data Sanitization using `htmlspecialchars()` (Prevents XSS)

---

## ⚙️ Features

### 📊 Dashboard (index.php)

* Displays all assets using **INNER JOIN**
* Shows category name instead of ID
* Displays total inventory value using `SUM(price)`
* Search by device name or serial number
* Filter by status (In Use / Available / Maintenance)

---

### ➕ Add Asset (add.php)

* Insert new asset into database
* Uses prepared statements

---

### ✏️ Edit Asset (edit.php)

* Update existing asset
* Pre-filled form with current data

---

### ❌ Delete Asset (delete.php)

* Remove asset from database
* Confirmation before deletion

---

## 🎨 UI / UX

* Clean dashboard layout
* Responsive design
* Status color indicators:

  * 🟢 In Use
  * 🔵 Available
  * 🔴 Maintenance
* Table optimized for readability (like admin dashboards)

---

## 🧠 Key Concepts Learned

* Relational Databases & Foreign Keys
* SQL JOIN & Aggregation (SUM)
* PHP PDO & Error Handling
* Web Security Basics (SQL Injection & XSS)
* CRUD Operations
* Dynamic Search & Filtering
* UI/UX Design for Data Tables

---

## 🚀 How to Run the Project

1. Start XAMPP (Apache & MySQL)
2. Place project inside:

```
htdocs/gearlog/
```

3. Import `index.sql` into phpMyAdmin
4. Open browser:

```
http://localhost/gearlog/
```

---

## 📌 Future Improvements

* Authentication system (Login)
* Pagination
* Sorting columns
* Bootstrap integration
* OOP structure (Classes)

---

## 🙌 Acknowledgment

This project is part of my learning journey with:

* Simplon Maghreb
* JobInTech
* Simplon Academy

---

## 💬 Author

Developed by **[Your Name]**
Passionate about Web Development & Backend Engineering 🚀
