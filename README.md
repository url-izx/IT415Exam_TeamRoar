# CampusGo — Touchscreen Self-Service Point-of-Sale (POS) Kiosk

![CampusGo Logo](assets/img/campusgo-logo.png)

> **Course**: IT415 — Systems Integration and Architecture / Application Development  
> **Project**: Practical Examination Project  
> **Team**: Team Roar  
> **Repository**: [https://github.com/url-izx/IT415Exam_TeamRoar.git](https://github.com/url-izx/IT415Exam_TeamRoar.git)  
> **Current Version**: v2.0 (Luminous Purple Glassmorphic Edition)

---

## 📖 1. Project Description

**CampusGo** is an intuitive, touchscreen-optimized self-service Point-of-Sale (POS) kiosk system developed for university and campus store environments. Built with modern web technologies and a self-contained local backend, CampusGo streamlines the ordering experience by eliminating long lines and manual cashier bottlenecks.

The kiosk guides campus customers through a seamless, 4-stage ordering journey:
1. **Order & Catalog**: Browse categorized products with pastel visual cards and live cart updates.
2. **Review**: Itemized order summary with unit prices, quantities, and subtotals with the ability to go back without losing items.
3. **Payment**: Select from three simulated payment methods (**Cash** with a touchscreen numeric keypad, **QR Payment** with dynamic reference codes, and **Credit/Debit Card** with terminal simulation).
4. **Receipt**: View and print a thermal digital receipt, followed by a complete state reset for the next customer.

---

## 🌟 2. Key Features

* **Touchscreen Kiosk Ergonomics**:
  * Large, thumb-friendly touch cards and button hit targets (`min-height: 60px`–`76px`).
  * On-screen 3×4 numeric keypad eliminating the need for a physical keyboard.
  * Tactile sound synthesis feedback (Web Audio API) on button presses.
* **Product Catalog & Category Filtering**:
  * 6 campus store products (Coffee, Sandwich, Soft Drink, Cookies, Bottled Water, Chocolate).
  * Category filter pills: *All*, *Drinks*, *Food*, *Snacks*.
  * Selected count badges (`1`, `2`, ...) on product cards.
* **Interactive Cart Sidebar**:
  * Real-time `+` / `−` quantity steppers.
  * Dedicated trash button (`🗑`) for item removal.
  * Dynamic subtotal and grand total calculations.
  * Empty cart guard preventing accidental progression.
* **Order Review Screen**:
  * Clean review table displaying Product, Quantity, Unit Price, and Subtotal.
  * Safe `← Back` navigation preserving all cart items.
* **Payment Processing**:
  * **Cash**: Keypad entry, quick bill presets (`Exact`, `₱200`, `₱500`, `₱1,000`), live change calculation formula, and strict insufficient cash validation.
  * **QR Payment**: Simulated e-wallet scanning with dynamic reference generation (`Ref: QR-TXN-2026-XXXXX`).
  * **Credit / Debit Card**: Contactless NFC terminal illustration with animated progress bar simulation.
* **Thermal Digital Receipt & Printing**:
  * Monospace thermal paper slip layout with dashed dividers and transaction timestamp.
  * Standard 80mm thermal receipt `@media print` print stylesheet.
* **New Transaction Reset**:
  * `+ New Transaction` completely clears cart, payment inputs, and transaction session data, returning to Step 1 with a toast notification.
* **Modern Aesthetic**:
  * QuestLearn-inspired deep obsidian violet (`#180C2E`) and luminous purple (`#7C3AED`) color scheme.
  * Official CampusGo kiosk logo and responsive header with overflow scroll protection.

---

## 💻 3. Technology Stack

* **Frontend**:
  * **HTML5**: Semantic markup with touch-friendly layout attributes.
  * **CSS3 (Vanilla)**: Custom kiosk design tokens, thermal paper styling, print media queries, glassmorphism.
  * **Bootstrap 5.3.3**: Responsive container layout and flexbox utility structure.
  * **JavaScript (ES6+)**: Custom `Kiosk` state engine, keypad handler, Web Audio API sound generator.
  * **SweetAlert2 (v11)**: Kiosk modal dialogs for validation and empty cart warnings.
* **Backend**:
  * **PHP 8.2**: Clean RESTful JSON API endpoints with PDO database abstraction.
* **Database**:
  * **MySQL / MariaDB**: Relational schema with transactional data integrity (`InnoDB`).
* **Environment**:
  * **XAMPP**: Local Apache 2.4 server and MySQL service.

---

## 🗄️ 4. Storage & Database Choices

The system uses a relational MySQL database named `campusgo_db` with `InnoDB` storage engine to guarantee transactional consistency:

1. **`products`**:
   * Stores catalog items, category names, unit prices, SVG icon indicators, and background colors.
2. **`transactions`**:
   * Stores completed transaction metadata (`txn_number`, `subtotal`, `total_amount`, `payment_method`, `amount_paid`, `change_amount`, `reference_no`, `status`, `created_at`).
   * Enforces a `UNIQUE` constraint on `txn_number` (format `TXN-YYYY-XXXXX`).
3. **`transaction_items`**:
   * Stores individual line items for each transaction (`transaction_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `subtotal`) with foreign key cascading deletion.

---

## 🚀 5. Installation & Setup Instructions

### Prerequisites
* Windows OS with **XAMPP** installed (Apache & MySQL modules).
* Git installed.
* Modern web browser (Google Chrome, Microsoft Edge, or Firefox).

### Step-by-Step Installation

1. **Clone or Copy Repository**:
   Clone the repository directly into your XAMPP `htdocs` directory:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/url-izx/IT415Exam_TeamRoar.git campusgo
   cd campusgo
   ```

2. **Start XAMPP Services**:
   * Open the **XAMPP Control Panel**.
   * Start **Apache**.
   * Start **MySQL**.

3. **Import Database Schema & Seed Data**:
   Import the schema into MySQL using the command line:
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root < database/schema.sql
   ```
   *Alternatively*, open **phpMyAdmin** (`http://localhost/phpmyadmin/`), create database `campusgo_db`, and import `database/schema.sql`.

4. **Verify Database Connection**:
   The default connection settings are defined in `config/db.php`:
   * Host: `localhost`
   * Database: `campusgo_db`
   * Username: `root`
   * Password: `` (empty string)

---

## 🖥️ 6. Run Instructions

1. Open your web browser.
2. Navigate to:
   ```
   http://localhost/campusgo/
   ```
3. The kiosk interface will load immediately in **Step 1 (Order)** mode.

---

## 📁 7. Project Structure

```
campusgo/
├── api/
│   ├── get_products.php          # REST endpoint: Returns active products and categories (JSON)
│   ├── get_transaction.php       # REST endpoint: Fetches receipt by ID or TXN number
│   └── process_payment.php       # REST endpoint: Validates payment and saves transactions to DB
├── assets/
│   ├── css/
│   │   └── style.css             # Kiosk master stylesheet (Purple theme tokens & print styles)
│   ├── img/
│   │   ├── campusgo-logo.png     # Official CampusGo circular kiosk logo
│   │   └── 836219238_...png      # Original asset reference
│   └── js/
│       └── kiosk.js              # State engine: cart, stepper, keypad, payment, sound
├── config/
│   └── db.php                    # PDO MySQL database connection
├── database/
│   └── schema.sql                # MySQL schema and product catalog seed data
├── index.php                     # Master Single-Page Kiosk interface (All 4 stages)
├── AI_DOCUMENTATION.md           # Formal documentation of AI prompts, evaluations, and modifications
└── README.md                     # Comprehensive project documentation
```

---

## 👥 8. Group Members & Individual Contributions (Team Roar)

### **Member 1: Earl Masana (`url-izx`)**
* **Assigned Role**: Project Lead & Backend / Database Architecture
* **Branch**: `earl`
* **Contributions**:
  * Designed the relational database schema (`products`, `transactions`, `transaction_items`).
  * Implemented PDO transaction handling in `api/process_payment.php` with server-side validation.
  * Formatted unique transaction sequence generation (`TXN-YYYY-XXXXX`).
  * Established GitHub repository and workflow management.

### **Member 2: Honey Jean Ambaic (`hanixsyyy`)**
* **Assigned Role**: Frontend UI/UX & Kiosk State Engine
* **Branch**: `honey`
* **Contributions**:
  * Implemented the touchscreen numeric keypad and quick cash bill presets.
  * Developed live change calculation math and insufficient payment error handling.
  * Programmed the cart state manager in `assets/js/kiosk.js` (`addToCart`, `stepItem`, `removeItem`).
  * Integrated SweetAlert2 dialogs and audio synthesis touch feedback.

### **Member 3: Althea Clariz Compoc (`teiyahh`)**
* **Assigned Role**: Design Systems & Thermal Receipt Engine
* **Branch**: `althea`
* **Contributions**:
  * Engineered the QuestLearn-inspired deep obsidian violet and luminous purple design tokens.
  * Integrated the official CampusGo logo and responsive overflow-protected navigation bar.
  * Built the thermal digital receipt layout and 80mm POS print media stylesheets.
  * Co-authored user documentation, AI collaboration logs, and quality assurance audit checklists.

---

## 🔄 9. Git & GitHub Workflow

Team Roar adhered to standard feature branch and integration workflows:

1. **Integration Branch**: `main` serves as the verified production integration branch.
2. **Dedicated Member Branches**:
   * `earl` (Lead: Earl Masana / `@url-izx`)
   * `honey` (Lead: Honey Jean Ambaic / `@hanixsyyy`)
   * `althea` (Lead: Althea Clariz Compoc / `@teiyahh`)
3. **Feature Branches**:
   * `feature/setup-and-database`: MySQL schema, seed data, and PDO connection.
   * `feature/kiosk-interface`: HTML structure, Bootstrap grid, and responsive header.
   * `feature/core-functionality`: Stepper controls, category filtering, and subtotal math.
   * `feature/payment-validation`: Keypad, change computation, and shortage alerts.
   * `feature/purple-redesign`: Migration to QuestLearn purple theme tokens and logo.
   * `feature/documentation`: `README.md` and `AI_DOCUMENTATION.md`.
4. **Pull Requests & Code Reviews**: Member and feature branches submitted via PRs, reviewed by collaborators, and merged into `main`.
