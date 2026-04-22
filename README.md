# ⛳ Digital Heroes Platform

A full-stack web application that combines **golf performance tracking, charity contributions, and a monthly reward draw system**.
Built as part of a full-stack development assignment based on a Product Requirements Document (PRD).

---

## 🚀 Live Demo

🌐 https://golfplatform.infinityfreeapp.com

---

## 👤 Test Credentials

### User

* Email: [Rama@gmail.com]
* Password: Rama@123

### Admin

* Email: [admin_golf_platform@gmail.com]
* Password: Admin@123

---

## 📌 Features

### 🔐 Authentication

* User registration and login
* Role-based access (User / Admin)
* Session-based authentication

### 💳 Subscription System

* Multiple plans (Basic, Pro, Premium)
* Payment integration (Razorpay UI)
* Active subscription detection

### ⛳ Score Management

* Users can enter golf scores (1–45)
* Date-based score entry
* Displays last 5 scores

### ❤️ Charity System

* Charity listing with search
* Charity details with images & events
* User can select charity and contribution %

### 🎯 Draw System

* Monthly draw mechanism
* Admin can run and preview draws
* Users can view draw results

### 👤 User Dashboard

* Subscription status
* Score entry and tracking
* Charity selection
* Draw participation overview
* Winnings display

### 🛠️ Admin Dashboard

* Manage charities (add/view)
* Run and simulate draws
* View users and winners
* Prize distribution overview

---

## 🧱 Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP (Core PHP)
* **Database:** MySQL
* **Hosting:** InfinityFree
* **Payment Integration:** Razorpay (UI)

---

## 📂 Project Structure

```
/golf_platform
│── auth.php
│── homepage.php
│── userdashboard.php
│── admindashboard.php
│── charities.php
│── charity_details.php
│── subscription.php
│── draws.php
│── config.php
│── navbar.php
│── logout.php
│
├── /css
├── /js
├── /api
```

---

## 🔒 Security

* Password hashing using `password_hash()`
* Prepared statements for database queries
* Session-based authentication
* Protected routes for admin/user access

---

## 📱 UI / UX

* Clean and modern interface
* Mobile responsive design
* User-friendly navigation
* Focus on charity impact and engagement

---

## ⚠️ Notes

* Payment flow is simulated (Razorpay UI integration)
* Draw logic implemented for demonstration purposes
* System is designed to be extensible for production use

---

## 📈 Future Improvements

* Full payment verification system
* Advanced draw algorithm
* Email notifications
* Real-time analytics dashboard
* Upload-based winner verification

---

## 🏁 Conclusion

This project demonstrates a complete full-stack application with:

* Authentication
* Subscription logic
* Data handling
* Admin control panel
* Interactive UI

Designed with scalability and real-world use cases in mind.

---

## 🙌 Acknowledgement

Developed as part of the **Digital Heroes Full-Stack Training Selection Process**.
