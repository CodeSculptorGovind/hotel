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