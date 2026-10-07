# Mini Project Journal

**Student Name: Nigel Ng Yi Shun**
**Project Title: MentaiYa - Online Ordering & POS System**
**Start Date: 2026-09-28**
**End Date: 2026-10-11**

---

## Project Overview

> Briefly describe your project idea, what problem it solves, and what you aim to build.

A web-based ordering and POS system for a Japanese restaurant called MentaiYa. It handles customer online orders, kitchen order tracking, menu item updates, and multi-role user access (Admin, Staff, Customer).

---

## Tech Stack

> List the technologies, frameworks, and tools you plan to use.

- **Frontend: HTML, CSS**
- **Backend: PHP**
- **Database: MySQL**
- **Other: VS Code, XAMPP**

---

## Day 1 — Date: 2026-09-28

### What I planned to do today
Design database schema and setup project environment.

### What I actually did
Created database tables for users, categories, menu items, and orders in MySQL.

### Blockers / Challenges
Configuring foreign key constraints for menu items and orders.

### What I learned
How to use PDO with exception handling in PHP.

---

## Day 2 — Date: 2026-09-29

### What I planned to do today
Insert seed data into database.

### What I actually did
Added default users (Admin/Staff) and initial menu categories with items.

### Blockers / Challenges
Encrypting default passwords properly using `password_hash`.

### What I learned
Using `CHECK` constraints to restrict roles and order status in MySQL.

---

## Day 3 — Date: 2026-09-30

### What I planned to do today
Build database connection file.

### What I actually did
Wrote `database.php` using PDO with host, dbname, and fetch mode settings.

### Blockers / Challenges
Handling connection failure errors gracefully.

### What I learned
Setting default fetch mode to `FETCH_ASSOC` in PDO.

---

## Day 4 — Date: 2026-10-01

### What I planned to do today
Implement session authentication functions.

### What I actually did
Created `roles.php` with `isAdmin()`, `isStaff()`, `isCustomer()`, and `isUser()` helpers.

### Blockers / Challenges
Validating nested `$_SESSION` array variables cleanly.

### What I learned
Managing role-based access control with PHP sessions.

---

## Day 5 — Date: 2026-10-02

### What I planned to do today
Build user registration page.

### What I actually did
Created `register.php` with form validation and password hashing.

### Blockers / Challenges
Preventing duplicate usernames during registration.

### What I learned
Checking existing usernames before executing `INSERT` query.

---

## Day 6 — Date: 2026-10-03

### What I planned to do today
Build login and logout pages.

### What I actually did
Implemented `login.php` using `password_verify()` and built `logout.php` to destroy sessions.

### Blockers / Challenges
Redirecting users correctly based on their role after login.

### What I learned
Using `session_destroy()` and redirecting with `header()`.

---

### What I planned to do today
Create main CSS stylesheet.

### What I actually did
Wrote styles for card layouts, buttons, tables, and responsive forms.

### Blockers / Challenges
Styling form elements to look consistent across browsers.

### What I learned
Using CSS custom variables for theme colors.

---

## Day 8 — Date: 2026-10-05

### What I planned to do today
Build customer ordering page UI.

### What I actually did
Designed `customer.php` sidebar, category links, and dish cards.

### Blockers / Challenges
Handling layout issues in the food grid.

### What I learned
Using CSS Grid for dynamic menu item displays.

---

## Day 9 — Date: 2026-10-06

### What I planned to do today
Implement customer ordering functionality.

### What I actually did
Added category filtering and table number selection for submitting orders.

### Blockers / Challenges
Passing selected category via GET request while posting order forms.

### What I learned
Combining GET parameter filters with POST form submissions.

---

## Day 10 — Date: 2026-10-07

### What I planned to do today
Add order history view for customers.

### What I actually did
Fetched logged-in user orders from database and rendered them in a table.

### Blockers / Challenges
Joining `orders` and `menu_items` tables to display dish names.

### What I learned
Writing SQL `JOIN` queries to combine data across tables.

---

## Day 11 — Date: 2026-10-08

### What I planned to do today
Build kitchen display system for staff.

### What I actually did
Created `staff.php` to list incoming orders sorted by creation time.

### Blockers / Challenges
Filtering staff access using `isStaff()` middleware.

### What I learned
Updating order status from `Pending` to `Completed` using SQL updates.

---

## Day 12 — Date: 2026-10-09

### What I planned to do today
Build admin dashboard categories and dish management.

### What I actually did
Added form controls in `admin.php` to insert categories and new menu items.

### Blockers / Challenges
Deleting categories without breaking referenced menu items.

### What I learned
Using `ON DELETE CASCADE` in database table setup.

---

## Day 13 — Date: 2026-10-10

### What I planned to do today
Complete admin inline editing and order controls.

### What I actually did
Added inline editing for menu prices and delete functions for dishes/orders.

### Blockers / Challenges
Handling multiple forms and action types on a single PHP page.

### What I learned
Checking `isset($_POST['action_name'])` to route form processing.

---

### What I planned to do today
Test end-to-end user workflows and fix bugs.

### What I actually did
Tested full flow from customer ordering to kitchen update and admin management.

### Blockers / Challenges
Ensuring session checks protected all restricted pages properly.

### What I learned
Systematic end-to-end testing of web applications.

---

## Final Reflection

### What went well?
All customer, staff, and admin modules were built and connected smoothly with PDO.

### What would I do differently?
Add AJAX requests for real-time order status updates without page reloads.

### Key takeaways from this project
Gained practical experience with PHP sessions, MySQL PDO operations, and role-based access control.


