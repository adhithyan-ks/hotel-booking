<p align="center">
  <img src="images/logo/hotellogo.png" alt="H-Hotel Logo" width="120" />
</p>

<h1 align="center">H-Hotel - Room Booking Management System</h1>

<p align="center">
  <em>A full-stack hotel room booking &amp; administration platform built with PHP, MySQL, and vanilla web technologies.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5" />
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3" />
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
  <img src="https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white" alt="XAMPP" />
</p>

---

## 📋 About the Project

**H-Hotel** is a comprehensive web-based Hotel Room Booking Management System that streamlines the entire guest experience — from browsing available rooms and selecting service add-ons to completing secure payments — while giving hotel administrators a powerful dashboard to manage bookings, rooms, users, and revenue analytics.

> **Academic Context**  
> This project was developed as a **Minor Project** during the **4th Semester** of a **3-Year Diploma in Computer Engineering** program.

---

## 📸 Screenshot

<p align="center">
  <img src="Screenshot 2025-04-27 215903.png" alt="H-Hotel Booking Page" width="900" />
</p>

---

## ✨ Features

### 🧑‍💻 Guest / User Side

| Feature | Description |
|---|---|
| **Registration & Login** | Secure session-based authentication with form validation |
| **Browse Rooms** | View all available room types with images, descriptions, and pricing |
| **Check Availability** | Real-time room availability check for selected dates |
| **Online Booking** | Book rooms with date selection and automatic price calculation |
| **Service Add-ons** | Opt-in for Breakfast, Dinner, and Additional Services during booking |
| **Payment Processing** | Complete bookings with payment confirmation and transaction tracking |
| **Booking Management** | View booking history and cancel upcoming reservations |
| **Profile Management** | Edit personal details from the Account page |

### 🛡️ Admin Panel

| Feature | Description |
|---|---|
| **Dashboard Analytics** | At-a-glance stats — Total Users, Bookings, Rooms, Revenue, Pending & Confirmed counts |
| **Booking Management** | View, search, and manage all guest bookings |
| **User Management** | Search users by ID, name, or phone number |
| **Offline Booking** | Add walk-in / counter bookings (cash payments) on behalf of guests |
| **Room Availability Checker** | Quick-check tool for room availability by date |
| **Payment Tracking** | Monitor payment statuses and transaction records |
| **Contact Messages** | View messages submitted via the contact form |

---

## 🛠️ Tech Stack

| Layer | Technology | Purpose |
|---|---|---|
| **Backend** | PHP | Server-side logic, session management, database queries |
| **Database** | MySQL | Relational data storage for users, rooms, bookings, payments |
| **Frontend** | HTML5 / CSS3 | Page structure and responsive styling |
| **Interactivity** | JavaScript | Client-side validation and dynamic UI interactions |
| **Icons** | Font Awesome | UI iconography |
| **Environment** | XAMPP | Local Apache + MySQL development server |

---

## 📁 Project Structure

```
hotel-booking/
├── admin/                  # Admin panel
│   ├── inc/                # Admin includes (sidebar, etc.)
│   ├── index.php           # Admin dashboard with analytics
│   ├── bookings.php        # Manage all bookings
│   ├── add_booking.php     # Offline / counter booking form
│   ├── users.php           # User management
│   ├── rooms.php           # Room management
│   ├── payments.php        # Payment records
│   ├── messages.php        # Contact form messages
│   ├── check_room.php      # Room availability checker
│   └── login.php           # Admin authentication
│
├── includes/               # Shared components
│   ├── config.php          # Database connection settings
│   ├── header.php          # Navigation bar
│   ├── footer.php          # Footer component
│   └── logout.php          # Session logout handler
│
├── css/                    # Stylesheets (modular per page)
│   ├── home.css
│   ├── nav.css
│   ├── booking.css
│   ├── rooms.css
│   └── ...                 # 17 CSS files total
│
├── js/                     # Client-side scripts
│   ├── navbar.js           # Responsive navigation
│   └── signup.js           # Registration form validation
│
├── images/                 # Static assets
│   ├── logo/               # Brand logo
│   ├── home/               # Hero section images
│   └── rooms/              # Room type photographs
│
├── db/
│   └── hotel_db.sql        # Database schema & seed data
│
├── home.php                # Landing page with hero & featured rooms
├── rooms.php               # Room listing & browsing
├── booking.php             # Room booking form
├── payment.php             # Payment processing page
├── confirmation.php        # Booking confirmation
├── cancel_booking.php      # Booking cancellation
├── account.php             # User profile management
├── services.php            # Hotel services showcase
├── contact.php             # Contact form
├── about.php               # About the hotel
├── login.php               # User login
├── signup.php              # User registration
└── README.md
```

---

## 🗄️ Database Schema

The application uses a MySQL database (`hotel_db`) with the following tables:

```
┌──────────────┐     ┌──────────────────┐     ┌──────────────┐
│    users     │     │     bookings     │     │   payments   │
├──────────────┤     ├──────────────────┤     ├──────────────┤
│ user_id (PK) │◄────│ user_id (FK)     │     │ payment_id   │
│ name         │     │ booking_id (PK)  │────►│ booking_id   │
│ email        │     │ room_id (FK)     │     │ payment_status│
│ password     │     │ check_in_date    │     │ payment_method│
│ phone        │     │ check_out_date   │     │ transaction_id│
│ created_at   │     │ total_price      │     └──────────────┘
└──────────────┘     │ breakfast        │
                     │ breakfast_time   │
┌──────────────┐     │ dinner           │
│    rooms     │     │ dinner_time      │
├──────────────┤     │ additional_services│
│ room_id (PK) │◄────│ status           │
│ room_type(FK)│     │ booking_source   │
│ status       │     │ admin_id         │
└──────┬───────┘     │ booked_at        │
       │             └──────────────────┘
       │
┌──────▼───────┐
│  room_types  │
├──────────────┤
│ room_type(PK)│
│ description  │
│ price_per_night│
│ image_url    │
└──────────────┘
```

> **Note:** Service preferences (breakfast, dinner, additional services) are embedded directly in the `bookings` table for simplified querying.

---

## ⚙️ Installation & Setup

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any Apache + PHP + MySQL stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- A modern web browser

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/adhithyan-ks/hotel-booking.git
   ```

2. **Move to your web server directory**
   ```bash
   cp -r hotel-booking /path/to/xampp/htdocs/
   ```

3. **Import the database**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database named `hotel_db`
   - Import the SQL file located at `db/hotel_db.sql`

4. **Configure database credentials**  
   Edit `includes/config.php` if your MySQL credentials differ from the defaults:
   ```php
   $host     = "localhost";
   $user     = "root";
   $password = "";
   $db_name  = "hotel_db";
   ```

5. **Start the application**  
   Open your browser and navigate to:
   ```
   http://localhost/hotel-booking/home.php
   ```

6. **Access the Admin Panel**
   ```
   http://localhost/hotel-booking/admin/
   ```

---

## 🚀 Usage

| Role | Workflow |
|---|---|
| **Guest** | Register → Login → Browse Rooms → Check Availability → Book Room → Select Services → Payment → Confirmation |
| **Admin** | Login → Dashboard → Manage Bookings / Users / Rooms → Add Offline Bookings → Track Payments |

---

## 🏨 Room Types

The system supports **8 room categories** out of the box:

| Room Type | Description |
|---|---|
| Single Room | Ideal for solo travelers |
| Standard Room | Comfortable room with essential amenities |
| Deluxe Room | Upgraded furnishings and premium amenities |
| Twin Room | Two separate beds, perfect for friends or colleagues |
| Executive Room | Business-class room with workspace |
| Family Room | Spacious room designed for families |
| Suite | Luxurious room with separate living area |
| Presidential Suite | The most luxurious room with VIP services |

---

## 📄 License

This project is open source and available for educational purposes.

---

## 👤 Author

**Adhithyan K S**

- GitHub: [@adhithyan-ks](https://github.com/adhithyan-ks)

---

<p align="center">
  <sub>Built with ❤️ as a Minor Project — 4th Semester, Diploma in Computer Engineering</sub>
</p>