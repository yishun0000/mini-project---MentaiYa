<?php
/* =========================================
   1. AUTHENTICATION & SETUP
   ========================================= */
require_once __DIR__ . '/auth/roles.php';
require_once __DIR__ . '/config/database.php';

// Validate customer authorization
if (!isCustomer()) {
    header('Location: /login.php');
    exit;
}

$msg = '';

/* =========================================
   2. ORDER SUBMISSION HANDLER
   ========================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $itemId      = $_POST['item_id'];
    $tableNumber = $_POST['table_number'];
    $userId      = $_SESSION['user']['id'];

if ($itemId && $tableNumber) {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, item_id, table_number, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->execute([$userId, $itemId, $tableNumber]);
        $msg = "注文が完了しました！ Order placed successfully!";
    }
}

/* =========================================
   3. DATA QUERYING & CATEGORY FILTERING
   ========================================= */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$selectedCategory = $_GET['category_id'] ?? null;

// Filter dish selection by active category parameter
if ($selectedCategory) {
    $stmt = $pdo->prepare("SELECT menu_items.*, categories.category_name FROM menu_items JOIN categories ON menu_items.category_id = categories.category_id WHERE menu_items.category_id = ?");
    $stmt->execute([$selectedCategory]);
    $menuItems = $stmt->fetchAll();
} else {
    $menuItems = $pdo->query("SELECT menu_items.*, categories.category_name FROM menu_items JOIN categories ON menu_items.category_id = categories.category_id")->fetchAll();
}

// Fetch order history for current logged-in customer
$userOrders = $pdo->prepare("SELECT orders.*, menu_items.item_name, menu_items.price FROM orders JOIN menu_items ON orders.item_id = menu_items.item_id WHERE orders.user_id = ? ORDER BY orders.created_at DESC");
$userOrders->execute([$_SESSION['user']['id']]);
$myOrders = $userOrders->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Japanese Dining - MentaiYa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="pos-container">
        <div class="sidebar">
            <div class="brand-logo">🎌 MentaiYa</div>

            <div class="nav-section">
                <div class="nav-section-title">Japanese Menu / メニュー</div>

                <a href="customer.php" class="nav-item <?php echo !$selectedCategory ? 'active' : ''; ?>">All Dishes</a>

                <?php foreach ($categories as $cat) { ?>
                    <a href="customer.php?category_id=<?php echo $cat['category_id']; ?>" 
                       class="nav-item <?php echo $selectedCategory == $cat['category_id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="main-content">
            <div class="header-bar">
                <div>
                    <h2 style="font-size: 22px; font-weight: 700;">Japanese Cuisine & Izakaya</h2>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Authentic flavors prepared fresh daily</p>
                </div>
                <div class="user-profile">
                    <span><strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></span>
                    <a href="logout.php" style="color: #ef4444; font-size: 13px; font-weight: 600;">Logout</a>
                </div>
            </div>

            <?php if ($msg !== '') { ?>
            <div style="background: #DCFCE7; color: #15803D; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-weight: 600;">
                <?php echo $msg; ?>
            </div>
            <?php } ?>

            <div class="food-grid">
                <?php if (count($menuItems) > 0) { ?>
                    <?php foreach ($menuItems as $item) { ?>
                    <div class="food-card">
                        <div class="food-img-wrapper">
                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? ''); ?>" alt="">
                        </div>
                        <div class="food-title"><?php echo htmlspecialchars($item['item_name']); ?></div>
                        <div class="food-category"><?php echo htmlspecialchars($item['category_name']); ?></div>
                        <div class="food-price">$<?php echo number_format($item['price'], 2); ?></div>

                        <form method="POST" action="customer.php<?php echo $selectedCategory ? '?category_id=' . $selectedCategory : ''; ?>" class="order-action-form">
                            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                            <select name="table_number" class="table-input" required>
                                <option value="" disabled selected>Table No.</option>
                                <?php for ($i = 1; $i <= 30; $i++) { ?>
                                    <option value="<?php echo $i; ?>">Table <?php echo $i; ?></option>
                                <?php } ?>
                            </select>

                            <button type="submit" name="place_order" class="btn-orange-submit">Order</button>
                        </form>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <p style="color: var(--text-muted); grid-column: 1/-1;">No dishes found in this category.</p>
                <?php } ?>
            </div>

            <h3 style="font-size: 18px; margin-bottom: 15px;">Order History</h3>
            <div class="order-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item Name</th>
                            <th>Table No</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myOrders as $ord) { ?>
                        <tr>
                            <td>#<?php echo $ord['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($ord['item_name']); ?></strong></td>
                            <td>Table <?php echo htmlspecialchars($ord['table_number']); ?></td>
                            <td>$<?php echo number_format($ord['price'], 2); ?></td>
                            <td>
                                <span class="status-badge <?php echo $ord['status'] === 'Pending' ? 'status-pending' : 'status-completed'; ?>">
                                    <?php echo htmlspecialchars($ord['status']); ?>
                                </span>
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