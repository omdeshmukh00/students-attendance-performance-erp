# 📊 ERP-Based Student Attendance & Performance Dashboard

A full-stack web-based ERP system designed to automate student attendance tracking and performance analysis. The system provides real-time insights, role-based dashboards, and efficient academic data management.

---

## 🚀 Features

* 👨‍🎓 Student Management
* 📅 Attendance Tracking (Manual + CSV Upload)
* 📊 Attendance Percentage Calculation
* 📈 Performance Analysis & Insights
* 🧑‍🏫 Role-Based Access (Admin / Teacher / Student)
* 📌 Eligibility Detection (e.g., below 75%)
* 📉 Interactive Dashboard

---

## 🏗️ Tech Stack

### Frontend

* React.js
* TypeScript
* Axios

### Backend

* Laravel (REST API)
* Laravel Sanctum (Authentication)

### Database

* PostgreSQL

---

## ⚙️ System Architecture

* Frontend (React SPA) communicates with Backend via REST APIs
* Backend handles business logic and authentication
* PostgreSQL manages structured data
* Decoupled architecture ensures scalability

---

## 🔐 Authentication

* Token-based authentication using Laravel Sanctum
* Role-Based Access Control (RBAC)
* Secure API communication

---

## 📂 Project Structure

```
/frontend     → React + TypeScript  
/backend      → Laravel API  
/database     → Migrations & schema  
```

---

## 🛠️ Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

---

### 2️⃣ Backend Setup (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

### Configure Database (.env)

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_db_name
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### Run Migrations

```bash
php artisan migrate
```

### Start Server

```bash
php artisan serve
```

---

### 3️⃣ Frontend Setup (React)

```bash
cd frontend
npm install
npm start
```

---

## 📥 CSV Upload (Important Feature)

* Upload attendance data using CSV file
* Ensure format:

```
student_id,subject_id,date,status
1,101,2025-01-10,Present
```

* System processes and stores data automatically

---

## 📊 How It Works

1. User logs in (Admin/Teacher/Student)
2. Attendance data is entered or uploaded
3. Data is stored in PostgreSQL
4. System calculates attendance %
5. Dashboard displays analytics and eligibility

---

## 🚀 Scalability

* Decoupled architecture (React + Laravel)
* Stateless REST APIs
* Database indexing & pagination
* Cloud deployment ready

---


## 👨‍💻 Authors

* Om Deshmukh 
* Yash Sonare

---

## 📜 License

This project is developed for academic purposes.
