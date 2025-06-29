
<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get all categories with item counts
$categoryQuery = "SELECT tc.*, COUNT(tm.id) as item_count 
                  FROM takeaway_categories tc 
                  LEFT JOIN takeaway_menu_items tm ON tc.id = tm.category_id 
                  GROUP BY tc.id 
                  ORDER BY tc.display_order";
$categoryStmt = $db->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent menu items
$itemQuery = "SELECT tm.*, tc.name as category_name 
              FROM takeaway_menu_items tm 
              LEFT JOIN takeaway_categories tc ON tm.category_id = tc.id 
              ORDER BY tm.created_at DESC 
              LIMIT 10";
$itemStmt = $db->prepare($itemQuery);
$itemStmt->execute();
$recentItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takeaway Menu Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white p-3">
                <h4>Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../index.php">
                            <i class="fas fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../table-menu/index.php">
                            <i class="fas fa-utensils"></i> Table Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="index.php">
                            <i class="fas fa-shopping-bag"></i> Takeaway Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Takeaway Menu Management</h2>
                    <div>
                        <a href="create-category.php" class="btn btn-success me-2">
                            <i class="fas fa-plus"></i> Add Category
                        </a>
                        <a href="create-item.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Menu Item
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Categories</h5>
                                <h3><?= count($categories) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Items</h5>
                                <h3><?= array_sum(array_column($categories, 'item_count')) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Menu Categories</h5>
                                <a href="list-categories.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <?php if (empty($categories)): ?>
                                    <p class="text-muted">No categories found. <a href="create-category.php">Create one</a></p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($categories as $category): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($category['name']) ?></strong>
                                                    <small class="text-muted d-block"><?= $category['item_count'] ?> items</small>
                                                </div>
                                                <div>
                                                    <a href="edit-category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="list-items.php?category=<?= $category['id'] ?>" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Items Section -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Recent Menu Items</h5>
                                <a href="list-items.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentItems)): ?>
                                    <p class="text-muted">No items found. <a href="create-item.php">Add one</a></p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($recentItems as $item): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?= htmlspecialchars($item['category_name']) ?> - £<?= number_format($item['price'], 2) ?>
                                                    </small>
                                                </div>
                                                <div>
                                                    <a href="edit-item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <span class="badge <?= $item['is_available'] ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
