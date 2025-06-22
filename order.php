
<?php include 'includes/header.php'; ?>

<style>
/* Mobile-first design */
.mobile-order-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 15px;
    background: #f8f9fa;
    min-height: 100vh;
}

.search-bar {
    background: white;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 80px;
    z-index: 100;
}

.search-input {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    background: #f8f9fa;
}

.search-input:focus {
    border-color: #e52b34;
    background: white;
}

.filter-chips {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 15px 0 5px 0;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.filter-chips::-webkit-scrollbar {
    display: none;
}

.filter-chip {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.filter-chip.active {
    background: #e52b34;
    color: white;
    border-color: #e52b34;
}

.filter-chip:hover {
    border-color: #e52b34;
}

.menu-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
    margin-bottom: 100px;
}

.menu-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.menu-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.menu-card-image {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.item-type-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 20px;
    height: 20px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.veg-badge {
    background: #4CAF50;
}

.non-veg-badge {
    background: #f44336;
}

.menu-card-content {
    padding: 15px;
}

.menu-card-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    line-height: 1.3;
}

.menu-card-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 12px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.menu-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-card-price {
    font-size: 20px;
    font-weight: bold;
    color: #e52b34;
}

.add-to-cart-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-controls {
    display: none;
    align-items: center;
    background: #e52b34;
    border-radius: 8px;
    overflow: hidden;
}

.quantity-controls.show {
    display: flex;
}

.quantity-btn {
    width: 35px;
    height: 35px;
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn:hover {
    background: rgba(255,255,255,0.2);
}

.quantity-display {
    min-width: 40px;
    text-align: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.add-btn {
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.add-btn:hover {
    background: #c41e3a;
}

.cart-floating {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 15px 20px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 20px rgba(229, 43, 52, 0.3);
    cursor: pointer;
    z-index: 1000;
    display: none;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.cart-floating.show {
    display: flex;
}

.cart-floating:hover {
    transform: scale(1.05);
}

.cart-count {
    background: white;
    color: #e52b34;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.loading {
    text-align: center;
    padding: 50px;
    font-size: 16px;
    color: #666;
}

.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #666;
}

@media (min-width: 768px) {
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .mobile-order-container {
        max-width: 1200px;
        padding: 20px;
    }
}

@media (min-width: 1024px) {
    .menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<div class="mobile-order-container">
    <!-- Search and Filter Section -->
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search for dishes..." id="searchInput">
        <div class="filter-chips">
            <div class="filter-chip active" data-filter="all">All</div>
            <div class="filter-chip" data-filter="veg">🌱 Veg</div>
            <div class="filter-chip" data-filter="non-veg">🍖 Non-Veg</div>
            <div class="filter-chip" data-filter="breakfast">🍳 Breakfast</div>
            <div class="filter-chip" data-filter="lunch">🍽️ Lunch</div>
            <div class="filter-chip" data-filter="dinner">🌙 Dinner</div>
            <div class="filter-chip" data-filter="desserts">🍰 Desserts</div>
            <div class="filter-chip" data-filter="drinks">🥤 Drinks</div>
            <div class="filter-chip" data-filter="wine">🍷 Wine</div>
        </div>
    </div>

    <!-- Menu Items Grid -->
    <div class="menu-grid" id="menuGrid">
        <div class="loading">Loading menu items...</div>
    </div>
</div>

<!-- Floating Cart Button -->
<button class="cart-floating" id="cartFloating" onclick="openCart()">
    <span class="cart-count" id="cartCount">0</span>
    🛒 Cart
</button>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let menuItems = [];
let filteredItems = [];
let currentFilter = 'all';
let searchTimeout;

// Debounced search function
function debounceSearch(func, delay) {
    return function(args) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => func.apply(this, args), delay);
    }
}

// Load menu items
async function loadMenu() {
    try {
        const response = await fetch('api/menu.php');
        const data = await response.json();
        
        if (Array.isArray(data)) {
            menuItems = data.filter(item => item.is_available == 1);
            filteredItems = [...menuItems];
            displayMenuItems(filteredItems);
            updateCartDisplay();
        } else {
            throw new Error('Invalid data format');
        }
    } catch (error) {
        console.error('Error loading menu:', error);
        document.getElementById('menuGrid').innerHTML = `
            <div class="empty-state">
                <h3>Unable to load menu</h3>
                <p>Please try again later</p>
                <button onclick="loadMenu()" class="add-btn">Retry</button>
            </div>
        `;
    }
}

// Display menu items
function displayMenuItems(items) {
    const menuGrid = document.getElementById('menuGrid');
    
    if (items.length === 0) {
        menuGrid.innerHTML = `
            <div class="empty-state">
                <h3>No items found</h3>
                <p>Try adjusting your search or filters</p>
            </div>
        `;
        return;
    }

    menuGrid.innerHTML = items.map(item => {
        const cartItem = cart.find(cartItem => cartItem.id == item.id);
        const quantity = cartItem ? cartItem.quantity : 0;
        const isVeg = getItemType(item) === 'veg';
        
        return `
            <div class="menu-card" data-category="${item.category}" data-type="${getItemType(item)}">
                <div class="menu-card-image" style="background-image: url('${item.image_url || 'images/menu-1.jpg'}')">
                    <div class="item-type-badge ${isVeg ? 'veg-badge' : 'non-veg-badge'}">
                        ${isVeg ? '🔸' : '🔺'}
                    </div>
                </div>
                <div class="menu-card-content">
                    <div class="menu-card-title">${item.name}</div>
                    <div class="menu-card-description">${item.description || 'Delicious and freshly prepared'}</div>
                    <div class="menu-card-footer">
                        <div class="menu-card-price">₹${item.price}</div>
                        <div class="add-to-cart-container">
                            <div class="quantity-controls ${quantity > 0 ? 'show' : ''}" id="qty-controls-${item.id}">
                                <button class="quantity-btn" onclick="decreaseQuantity(${item.id})">-</button>
                                <span class="quantity-display" id="qty-${item.id}">${quantity}</span>
                                <button class="quantity-btn" onclick="increaseQuantity(${item.id})">+</button>
                            </div>
                            <button class="add-btn ${quantity > 0 ? 'show' : ''}" onclick="toggleQuantity(${item.id})" id="add-btn-${item.id}">
                                ${quantity > 0 ? 'ADD MORE' : 'ADD'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Get item type (veg/non-veg)
function getItemType(item) {
    const vegKeywords = ['veg', 'vegetarian', 'paneer', 'dal', 'aloo', 'gobi', 'salad'];
    const itemName = item.name.toLowerCase();
    const itemDesc = (item.description || '').toLowerCase();
    
    // Check if explicitly marked as veg
    if (vegKeywords.some(keyword => itemName.includes(keyword) || itemDesc.includes(keyword))) {
        return 'veg';
    }
    
    // Default to non-veg for meat items
    const nonVegKeywords = ['chicken', 'mutton', 'fish', 'egg', 'meat', 'biryani'];
    if (nonVegKeywords.some(keyword => itemName.includes(keyword) || itemDesc.includes(keyword))) {
        return 'non-veg';
    }
    
    // Default to veg for drinks and desserts
    if (item.category && ['drinks', 'desserts', 'wine'].includes(item.category.toLowerCase())) {
        return 'veg';
    }
    
    return 'veg'; // Default to veg
}

// Toggle quantity controls
function toggleQuantity(itemId) {
    const qtyControls = document.getElementById(`qty-controls-${itemId}`);
    const addBtn = document.getElementById(`add-btn-${itemId}`);
    
    if (!qtyControls.classList.contains('show')) {
        qtyControls.classList.add('show');
        increaseQuantity(itemId);
    } else {
        increaseQuantity(itemId);
    }
}

// Increase quantity
function increaseQuantity(itemId) {
    const item = menuItems.find(item => item.id == itemId);
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: parseFloat(item.price),
            quantity: 1,
            image_url: item.image_url
        });
    }
    
    updateItemDisplay(itemId);
    saveCart();
    updateCartDisplay();
}

// Decrease quantity
function decreaseQuantity(itemId) {
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    
    if (existingItem) {
        existingItem.quantity--;
        if (existingItem.quantity <= 0) {
            cart = cart.filter(item => item.id != itemId);
            const qtyControls = document.getElementById(`qty-controls-${itemId}`);
            qtyControls.classList.remove('show');
        }
    }
    
    updateItemDisplay(itemId);
    saveCart();
    updateCartDisplay();
}

// Update item display
function updateItemDisplay(itemId) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    const addBtn = document.getElementById(`add-btn-${itemId}`);
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    const quantity = existingItem ? existingItem.quantity : 0;
    
    if (qtyElement) {
        qtyElement.textContent = quantity;
    }
    
    if (addBtn) {
        addBtn.textContent = quantity > 0 ? 'ADD MORE' : 'ADD';
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Update cart display
function updateCartDisplay() {
    const cartFloating = document.getElementById('cartFloating');
    const cartCount = document.getElementById('cartCount');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    
    if (totalItems > 0) {
        cartFloating.classList.add('show');
        cartCount.textContent = totalItems;
    } else {
        cartFloating.classList.remove('show');
    }
}

// Open cart
function openCart() {
    window.location.href = 'cart.php';
}

// Debounced search
const debouncedSearch = debounceSearch(function(searchTerm) {
    filterItems(searchTerm, currentFilter);
}, 300);

// Filter items
function filterItems(searchTerm = '', filter = 'all') {
    let filtered = [...menuItems];
    
    // Apply search filter
    if (searchTerm.trim()) {
        filtered = filtered.filter(item => 
            item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }
    
    // Apply category filter
    if (filter !== 'all') {
        if (filter === 'veg' || filter === 'non-veg') {
            filtered = filtered.filter(item => getItemType(item) === filter);
        } else {
            filtered = filtered.filter(item => 
                item.category && item.category.toLowerCase() === filter.toLowerCase()
            );
        }
    }
    
    filteredItems = filtered;
    displayMenuItems(filteredItems);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    loadMenu();
    
    // Search functionality with debouncing
    document.getElementById('searchInput').addEventListener('input', function(e) {
        debouncedSearch(e.target.value);
    });
    
    // Filter functionality
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.dataset.filter;
            const searchTerm = document.getElementById('searchInput').value;
            filterItems(searchTerm, currentFilter);
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
