<?php
/* =========================================
   1. AUTHENTICATION & SETUP
   ========================================= */
require_once __DIR__ . '/auth/roles.php';
require_once __DIR__ . '/config/database.php';

// Enforce admin access control
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

/* =========================================
   2. POST REQUEST HANDLERS (CRUD)
   ========================================= */

// Action: Add new menu category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $categoryName = $_POST['category_name'];
    if ($categoryName !== '') {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$categoryName]);
    }
}

// Action: Add new menu dish item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $itemName   = $_POST['item_name'];
    $price      = $_POST['price'];
    $categoryId = $_POST['category_id'];

    if ($itemName !== '' && $price !== '') {
        $stmt = $pdo->prepare("INSERT INTO menu_items (item_name, price, category_id) VALUES (?, ?, ?)");
        $stmt->execute([$itemName, $price, $categoryId]);
    }
}

// Action: Update existing dish details (Name, Price)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $itemId   = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $price    = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE menu_items SET item_name = ?, price = ? WHERE item_id = ?");
    $stmt->execute([$itemName, $price, $itemId]);
    header("Location: admin.php");
    exit;
}

// Action: Mark order status as 'Completed'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $orderId = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
    $stmt->execute([$orderId]);
    header("Location: admin.php");
    exit;
}

/* =========================================
   3. GET REQUEST HANDLERS (DELETIONS)
   ========================================= */

// Delete single dish item
if (isset($_GET['delete_item'])) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE item_id = ?");
    $stmt->execute([$_GET['delete_item']]);
    header("Location: admin.php");
    exit;
}

// Delete menu category
if (isset($_GET['delete_category'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->execute([$_GET['delete_category']]);
    header("Location: admin.php");
    exit;
}

// Delete customer order record
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    header("Location: admin.php");
    exit;
}

/* =========================================
   4. FETCH DATA FOR VIEW
   ========================================= */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$menuItems  = $pdo->query("SELECT menu_items.*, categories.category_name FROM menu_items JOIN categories ON menu_items.category_id = categories.category_id")->fetchAll();

$ordersStmt = $pdo->query("SELECT orders.*, users.username, menu_items.item_name FROM orders JOIN users ON orders.user_id = users.id JOIN menu_items ON orders.item_id = menu_items.item_id ORDER BY orders.created_at DESC");
$orders = $ordersStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Cafe POS System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="admin-dashboard">
        <div class="admin-header">
            <div>
                <h1>Admin Control Panel</h1>
                <p class="subtitle">Cafe Menu & Category Management System</p>
            </div>
            <div class="admin-user-info">
                <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'Admin'); ?></strong></span>
                <a href="logout.php" class="btn-logout">Sign Out</a>
            </div>
        </div>

        <div class="admin-grid">
            <div class="admin-card">
                <h2>Category Management</h2>
                <form method="POST" action="admin.php" class="admin-form">
                    <input type="text" name="category_name" placeholder="New Category Name (e.g. Sandwich)" required>
                    <button type="submit" name="add_category" class="btn-orange-submit">Add Category</button>
                </form>

                <div class="category-tags-box">
                    <?php foreach ($categories as $cat) { ?>
                        <span class="category-tag">
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                            <a href="admin.php?delete_category=<?php echo $cat['category_id']; ?>" onclick="return confirm('Delete this category?')" class="tag-del">✕</a>
                        </span>
                    <?php } ?>
                </div>

                <h2 style="margin-top: 30px;">Add New Menu Item</h2>
                <form method="POST" action="admin.php" class="admin-form">
                    <label>Item Name</label>
                    <input type="text" name="item_name" placeholder="e.g. Italian Veg. Sandwich" required>

                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" placeholder="16.54" required>

                    <label>Category</label>
                    <select name="category_id" required>
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                        <?php } ?>
                    </select>

                    <button type="submit" name="add_item" class="btn-orange-submit" style="margin-top: 10px;">+ Add Dish to Menu</button>
                </form>
            </div>

        <div class="admin-card">
                <h2>Current Menu Items (<?php echo count($menuItems); ?>)</h2>
                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Price ($)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menuItems as $item) { ?>
                            <tr>
                                <form method="POST" action="admin.php">
                                    <td><strong>#<?php echo $item['item_id']; ?></strong></td>
                                    <td>
                                        <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required class="inline-input">
                                    </td>
                                    <td>
                                        <span class="category-badge"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required class="inline-input price-input">
                                    </td>
                                    <td class="action-cells">
                                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                        <button type="submit" name="update_item" class="btn-sm btn-save">Save</button>
                                        <a href="admin.php?delete_item=<?php echo $item['item_id']; ?>" class="btn-sm btn-del" onclick="return confirm('Delete this dish?')">Delete</a>
                                    </td>
                                </form>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-top: 25px;">
            <h2>Customer Orders Management (<?php echo count($orders); ?>)</h2>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order Ref</th>
                            <th>Customer</th>
                            <th>Table No</th>
                            <th>Dish Item</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord) { ?>
                        <tr>
                            <td><strong>#<?php echo $ord['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($ord['username']); ?></td>
                            <td>Table <?php echo htmlspecialchars($ord['table_number']); ?></td>
                            <td><strong><?php echo htmlspecialchars($ord['item_name']); ?></strong></td>
                            <td>
                                <span class="status-badge <?php echo $ord['status'] === 'Pending' ? 'status-pending' : 'status-completed'; ?>">
                                    <?php echo htmlspecialchars($ord['status']); ?>
                                </span>
                            </td>
                            <td class="action-cells">
                                <?php if ($ord['status'] === 'Pending') { ?>
                                    <form method="POST" action="admin.php" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                        <button type="submit" name="complete_order" class="btn-sm btn-save">Mark Complete</button>
                                    </form>
                                <?php } ?>
                                <a href="admin.php?delete_order=<?php echo $ord['id']; ?>" class="btn-sm btn-del" onclick="return confirm('Delete this order?')">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>