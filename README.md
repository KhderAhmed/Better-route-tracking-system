## 👨‍💻 Developer & Academic Information

* **Developer:** Khder Jaber Ahmed 
* **University:** Tartous University 
* **Faculty:** Faculty of Communication and Information Technology Engineering 
* **Department:** Department of Information Engineering 
---

## 🛠️ Tech Stack

* **Backend:** Laravel 12 (PHP 8.2+)
* **Frontend & Interactivity:** Livewire v3, Alpine.js, Tailwind CSS v3
* **Maps & Routing:** Leaflet.js (OpenStreetMap Integration)
* **Charts & Analytics:** Chart.js v4 (Canvas Gradients & Analytics)
* **Database & Cache:** SQLite / MySQL, Redis (Queue & Caching)
* **Security & Auth:** RateLimiter, Bcrypt/Argon2id, Session Encryption

---

## 📂 Database Schema & Models

The system consists of the following core data entities:
1. **`users`**: Manages users and roles (Admin, Manager, Driver, Customer) with active status toggling and login auditing.
2. **`vehicles`**: Fleet management (Available, On Route, Maintenance).
3. **`depots`**: Central dispatch hubs and warehouses.
4. **`orders`**: Customer orders (Name, Phone, Address, Coordinates, Weight) with states: `pending`, `assigned`, `delivered`, `cancelled`.
5. **`routers`**: Calculated delivery paths and estimated distances between geographic nodes.

---

## 🛡️ Security Features

* **Brute-Force Protection (Rate Limiting):** Login attempts are restricted to a maximum of 5 attempts per minute per email and IP address.
* **Encrypted Sessions:** Session cookies are encrypted (`SESSION_ENCRYPT=true`) with `HttpOnly` and `Secure` flags enforced.
* **Role-Based Access Control (RBAC):** Middleware-secured routes preventing unauthorized access across administrative modules.

---

## 🖥️ The 6 Core Modules

1. **Analytics Dashboard (`MainDashboard`):** Live monitoring interface featuring four interactive charts (Bar, Doughnut, Polar Area) tracking fleet status, order pipelines, routes, and depots.
2. **Orders Manager (`OrdersManager`):** Advanced data table featuring instant search, status filtering, and quick direct order creation into the queue.
3. **Route Planner (`RoutePlanner`):** An interactive map (`Leaflet.js`) allowing operators to select depots, available vehicles, and pending orders to execute routing and distance calculations.
4. **Reports & Analytics (`ReportsAnalytics`):** Key Performance Indicators (KPIs) showing total distance traveled, delivered orders, and printable route logs.
5. **Users Manager (`UsersManager`):** Control panel to create new accounts, assign roles (Admin, Manager, Driver, Customer), and manage user status.
6. **System Notifications (`SystemNotifications`):** Real-time monitoring for faulty vehicles in maintenance and unassigned pending orders requiring immediate attention.
7. **System Settings (`SystemSettings`):** Configuration panel for general platform constants, default geographic center coordinates, and maximum vehicle weight limits.

---

## ⚙️ Installation & Local Setup

1. **Clone the repository and install dependencies:**
   ```bash
   git clone <repository-url>
   cd logistics-system
   composer install
   npm install && npm run build
   docker compose up -d --build
   docker compose up -d 