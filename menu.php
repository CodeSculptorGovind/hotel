
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Menu</span></p>
                <h1 class="mb-0 bread">Our Menu</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 heading-section text-center ftco-animate mb-5">
                <h2 class="mb-4">Our Delicious Menu</h2>
                <p>Browse our delicious menu items across different categories.</p>
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

        <!-- Filter Buttons -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-12 text-center">
                <div class="btn-group filter-buttons" role="group">
                    <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">All Items</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="breakfast">Breakfast</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="lunch">Lunch</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="dinner">Dinner</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="desserts">Desserts</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="wine">Wine</button>
                </div>
            </div>
        </div>

        <!-- Menu Items Grid -->
        <div class="row" id="menuContainer">
            <!-- Menu items will be loaded here -->
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

<style>
.menu-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    height: 100%;
}

.menu-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.menu-card .card-img-top {
    height: 250px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.menu-card:hover .card-img-top {
    transform: scale(1.05);
}

.menu-card .card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 250px);
}

.menu-card .card-title {
    color: #333;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.menu-card .card-text {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    flex-grow: 1;
    margin-bottom: 1rem;
}

.price-tag {
    font-size: 1.2rem;
    font-weight: 700;
    color: #e52b34;
    margin-bottom: 1rem;
}

.add-to-cart-btn {
    background: linear-gradient(45deg, #e52b34, #ff6b6b);
    border: none;
    border-radius: 25px;
    padding: 0.7rem 1.5rem;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(229, 43, 52, 0.3);
    color: white;
}

.filter-buttons .filter-btn {
    border-radius: 25px;
    padding: 10px 20px;
    margin: 5px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.filter-buttons .filter-btn.active {
    background: #e52b34;
    border-color: #e52b34;
    color: white;
}

.filter-buttons .filter-btn:hover {
    background: #e52b34;
    border-color: #e52b34;
    color: white;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .menu-card .card-img-top {
        height: 200px;
    }
    
    .filter-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .filter-btn {
        font-size: 0.8rem;
        padding: 8px 15px;
        margin: 3px;
    }
    
    .cart-floating {
        bottom: 15px;
        right: 15px;
        padding: 12px 16px;
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .menu-card .card-body {
        padding: 1rem;
    }
    
    .menu-card .card-title {
        font-size: 1rem;
    }
    
    .menu-card .card-text {
        font-size: 0.85rem;
    }
    
    .add-to-cart-btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let menuItems = [];
let searchTimeout;

// Dummy menu data - 6 items per category
const dummyMenuData = [
    // Breakfast Items
    {
        id: 1,
        name: "Classic Pancakes",
        description: "Fluffy pancakes with maple syrup and butter",
        price: 12.99,
        category: "breakfast",
        image_url: "images/breakfast-1.jpg"
    },
    {
        id: 2,
        name: "English Breakfast",
        description: "Eggs, bacon, sausages, beans, and toast",
        price: 15.99,
        category: "breakfast",
        image_url: "images/breakfast-2.jpg"
    },
    {
        id: 3,
        name: "Avocado Toast",
        description: "Sourdough toast with avocado and cherry tomatoes",
        price: 11.99,
        category: "breakfast",
        image_url: "images/breakfast-3.jpg"
    },
    {
        id: 4,
        name: "Breakfast Burrito",
        description: "Scrambled eggs, cheese, peppers in tortilla",
        price: 13.99,
        category: "breakfast",
        image_url: "images/breakfast-4.jpg"
    },
    {
        id: 5,
        name: "French Toast",
        description: "Golden brioche with cinnamon and vanilla",
        price: 10.99,
        category: "breakfast",
        image_url: "images/breakfast-5.jpg"
    },
    {
        id: 6,
        name: "Eggs Benedict",
        description: "Poached eggs with hollandaise sauce",
        price: 14.99,
        category: "breakfast",
        image_url: "images/breakfast-6.jpg"
    },
    
    // Lunch Items
    {
        id: 7,
        name: "Grilled Chicken",
        description: "Perfectly grilled chicken breast with herbs",
        price: 18.99,
        category: "lunch",
        image_url: "images/lunch-1.jpg"
    },
    {
        id: 8,
        name: "Caesar Salad",
        description: "Fresh romaine lettuce with caesar dressing",
        price: 12.99,
        category: "lunch",
        image_url: "images/lunch-2.jpg"
    },
    {
        id: 9,
        name: "Beef Burger",
        description: "Juicy beef patty with lettuce and tomato",
        price: 15.99,
        category: "lunch",
        image_url: "images/lunch-3.jpg"
    },
    {
        id: 10,
        name: "Fish & Chips",
        description: "Beer battered cod with chunky chips",
        price: 19.99,
        category: "lunch",
        image_url: "images/lunch-4.jpg"
    },
    {
        id: 11,
        name: "Chicken Wrap",
        description: "Grilled chicken with fresh vegetables",
        price: 13.99,
        category: "lunch",
        image_url: "images/lunch-5.jpg"
    },
    {
        id: 12,
        name: "Pasta Salad",
        description: "Fresh pasta with seasonal vegetables",
        price: 11.99,
        category: "lunch",
        image_url: "images/lunch-6.jpg"
    },
    
    // Dinner Items
    {
        id: 13,
        name: "Ribeye Steak",
        description: "Premium ribeye steak grilled to perfection",
        price: 32.99,
        category: "dinner",
        image_url: "images/dinner-1.jpg"
    },
    {
        id: 14,
        name: "Grilled Salmon",
        description: "Atlantic salmon with seasonal vegetables",
        price: 26.99,
        category: "dinner",
        image_url: "images/dinner-2.jpg"
    },
    {
        id: 15,
        name: "Pasta Carbonara",
        description: "Creamy pasta with bacon and eggs",
        price: 18.99,
        category: "dinner",
        image_url: "images/dinner-3.jpg"
    },
    {
        id: 16,
        name: "Lobster Thermidor",
        description: "Fresh lobster with creamy sauce",
        price: 39.99,
        category: "dinner",
        image_url: "images/dinner-4.jpg"
    },
    {
        id: 17,
        name: "Lamb Chops",
        description: "Herb-crusted lamb with mint sauce",
        price: 28.99,
        category: "dinner",
        image_url: "images/dinner-5.jpg"
    },
    {
        id: 18,
        name: "Duck Confit",
        description: "Traditional French duck with orange glaze",
        price: 24.99,
        category: "dinner",
        image_url: "images/dinner-6.jpg"
    },
    
    // Desserts
    {
        id: 19,
        name: "Chocolate Cake",
        description: "Rich chocolate cake with vanilla ice cream",
        price: 8.99,
        category: "desserts",
        image_url: "images/dessert-1.jpg"
    },
    {
        id: 20,
        name: "Tiramisu",
        description: "Classic Italian coffee-flavored dessert",
        price: 9.99,
        category: "desserts",
        image_url: "images/dessert-2.jpg"
    },
    {
        id: 21,
        name: "Cheesecake",
        description: "New York style cheesecake with fruit",
        price: 8.99,
        category: "desserts",
        image_url: "images/dessert-3.jpg"
    },
    {
        id: 22,
        name: "Ice Cream Sundae",
        description: "Vanilla ice cream with chocolate sauce",
        price: 7.99,
        category: "desserts",
        image_url: "images/dessert-4.jpg"
    },
    {
        id: 23,
        name: "Crème Brûlée",
        description: "Vanilla custard with caramelized sugar",
        price: 10.99,
        category: "desserts",
        image_url: "images/dessert-5.jpg"
    },
    {
        id: 24,
        name: "Apple Pie",
        description: "Traditional apple pie with cinnamon",
        price: 6.99,
        category: "desserts",
        image_url: "images/menu-1.jpg"
    },
    
    // Wine Items
    {
        id: 25,
        name: "Red Wine Cabernet",
        description: "Full-bodied red wine from Napa Valley",
        price: 25.99,
        category: "wine",
        image_url: "images/wine-1.jpg",
        type: "alcoholic"
    },
    {
        id: 26,
        name: "White Wine Chardonnay",
        description: "Crisp and refreshing white wine",
        price: 22.99,
        category: "wine",
        image_url: "images/wine-2.jpg",
        type: "alcoholic"
    },
    {
        id: 27,
        name: "Pinot Noir",
        description: "Light and elegant red wine",
        price: 28.99,
        category: "wine",
        image_url: "images/wine-3.jpg",
        type: "alcoholic"
    },
    {
        id: 28,
        name: "Sauvignon Blanc",
        description: "Zesty white wine with citrus notes",
        price: 20.99,
        category: "wine",
        image_url: "images/wine-4.jpg",
        type: "alcoholic"
    },
    {
        id: 29,
        name: "Merlot",
        description: "Smooth and velvety red wine",
        price: 24.99,
        category: "wine",
        image_url: "images/wine-5.jpg",
        type: "alcoholic"
    },
    {
        id: 30,
        name: "Rosé Wine",
        description: "Light and fruity pink wine",
        price: 19.99,
        category: "wine",
        image_url: "images/wine-6.jpg",
        type: "alcoholic"
    }
];

// Debounced search function
function debounceSearch(func, delay) {
    return function(...args) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => func.apply(this, args), delay);
    }
}

// Load menu items
function loadMenu() {
    menuItems = dummyMenuData;
    displayMenuItems(menuItems);
    updateCartDisplay();
}

function displayMenuItems(items) {
    const container = document.getElementById('menuContainer');
    
    if (items.length === 0) {
        container.innerHTML = '<div class="col-12 text-center"><p>No items found.</p></div>';
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="col-lg-4 col-md-6 col-sm-12 menu-item" data-category="${item.category}">
            <div class="card menu-card">
                <img src="${item.image_url || 'images/menu-1.jpg'}" class="card-img-top" alt="${item.name}">
                <div class="card-body">
                    <h5 class="card-title">${item.name}</h5>
                    <p class="card-text">${item.description || 'Delicious item from our kitchen'}</p>
                    <div class="price-tag">₹${item.price.toFixed(2)}</div>
                    ${item.type === 'alcoholic' ? '<span class="badge badge-warning mb-2">21+ Only</span><br>' : ''}
                    <button class="btn add-to-cart-btn" onclick="addToCart(${item.id})" id="add-btn-${item.id}">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Filter functionality
function filterItems(category) {
    let filtered = [...menuItems];
    
    if (category !== 'all') {
        filtered = filtered.filter(item => item.category === category);
    }
    
    const searchTerm = document.getElementById('searchInput').value.trim();
    if (searchTerm) {
        filtered = filtered.filter(item => 
            item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }
    
    displayMenuItems(filtered);
}

// Search functionality
const debouncedSearch = debounceSearch(function() {
    const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
    filterItems(activeFilter);
}, 300);

// Cart functionality
function addToCart(itemId) {
    const item = menuItems.find(item => item.id === itemId);
    if (!item) return;

    const existingItem = cart.find(cartItem => cartItem.id === itemId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            image_url: item.image_url,
            quantity: 1,
            type: item.type || 'regular'
        });
    }
    
    saveCart();
    updateCartDisplay();
    
    // Visual feedback
    const button = document.getElementById(`add-btn-${itemId}`);
    const originalText = button.innerHTML;
    button.innerHTML = 'Added!';
    button.style.background = '#28a745';
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.style.background = '';
    }, 1000);
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCartDisplay() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    const cartCountElement = document.getElementById('cartCount');
    const cartFloating = document.getElementById('cartFloating');
    
    cartCountElement.textContent = cartCount;
    
    if (cartCount > 0) {
        cartFloating.style.display = 'flex';
    } else {
        cartFloating.style.display = 'none';
    }
}

function openCart() {
    window.location.href = 'cart.php';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    loadMenu();

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterItems(this.dataset.filter);
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', debouncedSearch);
});
</script>

<?php include 'includes/footer.php'; ?>
