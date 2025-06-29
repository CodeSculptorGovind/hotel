const API_BASE = '../api/';

function showReservations() {
    const content = document.getElementById('content');
    content.innerHTML = `
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Reservations Management</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadReservations()">
                        <i class="fas fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Guests</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="reservationsTable">
                    <tr>
                        <td colspan="10" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
    loadReservations();
}

function loadReservations() {
    fetch(API_BASE + 'reservations.php?admin=true')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('reservationsTable');
            tbody.innerHTML = '';

            data.forEach(reservation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${reservation.id}</td>
                    <td>${reservation.name}</td>
                    <td>${reservation.email}</td>
                    <td>${reservation.phone}</td>
                    <td>${reservation.date}</td>
                    <td>${reservation.time}</td>
                    <td>${reservation.guests}</td>
                    <td>${reservation.request_type}</td>
                    <td><span class="status-${reservation.status}">${reservation.status}</span></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-success btn-sm" onclick="updateReservationStatus(${reservation.id}, 'approved')">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="updateReservationStatus(${reservation.id}, 'declined')">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-info btn-sm" onclick="rescheduleReservation(${reservation.id})">
                                <i class="fas fa-calendar"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

function updateReservationStatus(id, status) {
    fetch(API_BASE + `reservations.php?id=${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Reservation updated successfully');
            loadReservations();
        } else {
            alert('Failed to update reservation');
        }
    });
}

function rescheduleReservation(id) {
    const newDate = prompt('Enter new date (YYYY-MM-DD):');
    const newTime = prompt('Enter new time (HH:MM):');

    if(newDate && newTime) {
        fetch(API_BASE + `reservations.php?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                status: 'rescheduled',
                date: newDate,
                time: newTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Reservation rescheduled successfully');
                loadReservations();
            } else {
                alert('Failed to reschedule reservation');
            }
        });
    }
}

function showMenu() {
    const content = document.getElementById('content');
    content.innerHTML = `
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Menu Management</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-primary" onclick="showAddMenuForm()">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showAddComboForm()">
                        <i class="fas fa-plus"></i> Add Combo
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="loadMenuItems()">
                        <i class="fas fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab" aria-controls="items" aria-selected="true" onclick="showItemsTab()">Items</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="combos-tab" data-bs-toggle="tab" data-bs-target="#combos" type="button" role="tab" aria-controls="combos" aria-selected="false" onclick="showCombosTab()">Combos</button>
            </li>
        </ul>

        <div id="menuForm" style="display: none;" class="mb-4">
            <div class="card">
                <div class="card-header">Add New Menu Item</div>
                <div class="card-body">
                    <form id="addMenuForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="breakfast">Breakfast</option>
                                        <option value="lunch">Lunch</option>
                                        <option value="dinner">Dinner</option>
                                        <option value="desserts">Desserts</option>
                                        <option value="wine">Wine</option>
                                        <option value="drinks">Drinks</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="url" class="form-control" name="image_url">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_available" checked>
                                <label class="form-check-label">Available</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Menu Item</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddMenuForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="comboForm" style="display: none;" class="mb-4">
            <div class="card">
                <div class="card-header">Add New Combo</div>
                <div class="card-body">
                    <form id="addComboForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="breakfast">Breakfast</option>
                                        <option value="lunch">Lunch</option>
                                        <option value="dinner">Dinner</option>
                                        <option value="desserts">Desserts</option>
                                        <option value="wine">Wine</option>
                                        <option value="drinks">Drinks</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="url" class="form-control" name="image_url">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_available" checked>
                                <label class="form-check-label">Available</label>
                            </div>
                        </div>
                        <div id="comboItems">
                            </div>
                        <button type="button" class="btn btn-success" onclick="addComboItem()">Add Item</button>
                        <button type="submit" class="btn btn-primary">Add Combo</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddComboForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="itemsTable" style="display: block;">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="menuTable">
                    <tr>
                        <td colspan="6" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
            </div>

            <div id="combosTable" style="display: none;">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Items</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="comboTable">
                        <tr>
                            <td colspan="7" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `;

    loadMenuItems();
    loadMenuItemsForCombo();

    document.getElementById('addMenuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addMenuItem();
    });

    document.getElementById('addComboForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addCombo();
    });
}

function showItemsTab() {
    document.getElementById('items-tab').classList.add('active');
    document.getElementById('combos-tab').classList.remove('active');
    document.getElementById('itemsTable').style.display = 'block';
    document.getElementById('combosTable').style.display = 'none';
    document.getElementById('menuForm').style.display = 'none';
    document.getElementById('comboForm').style.display = 'none';
}

function showCombosTab() {
    document.getElementById('combos-tab').classList.add('active');
    document.getElementById('items-tab').classList.remove('active');
    document.getElementById('itemsTable').style.display = 'none';
    document.getElementById('combosTable').style.display = 'block';
    document.getElementById('menuForm').style.display = 'none';
    document.getElementById('comboForm').style.display = 'none';
    loadCombos();
}

function showAddComboForm() {
    document.getElementById('comboForm').style.display = 'block';
    loadMenuItemsForCombo();
}

function hideAddComboForm() {
    document.getElementById('comboForm').style.display = 'none';
    document.getElementById('addComboForm').reset();
}

function addComboItem() {
    const comboItems = document.getElementById('comboItems');
    const newRow = document.createElement('div');
    newRow.className = 'combo-item-row mb-2';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <select class="form-control" name="menu_item_id[]" required>
                    <option value="">Select Menu Item</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="quantity[]" value="1" min="1" required>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_optional[]" value="1">
                    <label class="form-check-label">Optional</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeComboItem(this)">Remove</button>
            </div>
        </div>
    `;
    comboItems.appendChild(newRow);
    loadMenuItemsForCombo();
}

function removeComboItem(button) {
    button.closest('.combo-item-row').remove();
}

function loadMenuItemsForCombo() {
    fetch(API_BASE + 'menu.php')
        .then(response => response.json())
        .then(data => {
            const selects = document.querySelectorAll('select[name="menu_item_id[]"]');
            selects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Select Menu Item</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} - $${item.price}`;
                    if (item.id == currentValue) option.selected = true;
                    select.appendChild(option);
                });
            });
        });
}

function addCombo() {
    const form = document.getElementById('addComboForm');
    const formData = new FormData(form);

    const comboItems = [];
    const menuItemIds = formData.getAll('menu_item_id[]');
    const quantities = formData.getAll('quantity[]');
    const optionals = formData.getAll('is_optional[]');

    menuItemIds.forEach((itemId, index) => {
        if (itemId) {
            comboItems.push({
                menu_item_id: itemId,
                quantity: quantities[index] || 1,
                is_optional: optionals.includes((index + 1).toString()) ? 1 : 0
            });
        }
    });

    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        price: formData.get('price'),
        category: formData.get('category'),
        image_url: formData.get('image_url'),
        is_available: formData.get('is_available') ? 1 : 0,
        combo_items: comboItems
    };

    fetch(API_BASE + 'menu.php?type=combo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Combo added successfully');
            hideAddComboForm();
            loadCombos();
        } else {
            alert('Failed to add combo: ' + data.message);
        }
    });
}

function loadCombos() {
    fetch(API_BASE + 'menu.php?type=combos')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('comboTable');
            tbody.innerHTML = '';

            data.forEach(combo => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${combo.id}</td>
                    <td>${combo.name}</td>
                    <td>${combo.category}</td>
                    <td>$${combo.price}</td>
                    <td>${combo.combo_items || 'No items'}</td>
                    <td>${combo.is_available ? 'Yes' : 'No'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-danger btn-sm" onclick="deleteCombo(${combo.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

function deleteCombo(id) {
    if(confirm('Are you sure you want to delete this combo?')) {
        fetch(API_BASE + `menu.php?id=${id}&type=combo`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Combo deleted successfully');
                loadCombos();
            } else {
                alert('Failed to delete combo');
            }
        });
    }
}

function showAddMenuForm() {
    document.getElementById('menuForm').style.display = 'block';
}

function hideAddMenuForm() {
    document.getElementById('menuForm').style.display = 'none';
    document.getElementById('addMenuForm').reset();
}

function loadMenuItems() {
    fetch(API_BASE + 'menu.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('menuTable');
            tbody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td>$${item.price}</td>
                    <td>${item.is_available ? 'Yes' : 'No'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-warning btn-sm" onclick="editMenuItem(${item.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteMenuItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

function addMenuItem() {
    const form = document.getElementById('addMenuForm');
    const formData = new FormData(form);

    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        price: formData.get('price'),
        category: formData.get('category'),
        image_url: formData.get('image_url'),
        is_available: formData.get('is_available') ? 1 : 0
    };

    fetch(API_BASE + 'menu.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Menu item added successfully');
            hideAddMenuForm();
            loadMenuItems();
        } else {
            alert('Failed to add menu item');
        }
    });
}

function deleteMenuItem(id) {
    if(confirm('Are you sure you want to delete this menu item?')) {
        fetch(API_BASE + `menu.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Menu item deleted successfully');
                loadMenuItems();
            } else {
                alert('Failed to delete menu item');
            }
        });
    }
}

function showTakeaway() {
    const content = document.getElementById('content');
    content.innerHTML = `
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Takeaway Orders</h1>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Takeaway orders will be displayed here when the feature is fully implemented.
        </div>
    `;
}

function showTakeawayMenu() {
    const content = document.getElementById('content');
    content.innerHTML = `
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Takeaway Menu Management</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-primary" onclick="showAddCategoryModal()">
                    <i class="fas fa-plus"></i> Add Category
                </button>
                <button class="btn btn-success ml-2" onclick="showAddItemModal()">
                    <i class="fas fa-plus"></i> Add Menu Item
                </button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Categories</h5>
                    </div>
                    <div class="card-body" id="categoriesList">
                        <!-- Categories will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Menu Items</h5>
                        <select class="form-control ml-auto" style="width: 200px;" id="categoryFilter" onchange="filterMenuItems()">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="menuItemsList">
                                    <!-- Menu items will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Category Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="categoryForm">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="categoryOrder" value="0">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveCategory()">Save Category</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Menu Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="itemForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Item Code</label>
                                        <input type="text" class="form-control" id="itemCode" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Item Name</label>
                                        <input type="text" class="form-control" id="itemName" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="itemDescription" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control" id="itemCategory" required>
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price (£)</label>
                                        <input type="number" step="0.01" class="form-control" id="itemPrice" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Preparation Time (minutes)</label>
                                        <input type="number" class="form-control" id="itemPrepTime" value="15">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="itemOrder" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Allergens (comma separated)</label>
                                <input type="text" class="form-control" id="itemAllergens" placeholder="e.g., nuts, dairy, gluten">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveMenuItem()">Save Item</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    loadTakeawayMenuData();
}

let takeawayCategories = [];
let takeawayMenuItems = [];

async function loadTakeawayMenuData() {
    try {
        const response = await fetch('../api/takeaway_menu.php?action=categories');
        const data = await response.json();
        
        if (data.success) {
            takeawayCategories = data.categories;
            takeawayMenuItems = data.categories.flatMap(cat => 
                cat.items.map(item => ({...item, category_name: cat.name}))
            );
            
            displayTakeawayCategories();
            displayTakeawayMenuItems();
            populateCategorySelects();
        }
    } catch (error) {
        console.error('Error loading takeaway menu:', error);
    }
}

function displayTakeawayCategories() {
    const categoriesList = document.getElementById('categoriesList');
    categoriesList.innerHTML = takeawayCategories.map(cat => `
        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
            <span>${cat.name} (${cat.items.length} items)</span>
            <div>
                <button class="btn btn-sm btn-outline-primary" onclick="editCategory(${cat.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${cat.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function displayTakeawayMenuItems() {
    const menuItemsList = document.getElementById('menuItemsList');
    const categoryFilter = document.getElementById('categoryFilter').value;
    
    let filteredItems = takeawayMenuItems;
    if (categoryFilter) {
        filteredItems = takeawayMenuItems.filter(item => item.category_id == categoryFilter);
    }
    
    menuItemsList.innerHTML = filteredItems.map(item => `
        <tr>
            <td>${item.item_code}</td>
            <td>${item.name}</td>
            <td>${item.category_name}</td>
            <td>£${parseFloat(item.price).toFixed(2)}</td>
            <td>
                <span class="badge badge-${item.is_available ? 'success' : 'danger'}">
                    ${item.is_available ? 'Available' : 'Unavailable'}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editMenuItem(${item.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-sm btn-outline-${item.is_available ? 'warning' : 'success'}" onclick="toggleItemAvailability(${item.id})">
                    <i class="fas fa-${item.is_available ? 'eye-slash' : 'eye'}"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function populateCategorySelects() {
    const categoryFilter = document.getElementById('categoryFilter');
    const itemCategory = document.getElementById('itemCategory');
    
    const categoryOptions = takeawayCategories.map(cat => 
        `<option value="${cat.id}">${cat.name}</option>`
    ).join('');
    
    categoryFilter.innerHTML = '<option value="">All Categories</option>' + categoryOptions;
    itemCategory.innerHTML = '<option value="">Select Category</option>' + categoryOptions;
}

function showAddCategoryModal() {
    const modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
    modal.show();
}

function showAddItemModal() {
    const modal = new bootstrap.Modal(document.getElementById('addItemModal'));
    modal.show();
}

async function saveCategory() {
    const name = document.getElementById('categoryName').value;
    const order = document.getElementById('categoryOrder').value;
    
    try {
        const response = await fetch('../api/takeaway_menu.php?action=category', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, display_order: order })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
            loadTakeawayMenuData();
            document.getElementById('categoryForm').reset();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error saving category');
    }
}

async function saveMenuItem() {
    const itemData = {
        item_code: document.getElementById('itemCode').value,
        name: document.getElementById('itemName').value,
        description: document.getElementById('itemDescription').value,
        category_id: document.getElementById('itemCategory').value,
        price: document.getElementById('itemPrice').value,
        preparation_time: document.getElementById('itemPrepTime').value,
        display_order: document.getElementById('itemOrder').value,
        allergens: document.getElementById('itemAllergens').value
    };
    
    try {
        const response = await fetch('../api/takeaway_menu.php?action=item', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(itemData)
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
            loadTakeawayMenuData();
            document.getElementById('itemForm').reset();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error saving menu item');
    }
}

function filterMenuItems() {
    displayTakeawayMenuItems();
}

async function deleteMenuItem(itemId) {
    if (confirm('Are you sure you want to delete this menu item?')) {
        try {
            const response = await fetch(`../api/takeaway_menu.php?action=item&id=${itemId}`, {
                method: 'DELETE'
            });
            
            const data = await response.json();
            if (data.success) {
                loadTakeawayMenuData();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error deleting menu item');
        }
    }
}