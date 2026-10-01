# Project Proposal: PHP + MySQL Application

> **Course:** J620-002-4:2020 Front-End Software Development (Level 4)
> **Competency Unit:** J620-002-4:2020-C01
> **Instructions:** Replace every `[ ... ]` and delete the hint lines (starting with `>`) before submitting. Keep this file as `README.md` in the root of your project repository.

---

## 1. Student Details

| Field | Your Answer |
|---|---|
| Candidate Name | [ Nigel Ng Yi Shun ] |
| NRIC Number | [ 070420070317 ] |
| Date Submitted | [ ... ] |

---

## 2. Project Title

**[ MentaiYa - Smart Restaurant Ordering & Kitchen Management System ]**

### One-line summary
[ A web application for customers to browse menu and order food, kitchen staff to view orders, and admin to manage the menu. ]

---

## 3. Problem Statement & Purpose

> What problem does your application solve? Who is it for? Why is it useful?

[ Manual paper ordering in small restaurants causes miscommunication between servers and the kitchen. Simple Food provides a digitized ordering platform to simplify menu browsing, food ordering, and kitchen status tracking. ]

---

## 4. Tech Stack

> Required: HTML, CSS, PHP, MySQL.

| Layer | Technology |
|---|---|
| Markup | HTML5 |
| Styling | CSS3 |
| Server-side | PHP |
| Database | MySQL |

---

## 5. Types of Users (Roles)

> Minimum **3 roles**. Each role must have different levels of access.

| Role | Description |
|---|---|
| Admin | Manages menu items, categories, and user accounts. |
| Staff | Views pending food orders and updates order preparation status. |
| Customer | Browses the menu, places food orders, and tracks order status. |

### Role-Based Access Matrix

> Mark what each role can do. Add or remove rows to match your features.

| Feature / Page | Role 1 | Role 2 | Role 3 | Guest (not logged in) |
|---|:---:|:---:|:---:|:---:|
| Register / Login | ✅ | ✅ | ✅ | ✅ |
| View Menu | ✅ | ✅ | ✅ | ✅ |
| Place Food Order | ❌ | ❌ | ✅ | ❌ |
| Manage Kitchen Orders | ❌ | ✅ | ❌ | ❌ |
| Manage Menu & Categories | ✅ | ❌ | ❌ | ❌ |

---

## 6. Features

### 6.1 Core Features (must have)

- [x] User registration and login
- [x] Role-based access control (each role sees/does different things)
- [x] Data management (Create, Read, Update, Delete)
- [x] Food ordering system for customers
- [x] Kitchen order status management for staff

### 6.2 Extra Features (nice to have)

- [x] Table number selection during checkout
- [ ] Real-time total price calculation in cart

### 6.3 Feature Descriptions

> Briefly explain each core feature: what it does and which role uses it.

| Feature | Description | Role(s) |
|---|---|---|
| User Authentication | Allows users to log in securely with PHP session-based access control. | All Roles |
| Menu Management | Admin can Create, Read, Update, and Delete food items and categories. | Admin |
| Order Placement | Customers select items and submit food orders. | Customer |
| Order Processing | Staff view orders and change status from 'Pending' to 'Completed'. | Staff |


---

## 7. Data Management System

> Which data can users create, view, edit and delete? Who is allowed to do what?

| Data / Entity | Create | Read | Update | Delete |
|---|---|---|---|---|
| Users | Admin, Guest | Admin | Admin | Admin |
| Categories | Admin | All Roles | Admin | Admin |
| Menu Items | Admin | All Roles | Admin | Admin |
| Orders | Customer | Admin, Staff, Customer | Staff | Admin |

---

## 8. Database Design

> Minimum **4 tables** with at least **3 linkages** (foreign keys) between them.

### Entity Relationship Diagram (ERD)

> Create your own ERD for your database and place it here. You can draw it in draw.io or dbdiagram.io and insert the exported image (e.g. `![ERD](docs/erd.png)`), or write it in Mermaid.

[```mermaid
erDiagram
    categories ||--o{ menu_items : "has"
    users ||--o{ orders : "places"
    menu_items ||--o{ orders : "ordered"

    users {
        int user_id PK
        string username
        string password
        enum role "Admin, Staff, Customer"
    }

    categories {
        int category_id PK
        string category_name
    }

    menu_items {
        int item_id PK
        int category_id FK
        string item_name
        decimal price
    }

    orders {
        int order_id PK
        int user_id FK
        int item_id FK
        string table_number
        enum status "Pending, Completed"
    }]

---

## 9. Use Case Diagram

> Show the actors (roles) and what each can do in the system. Use a Mermaid flowchart below, or export an image from draw.io / Lucidchart to `docs/usecase.png`.

```mermaid
flowchart LR
    A([Admin]) --> UC1[Login]
    A --> UC2[Manage Menu & Categories]
    B([Staff]) --> UC1
    B --> UC3[Manage Kitchen Orders]
    C([Customer]) --> UC1
    C --> UC4[Browse Menu & Place Order]
```

---

## 10. Presentation Checklist

- [ ] Can explain the purpose of the application
- [ ] Can justify design choices (why this database structure, why these roles)
- [ ] Can demo every role
- [ ] Can answer questions about my own code
- [ ] Submitted on time
