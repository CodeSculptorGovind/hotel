
<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Build query with optional category filter
$whereClause = "WHERE m.is_available = 1";
$params = [];

if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $whereClause .= " AND m.category_id = ?";
    $params[] = $_GET['category_id'];
}

$query = "SELECT m.*, c.display_name as category_name 
          FROM menu_items m 
          LEFT JOIN categories c ON m.category_id = c.id 
          $whereClause 
          ORDER BY c.sort_order, m.name";

$stmt = $db->prepare($query);
$stmt->execute($params);
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($menuItems)): ?>
                <tr>
                    <td colspan="7" class="text-center">No menu items found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td>
                            <img src="../../<?= $item['image_url'] ?: 'images/default-menu-item.jpg' ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars(substr($item['description'], 0, 50)) ?>...</td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category_name']) ?></span></td>
                        <td>£<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <span class="badge <?= $item['is_available'] ? 'bg-success' : 'bg-danger' ?>">
                                <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>')" 
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
