
<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get filter parameters
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query with filters
$where_conditions = [];
$params = [];

if ($category_filter > 0) {
    $where_conditions[] = "tm.category_id = ?";
    $params[] = $category_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(tm.name LIKE ? OR tm.description LIKE ? OR tm.item_code LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$query = "SELECT tm.*, tc.name as category_name 
          FROM takeaway_menu_items tm 
          LEFT JOIN takeaway_categories tc ON tm.category_id = tc.id 
          $where_clause
          ORDER BY tc.display_order, tm.display_order, tm.name";

$stmt = $db->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filter dropdown
$categoryQuery = "SELECT * FROM takeaway_categories WHERE is_active = 1 ORDER BY display_order";
$categoryStmt = $db->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items List - Takeaway Menu</title>
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
                    <h2>Menu Items (<?= count($items) ?>)</h2>
                    <div>
                        <a href="create-item.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Item
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label for="category" class="form-label">Filter by Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" 
                                                <?= $category_filter == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="search" class="form-label">Search Items</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?= htmlspecialchars($search) ?>" 
                                       placeholder="Search by name, description, or item code">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Items List -->
                <?php if (empty($items)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                        <h5>No menu items found</h5>
                        <p class="text-muted">Start by <a href="create-item.php">adding your first menu item</a></p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($items as $item): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img src="../../<?= $item['image_url'] ?>" class="card-img-top" 
                                             style="height: 200px; object-fit: cover;" 
                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                            <span class="badge bg-primary"><?= $item['item_code'] ?></span>
                                        </div>
                                        
                                        <p class="text-muted small mb-2"><?= htmlspecialchars($item['category_name']) ?></p>
                                        
                                        <?php if (!empty($item['description'])): ?>
                                            <p class="card-text small text-muted">
                                                <?= strlen($item['description']) > 100 ? 
                                                    htmlspecialchars(substr($item['description'], 0, 100)) . '...' : 
                                                    htmlspecialchars($item['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-success">£<?= number_format($item['price'], 2) ?></strong>
                                                <div>
                                                    <?php if ($item['is_popular']): ?>
                                                        <span class="badge bg-warning text-dark">Popular</span>
                                                    <?php endif; ?>
                                                    <span class="badge <?= $item['is_available'] ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($item['allergens'])): ?>
                                                <small class="text-danger d-block mb-2">
                                                    <i class="fas fa-exclamation-triangle"></i> 
                                                    Allergens: <?= htmlspecialchars($item['allergens']) ?>
                                                </small>
                                            <?php endif; ?>
                                            
                                            <div class="btn-group w-100" role="group">
                                                <a href="edit-item.php?id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="delete-item.php?id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this item?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
