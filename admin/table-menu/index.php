
<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get all categories for dropdown
$categoryQuery = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order";
$categoryStmt = $db->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Menu Management - Admin</title>
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
                        <a class="nav-link text-white active" href="index.php">
                            <i class="fas fa-utensils"></i> Table Menu
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
                    <h2>Table Menu Management</h2>
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Item
                    </a>
                </div>

                <!-- Category Filter -->
                <div class="mb-3">
                    <label for="categoryFilter" class="form-label">Filter by Category:</label>
                    <select id="categoryFilter" class="form-select" style="width: 200px; display: inline-block;">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['display_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Menu Items List -->
                <div id="menuItemsList">
                    <?php include 'list.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const categoryId = this.value;
            const url = categoryId ? `list.php?category_id=${categoryId}` : 'list.php';
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('menuItemsList').innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        });

        // Delete confirmation
        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?`)) {
                window.location.href = `delete.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
