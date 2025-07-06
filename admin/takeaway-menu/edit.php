<?php
session_start();
include '../auth_check.php';
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch the item to be edited
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM takeaway_menu_items WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if item exists
if (!$item) {
    echo "Item not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $item_code = $_POST['item_code'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $updateQuery = "UPDATE takeaway_menu_items SET item_code=?, name=?, description=?, category_id=?, price=?, image_url=? WHERE id=?";
    $updateStmt = $db->prepare($updateQuery);
    
    if ($updateStmt->execute([$item_code, $name, $description, $category_id, $price, $image_url, $item_id])) {
        echo "<h3>Item updated successfully!</h3>";
    } else {
        echo "<h3>Error updating item!</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../sidepanel.css">
</head>
<body>
      
    <div class="container-fluid">
         <div class="row">
<?php include '../sidepanel.php'; ?>
          <div class="col-md-10 p-4">
         <h2>Edit Menu Item</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $item_id); ?>" method="post">
            <div class="mb-3">
                <label for="item_code" class="form-label">Item Code</label>
                <input type="text" class="form-control" id="item_code" name="item_code" required value="<?= htmlspecialchars($item['item_code']); ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($item['name']); ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($item['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category ID</label>
                <input type="number" class="form-control" id="category_id" name="category_id" required value="<?= htmlspecialchars($item['category_id']); ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required value="<?= htmlspecialchars($item['price']); ?>">
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?= htmlspecialchars($item['image_url']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Menu Item</button>
        </form>
          </div>
         </div>
        
    </div>
</body>
</html>