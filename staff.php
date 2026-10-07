<?php
/* =========================================
   1. AUTHENTICATION & SETUP
   ========================================= */
require_once __DIR__ . '/auth/roles.php';
require_once __DIR__ . '/config/database.php';

// Verify kitchen staff or admin privileges
if (!isStaff()) {
    header('Location: login.php');
    exit;
}

/* =========================================
   2. ORDER FULFILLMENT HANDLER
   ========================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $orderId = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
    $stmt->execute([$orderId]);
    header("Location: staff.php");
    exit;
}

/* =========================================
   3. FETCH ACTIVE ORDERS
   ========================================= */
$stmt = $pdo->query("SELECT orders.*, users.username, menu_items.item_name FROM orders JOIN users ON orders.user_id = users.id JOIN menu_items ON orders.item_id = menu_items.item_id ORDER BY orders.created_at ASC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Display System - MentaiYa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="staff-dashboard">
        <div class="staff-header">
            <div>
                <h1>Kitchen Display System (KDS)</h1>
                <p class="subtitle">Real-time Order Processing Center</p>
            </div>
            <div class="staff-user-info">
                <span>Kitchen Staff: <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></span>
                <a href="logout.php" class="btn-logout">Sign Out</a>
            </div>
        </div>
        <div class="staff-card">
            <h2>Incoming Orders (<?php echo count($orders); ?>)</h2>
            <div class="table-wrapper">
                <table class="staff-table">
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
                            <td>
                                <span class="table-number-badge">Table <?php echo htmlspecialchars($ord['table_number']); ?></span>
                            </td>
                            <td>
                                <strong class="dish-title"><?php echo htmlspecialchars($ord['item_name']); ?></strong>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $ord['status'] === 'Pending' ? 'status-pending' : 'status-completed'; ?>">
                                    <?php echo htmlspecialchars($ord['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($ord['status'] === 'Pending') { ?>
                                    <form method="POST" action="staff.php" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                        <button type="submit" name="complete_order" class="btn-complete">Mark Complete</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="status-served-text">✓ Served</span>
                                <?php } ?>
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