<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Order Online</span></p>
                <h1 class="mb-0 bread">Order Online</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 heading-section text-center ftco-animate mb-5">
                <h2 class="mb-4">Order Your Favorites</h2>
                <p>Browse our delicious menu and place your order for pickup or delivery.</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="search-container">
                    <input type="text" class="form-control" placeholder="Search for dishes..." id="searchInput" style="border-radius: 25px; padding: 12px 20px;">
                </div>
            </div>
        </div>

        <div class="row d-md-flex justify-content-center">
            <div class="col-lg-12 ftco-animate p-md-5">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-all-tab" data-toggle="pill" href="#v-pills-all" role="tab" aria-controls="v-pills-all" aria-selected="true">All Items</a>
                            <a class="nav-link" id="v-pills-breakfast-tab" data-toggle="pill" href="#v-pills-breakfast" role="tab" aria-controls="v-pills-breakfast" aria-selected="false">Breakfast</a>
                            <a class="nav-link" id="v-pills-lunch-tab" data-toggle="pill" href="#v-pills-lunch" role="tab" aria-controls="v-pills-lunch" aria-selected="false">Lunch</a>
                            <a class="nav-link" id="v-pills-dinner-tab" data-toggle="pill" href="#v-pills-dinner" role="tab" aria-controls="v-pills-dinner" aria-selected="false">Dinner</a>
                            <a class="nav-link" id="v-pills-desserts-tab" data-toggle="pill" href="#v-pills-desserts" role="tab" aria-controls="v-pills-desserts" aria-selected="false">Desserts</a>
                            <a class="nav-link" id="v-pills-drinks-tab" data-toggle="pill" href="#v-pills-drinks" role="tab" aria-controls="v-pills-drinks" aria-selected="false">Drinks</a>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex align-items-center">
                        <div class="tab-content ftco-animate" id="v-pills-tabContent">

                            <!-- All Items Tab -->
                            <div class="tab-pane fade show active" id="v-pills-all" role="tabpanel" aria-labelledby="v-pills-all-tab">
                                <div class="row" id="all-menu">
                                    <div class="col-12 text-center">
                                        <p>Loading menu items...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Breakfast Tab -->
                            <div class="tab-pane fade" id="v-pills-breakfast" role="tabpanel" aria-labelledby="v-pills-breakfast-tab">
                                <div class="row" id="breakfast-menu">
                                    <!-- Breakfast items will be loaded here -->
                                </div>
                            </div>

                            <!-- Lunch Tab -->
                            <div class="tab-pane fade" id="v-pills-lunch" role="tabpanel" aria-labelledby="v-pills-lunch-tab">
                                <div class="row" id="lunch-menu">
                                    <!-- Lunch items will be loaded here -->
                                </div>
                            </div>

                            <!-- Dinner Tab -->
                            <div class="tab-pane fade" id="v-pills-dinner" role="tabpanel" aria-labelledby="v-pills-dinner-tab">
                                <div class="row" id="dinner-menu">
                                    <!-- Dinner items will be loaded here -->
                                </div>
                            </div>

                            <!-- Desserts Tab -->
                            <div class="tab-pane fade" id="v-pills-desserts" role="tabpanel" aria-labelledby="v-pills-desserts-tab">
                                <div class="row" id="desserts-menu">
                                    <!-- Desserts items will be loaded here -->
                                </div>
                            </div>

                            <!-- Drinks Tab -->
                            <div class="tab-pane fade" id="v-pills-drinks" role="tabpanel" aria-labelledby="v-pills-drinks-tab">
                                <div class="row" id="drinks-menu">
                                    <!-- Drinks items will be loaded here -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Cart Button -->
<button class="cart-floating" id="cartFloating" onclick="openCart()" style="
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
">
    <span class="cart-count" id="cartCount" style="
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
    ">0</span>
    🛒 Cart
</button>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let menuItems = [];
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
            displayAllMenuItems(menuItems);
            displayMenuByCategory(menuItems);
            updateCartDisplay();
        } else {
            throw new Error('Invalid data format');
        }
    } catch (error) {
        console.error('Error loading menu:', error);
        document.getElementById('all-menu').innerHTML = `
            <div class="col-12 text-center">
                <h3>Unable to load menu</h3>
                <p>Please try again later</p>
                <button onclick="loadMenu()" class="btn btn-primary">Retry</button>
            </div>
        `;
    }
}

function displayAllMenuItems(items) {
    const allMenu = document.getElementById('all-menu');
    allMenu.innerHTML = renderMenuItems(items);
}

function displayMenuByCategory(menuItems) {
    const categories = ['breakfast', 'lunch', 'dinner', 'desserts', 'drinks'];

    categories.forEach(category => {
        const categoryItems = menuItems.filter(item => 
            item.category && item.category.toLowerCase() === category.toLowerCase()
        );

        const container = document.getElementById(category + '-menu');
        if (container) {
            container.innerHTML = renderMenuItems(categoryItems);
        }
    });
}

function renderMenuItems(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center"><p>No items available in this category.</p></div>';
    }

    return items.map(item => `
        <div class="col-md-6">
            <div class="menus d-flex ftco-animate">
                <div class="menu-img img" style="background-image: url(${item.image_url || 'images/menu-1.jpg'});"></div>
                <div class="text">
                    <div class="d-flex">
                        <div class="one-half">
                            <h3>${item.name}</h3>
                        </div>
                        <div class="one-forth">
                            <span class="price">₹${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                    </div>
                    <p>${item.description || 'Delicious item from our kitchen'}</p>
                    <div class="order-controls mt-3">
                        <div class="quantity-controls d-inline-flex align-items-center mr-3" id="qty-controls-${item.id}" style="background: #e52b34; border-radius: 8px; display: none !important;">
                            <button class="btn btn-sm text-white" onclick="decreaseQuantity(${item.id})" style="border: none; background: transparent;">-</button>
                            <span class="text-white px-2" id="qty-${item.id}">0</span>
                            <button class="btn btn-sm text-white" onclick="increaseQuantity(${item.id})" style="border: none; background: transparent;">+</button>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="addToCart(${item.id})" id="add-btn-${item.id}">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function addToCart(itemId) {
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
    showToast('Added to cart!');
}

function increaseQuantity(itemId) {
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    if (existingItem) {
        existingItem.quantity++;
        updateItemDisplay(itemId);
        saveCart();
        updateCartDisplay();
    }
}

function decreaseQuantity(itemId) {
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    if (existingItem) {
        existingItem.quantity--;
        if (existingItem.quantity <= 0) {
            cart = cart.filter(item => item.id != itemId);
            const qtyControls = document.getElementById(`qty-controls-${itemId}`);
            qtyControls.style.display = 'none';
        }
        updateItemDisplay(itemId);
        saveCart();
        updateCartDisplay();
    }
}

function updateItemDisplay(itemId) {
    const qtyElement = document.getElementById(`qty-${itemId}`);
    const qtyControls = document.getElementById(`qty-controls-${itemId}`);
    const addBtn = document.getElementById(`add-btn-${itemId}`);
    const existingItem = cart.find(cartItem => cartItem.id == itemId);
    const quantity = existingItem ? existingItem.quantity : 0;

    if (qtyElement) {
        qtyElement.textContent = quantity;
    }

    if (qtyControls) {
        qtyControls.style.display = quantity > 0 ? 'inline-flex' : 'none';
    }

    if (addBtn) {
        addBtn.textContent = quantity > 0 ? 'Add More' : 'Add to Cart';
    }
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartDisplay() {
    const cartFloating = document.getElementById('cartFloating');
    const cartCount = document.getElementById('cartCount');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

    if (totalItems > 0) {
        cartFloating.style.display = 'flex';
        cartCount.textContent = totalItems;
    } else {
        cartFloating.style.display = 'none';
    }
}

function openCart() {
    window.location.href = 'cart.php';
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: #28a745;
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

// Search functionality
const debouncedSearch = debounceSearch(function(searchTerm) {
    filterItems(searchTerm);
}, 300);

function filterItems(searchTerm = '') {
    let filtered = [...menuItems];

    if (searchTerm.trim()) {
        filtered = filtered.filter(item => 
            item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }

    displayAllMenuItems(filtered);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    loadMenu();

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        debouncedSearch(e.target.value);
    });
});
</script>

<?php include 'includes/footer.php'; ?>