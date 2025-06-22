
<?php include 'includes/header.php'; ?>

<style>
.mobile-order-container {
    padding: 10px;
    max-width: 100%;
    margin: 0 auto;
}

.search-bar {
    position: sticky;
    top: 80px;
    z-index: 100;
    background: white;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.search-input {
    width: 100%;
    padding: 12px 40px 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
}

.search-input:focus {
    border-color: #e52b34;
}

.filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 15px;
    padding-bottom: 10px;
    overflow-x: auto;
}

.filter-chip {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-chip.active {
    background: #e52b34;
    color: white;
    border-color: #e52b34;
}

.menu-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
    padding: 0 10px;
}

.menu-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.menu-card:hover {
    transform: translateY(-2px);
}

.menu-card-image {
    width: 100%;
    height: 150px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.menu-card-content {
    padding: 15px;
}

.menu-card-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.menu-card-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    line-height: 1.4;
}

.menu-card-price {
    font-size: 18px;
    font-weight: bold;
    color: #e52b34;
    margin-bottom: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
}

.quantity-btn {
    width: 35px;
    height: 35px;
    border: 2px solid #e52b34;
    background: white;
    color: #e52b34;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
}

.quantity-btn:hover, .quantity-btn.active {
    background: #e52b34;
    color: white;
}

.quantity-display {
    font-size: 16px;
    font-weight: bold;
    min-width: 30px;
    text-align: center;
}

.add-to-cart-btn {
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
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
}

.cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
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

.veg-icon {
    color: #4CAF50;
    font-size: 12px;
}

.non-veg-icon {
    color: #f44336;
    font-size: 12px;
}

.loading {
    text-align: center;
    padding: 50px;
    font-size: 16px;
    color: #666;
}

@media (min-width: 768px) {
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .mobile-order-container {
        max-width: 1200px;
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
            <div class="filter-chip" data-filter="combo">🍽️ Combo</div>
            <div class="filter-chip" data-filter="burger">🍔 Burger</div>
            <div class="filter-chip" data-filter="pizza">🍕 Pizza</div>
            <div class="filter-chip" data-filter="drinks">🥤 Drinks</div>
            <div class="filter-chip" data-filter="desserts">🍰 Desserts</div>
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

// Load menu items
async function loadMenu() {
    try {
        const response = await fetch('api/menu.php');
        const data = await response.json();
        menuItems = data;
        displayMenuItems(menuItems);
        updateCartDisplay();
    } catch (error) {
        console.error('Error loading menu:', error);
        document.getElementById('menuGrid').innerHTML = '<div class="loading">Error loading menu. Please try again.</div>';
    }
}

// Display menu items
function displayMenuItems(items) {
    const menuGrid = document.getElementById('menuGrid');
    
    if (items.length === 0) {
        menuGrid.innerHTML = '<div class="loading">No items found</div>';
        return;
    }

    menuGrid.innerHTML = items.map(item => `
        <div class="menu-card" data-category="${item.category}" data-type="${getItemType(item)}">
            <div class="menu-card-image" style="background-image: url('${item.image_url || 'images/menu-1.jpg'}')">
                <div style="position: absolute; top: 10px; left: 10px;">
                    ${getItemTypeIcon(item)}
                </div>
            </div>
            <div class="menu-card-content">
                <div class="menu-card-title">${item.name}</div>
                <div class="menu-card-description">${item.description || 'Delicious and freshly prepared'}</div>
                <div class="menu-card-price">₹${item.price}</div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="decreaseQuantity(${item.id})">-</button>
                    <span class="quantity-display" id="qty-${item.id}">0</span>
                    <button class="quantity-btn" onclick="increaseQuantity(${item.id})">+</button>
                    <button class="add-to-cart-btn" onclick="addToCart(${item.id})" id="add-btn-${item.id}">Add</button>
                </div>
            </div>
        </div>
    `).join('');
    
    // Update quantities from cart
    cart.forEach(cartItem => {
        const qtyElement = document.getElementById(`qty-${cartItem.id}`);
        if (qtyElement) {
            qtyElement.textContent = cartItem.quantity;
        }
    });
}

// Get item type for filtering
function getItemType(item) {
    const name = item.name.toLowerCase();
    if (name.includes('burger')) return 'burger';
    if (name.includes('pizza')) return 'pizza';
    if (name.includes('combo') || name.includes('meal')) return 'combo';
    if (item.category === 'drinks' || item.category === 'liquor') return 'drinks';
    if (item.category === 'desserts') return 'desserts';
    
    // Simple veg/non-veg detection (you can enhance this)
    if (name.includes('chicken') || name.includes('mutton') || name.includes('beef') || name.includes('fish')) {
        return 'non-veg';
    }
    return 'veg';
}

// Get item type icon
function getItemTypeIcon(item) {
    const type = getItemType(item);
    if (type === 'non-veg') {
        return '<span class="non-veg-icon">🔺</span>';
    }
    return '<span class="veg-icon">🔸</span>';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const filteredItems = menuItems.filter(item => 
        item.name.toLowerCase().includes(searchTerm) ||
        (item.description && item.description.toLowerCase().includes(searchTerm))
    );
    displayMenuItems(filteredItems);
});

// Filter functionality
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        // Update active state
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        let filteredItems = menuItems;
        
        if (filter !== 'all') {
            filteredItems = menuItems.filter(item => getItemType(item) === filter);
        }
        
        displayMenuItems(filteredItems);
    });
});

// Cart functions
function increaseQuantity(itemId) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    let currentQty = parseInt(qtyElement.textContent);
    currentQty++;
    qtyElement.textContent = currentQty;
}

function decreaseQuantity(itemId) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    let currentQty = parseInt(qtyElement.textContent);
    if (currentQty > 0) {
        currentQty--;
        qtyElement.textContent = currentQty;
    }
}

function addToCart(itemId) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    const quantity = parseInt(qtyElement.textContent);
    
    if (quantity === 0) {
        alert('Please select quantity first');
        return;
    }
    
    const item = menuItems.find(item => item.id == itemId);
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: quantity,
            image_url: item.image_url
        });
    }
    
    // Reset quantity display
    qtyElement.textContent = '0';
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
    
    // Show success message
    showToast('Added to cart!');
}

function updateCartDisplay() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cartCount').textContent = cartCount;
    
    const cartFloating = document.getElementById('cartFloating');
    if (cartCount > 0) {
        cartFloating.style.display = 'block';
    } else {
        cartFloating.style.display = 'none';
    }
}

function openCart() {
    window.location.href = 'cart.php';
}

function showToast(message) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        z-index: 1001;
        font-weight: bold;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}

// Load menu on page load
document.addEventListener('DOMContentLoaded', loadMenu);
</script>

<?php include 'includes/footer.php'; ?>
