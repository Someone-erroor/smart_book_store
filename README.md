# DAASTAAN. — Online Bookstore & Digital Literary Mehfil

> A modern e-commerce bookstore and Hindi/Urdu literary archive built with **PHP 8.5** and **MongoDB**.
> *"Books you can buy. Words you can feel."*

---

## 🏛️ Core Bookstore Features

* **User Authentication**: Secure registration, login, and session-based logout with `password_hash` (`PASSWORD_BCRYPT`).
* **Role-Based Access Control**:
  * **Customer Role**: Browse books, manage cart, place orders, view order history and printable invoices.
  * **Administrator Role**: Protected admin console (`/admin`), full CRUD operations on book inventory, low-stock alerts, sales analytics, and order status fulfillment.
* **Dynamic Book Catalog**: Real-time MongoDB-driven book inventory across Technology, Sci-Fi, Classics, Thrillers, Philosophy, and Hindi/Urdu Literature.
* **Search & Filtering**: Search across book titles, authors, and genres, with instant category filter pill tabs.
* **Shopping Cart**: Session-backed shopping bag with quantity increment/decrement, inventory stock validation, and subtotal calculation with free delivery thresholds.
* **Order Management**: End-to-end checkout with address capture, stock deduction in MongoDB, invoice generation, customer order history, and admin order lifecycle management (`confirmed` → `processing` → `shipped` → `delivered`).
* **CRUD Operations**: Comprehensive Create, Read, Update, and Delete capabilities for book inventory in the administrative control panel.

---

## 📜 Digital Literary Mehfil (New Modern Extension)

* **Alfaaz (Poetry & Shayari Archive)**: Dedicated feed of Urdu & Hindi verses, Ghazals, Nazms, and Dohas in Roman script with English commentary (Tafseer) and mood tags.
* **Ustad-e-Sukhan (Poets & Authors Directory)**: Dedicated profiles for 14 iconic literary figures (Jaun Elia, Mirza Ghalib, Harivansh Rai Bachchan, Faiz Ahmed Faiz, Dushyant Kumar, Ramdhari Singh Dinkar, Parveen Shakir, Vinod Kumar Shukla, etc.).
* **Ehsaas (Emotional Taxonomy Collections)**: 8 mood-based curated suites (*Tanhai*, *Dard*, *Mohabbat*, *Falsafa*, *Zindagi*, *Ishq*, *Safar*, *Yaad*) uniting poetry, philosophy, and purchasable books.
* **Content-to-Commerce Integration**:
  $$\text{Poem Reading Room} \longrightarrow \text{Author Profile} \longrightarrow \text{Published Physical Volumes} \longrightarrow \text{Add to Cart}$$
* **Universal Readability**: High-legibility Roman script transcription paired with English literary reflections so readers can connect without requiring specialized fonts.

---

## 🛠️ Technology Stack

* **Backend**: PHP 8.5+ with MongoDB Extension
* **Database**: MongoDB (`mongodb/mongodb` ^2.4)
* **Frontend**: HTML5, Vanilla JavaScript (3D Card Tilt, Lerped Glow Inertia), Tailwind CSS, Google Fonts (*Space Grotesk*, *DM Mono*)
* **Design Aesthetic**: Cinematic dark void palette (`#050505`), blood crimson (`#ff1744`), subtle muted gold (`#c5a059`), and ivory typography (`#f8f6f0`).

---

## 📂 Project Structure

```text
online-bookstore/
├── admin/                      # Administrative Control Panel (Role-Protected)
│   ├── index.php               # Store metrics, revenue stats & stock audit
│   ├── books.php               # Inventory table with CRUD action buttons
│   ├── book-add.php            # Create new book in MongoDB
│   ├── book-edit.php           # Update existing book record
│   ├── book-delete.php         # Delete book from MongoDB
│   └── orders.php              # Manage & fulfill customer orders
├── auth/                       # User Authentication
│   ├── login.php               # User login with pre-configured demo buttons
│   ├── register.php            # Customer account creation
│   └── logout.php              # Session termination
├── cart/                       # Shopping Cart
│   ├── cart.php                # Visual shopping bag & order summary
│   ├── add.php                 # Add book with stock verification
│   ├── update.php              # Modify item quantities (+ / −)
│   ├── remove.php              # Remove specific book
│   └── clear.php               # Empty cart
├── collections/                # Emotional Mood Collections (Mehfil)
│   ├── index.php               # 8 Curated mood suites (Tanhai, Dard, etc.)
│   └── view.php                # Mood room aggregating verses + purchasable books
├── config/                     # Configuration & Seeding
│   ├── database.php            # MongoDB connection setup
│   ├── seed.php                # Base bookstore seeder (Users, Books, Indexes)
│   └── seed_literary.php       # Literary mehfil seeder (Poets, Poetry, Collections)
├── includes/                   # Reusable Layout Components
│   ├── auth.php                # Auth & session helper middleware
│   ├── header.php              # Shared HTML <head>, styles & flash alerts
│   ├── navbar.php              # Dynamic responsive navbar (with literary links)
│   └── footer.php              # Shared footer with scripts & cursor glow
├── orders/                     # Customer Orders
│   ├── checkout.php            # Shipping address & order confirmation
│   ├── confirmation.php        # Printable invoice & receipt
│   └── index.php               # Customer order history
├── poetry/                     # Poetry & Shayari (Alfaaz)
│   ├── index.php               # Poetry grid with mood, language & genre filters
│   └── view.php                # Reading room + Tafseer + related bookstore titles
├── poets/                      # Poets & Authors Directory
│   ├── index.php               # Directory of Urdu & Hindi poets
│   └── profile.php             # Biography, notable works, verses & books in store
├── index.php                   # Homepage with Curated Shelf, Alfaaz, & Mood Suites
├── books.php                   # Searchable book catalog
├── book-details.php            # Product view with specs & author profile link
├── composer.json               # PHP MongoDB dependency specification
└── README.md                   # Documentation
```

---

## 🔑 Pre-Configured Test Accounts

| Role | Email | Password | Access Privileges |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@smartbookstore.com` | `Admin@123` | Full access to `/admin` dashboard, inventory CRUD, order status |
| **Customer** | `customer@smartbookstore.com` | `Customer@123` | Catalog browsing, cart checkout, personal order receipts |

---

## 💻 Setup & Run Instructions

1. **Start MongoDB**:
   Ensure MongoDB service is running locally on port `27017`.
   ```bash
   mongosh --eval "db.version()"
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Seed Database & Literary Mehfil**:
   ```bash
   php config/seed.php
   php config/seed_literary.php
   ```

4. **Launch Local Server**:
   ```bash
   php -S localhost:8000
   ```
   Open your browser and navigate to `http://localhost:8000`.
