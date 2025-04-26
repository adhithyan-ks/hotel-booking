# 🏨 Hotel Room Booking Management System

Welcome to the **Hotel Room Booking Management System** project!  
This is a complete web-based application that allows **users to book hotel rooms** and **admins to manage hotel operations** like rooms, bookings, users, and more.

---

## 📚 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Database Structure](#database-structure)
- [Installation](#installation)
- [Usage Guide](#usage-guide)
- [Screenshots](#screenshots)

---

## 📖 About the Project

This project is a **Hotel Management Web Application** built using:

- PHP (backend)
- MySQL (database)
- HTML, CSS, JavaScript (frontend)

It allows **users** to easily find and book available rooms, select service preferences (like breakfast, dinner), and **admins** to manage all operations from a central dashboard.

---

## ✨ Features

### User Side:
- User Registration & Login
- Browse available room types
- Check room availability
- Book rooms online
- Select service preferences (Breakfast, Dinner, Additional Services)
- View and manage their own bookings
- Cancel future bookings
- Edit profile details

### Admin Side:
- Admin login (basic, session-based)
- View and manage all bookings
- Search users by ID, name, or phone number
- Add offline bookings from the counter (cash payments)
- Quick room availability checker
- View booking service details
- Manage users and booking history
- Admin dashboard with total users, total rooms, total bookings, revenue stats

---

## 🛠 Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP        | Backend Server-side Scripting |
| MySQL      | Database Storage |
| HTML5/CSS3 | Frontend Structure and Styling |
| JavaScript | Frontend Interactions |
| XAMPP      | Local Development Environment |

---

## 🗄️ Database Structure

Main tables:

- **users**  
  (`user_id`, `name`, `email`, `password`, `phone`, `created_at`)

- **rooms**  
  (`room_id`, `room_type`, `status`)

- **room_types**  
  (`room_type`, `description`, `price_per_night`, `image_url`)

- **bookings**  
  (`booking_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `breakfast`, `breakfast_time`, `dinner`, `dinner_time`, `additional_services`, `status`, `booking_source`, `admin_id`, `booked_at`)

- **payments**  
  (`payment_id`, `booking_id`, `payment_status`, `payment_method`, `transaction_id`)

✅ Note:  
Services like breakfast, dinner, and additional services are stored directly inside the `bookings` table.

---

## ⚙️ Installation

1. **Clone or Download** the project.
2. Place the project folder inside your `htdocs/` if using XAMPP.
3. Import the provided SQL file into **phpMyAdmin**.
4. Update your database credentials inside `includes/config.php`.
5. Access it via your browser:  
   `http://localhost/hotel-booking/`

---

## 📖 Usage Guide

- **User**: Register → Login → Browse Rooms → Check Availability → Book Room → Proceed to Payment
- **Admin**: Login to Admin Panel → Manage Bookings → Manage Users → Add Offline Bookings
- **Services**: Select breakfast, dinner, and extra services during booking.

---

## 📷 Screenshots

> (Add screenshots of the User Home page, Booking page, Admin Dashboard, and Manage Bookings page.)


---