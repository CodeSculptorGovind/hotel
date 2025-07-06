
<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get all categories for dropdown
$categoryQuery = "SELECT * FROM takeaway_categories WHERE is_active = 1 ORDER BY display_order";
$categoryStmt = $db->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = trim($_POST['item_code']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $allergens = trim($_POST['allergens']);
    $preparation_time = intval($_POST['preparation_time']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;

    if (empty($item_code) || empty($name) || empty($price) || empty($category_id)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../uploads/menu/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $filename = 'menu_' . time() . '_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_url = 'uploads/menu/' . $filename;
                }
            } else {
                $error = 'Please upload a valid image file (JPG, PNG, GIF, WebP).';
            }
        }

        if (empty($error)) {
            try {
                $query = "INSERT INTO takeaway_menu_items (item_code, name, description, category_id, price, image_url, allergens, preparation_time, is_available, is_popular) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $result = $stmt->execute([
                    $item_code,
                    $name,
                    $description,
                    $category_id,
                    $price,
                    $image_url,
                    $allergens,
                    $preparation_time,
                    $is_available,
                    $is_popular
                ]);

                if ($result) {
                    $success = 'Menu item created successfully!';
                    // Clear form data
                    $item_code = $name = $description = $allergens = '';
                    $price = $preparation_time = 0;
                    $category_id = 0;
                    $is_available = $is_popular = 1;
                } else {
                    $error = 'Failed to create menu item.';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Item code already exists. Please use a unique item code.';
                } else {
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Menu Item - Takeaway Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../sidepanel.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidepanel.php'; ?>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Add New Menu Item</h2>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Menu Management
                    </a>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="item_code" class="form-label">Item Code *</label>
                                        <input type="text" class="form-control" id="item_code" name="item_code" 
                                               value="<?= htmlspecialchars($item_code ?? '') ?>" 
                                               placeholder="e.g., ST001" required>
                                        <small class="form-text text-muted">Unique identifier for invoice systems</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Item Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($name ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="3"><?= htmlspecialchars($description ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" 
                                                        <?= (isset($category_id) && $category_id == $category['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price (£) *</label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               step="0.01" min="0" value="<?= $price ?? '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="preparation_time" class="form-label">Prep Time (mins)</label>
                                        <input type="number" class="form-control" id="preparation_time" name="preparation_time" 
                                               min="0" value="<?= $preparation_time ?? 15 ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Menu Item Image</label>
                                <input type="file" class="form-control" id="image" name="image" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                <small class="form-text text-muted">Upload JPG, PNG, GIF, or WebP files (max 5MB)</small>
                            </div>

                            <div class="mb-3">
                                <label for="allergens" class="form-label">Allergens</label>
                                <input type="text" class="form-control" id="allergens" name="allergens" 
                                       value="<?= htmlspecialchars($allergens ?? '') ?>"
                                       placeholder="e.g., Nuts, Dairy, Gluten">
                                <small class="form-text text-muted">Comma-separated list of allergens</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_available" 
                                               name="is_available" <?= (!isset($is_available) || $is_available) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_available">
                                            Available for customers
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_popular" 
                                               name="is_popular" <?= (isset($is_popular) && $is_popular) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_popular">
                                            Mark as popular item
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Menu Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-generate item code based on category
        document.getElementById('category_id').addEventListener('change', function() {
            const itemCode = document.getElementById('item_code');
            if (!itemCode.value) {
                const categoryMap = {
                    '1': 'ST', // Starters
                    '2': 'BG', // Burgers  
                    '3': 'MC', // Main Courses
                    '4': 'CR', // Curry
                    '5': 'RC', // Rice
                    '6': 'BR', // Breads
                    '7': 'SD', // Sides
                    '8': 'DS', // Desserts
                    '9': 'BV'  // Beverages
                };
                const prefix = categoryMap[this.value] || 'IT';
                const timestamp = Date.now().toString().slice(-3);
                itemCode.value = prefix + timestamp;
            }
        });
    </script>
</body>
</html>
