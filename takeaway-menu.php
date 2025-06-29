
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/imgbg2.jpg');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <h1 class="mb-0 bread takeaway-title">Takeaway Menu</h1>
        <p class="breadcrumbs takeaway-subtitle">Order for Collection or Delivery</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 heading-section text-center ftco-animate mb-5">
        <h2 class="mb-4 menu-main-title">Our Takeaway Menu</h2>
        <p class="menu-subtitle">Authentic flavors delivered to your door</p>
      </div>
    </div>

    <!-- Category Filter Buttons -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-10">
        <div class="category-filters text-center">
          <button class="filter-btn active" data-category="all">All Items</button>
          <div id="categoryButtons"></div>
        </div>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-8">
        <div class="search-container">
          <input type="text" class="form-control search-input" placeholder="Search for dishes..." id="searchInput">
          <i class="fas fa-search search-icon"></i>
        </div>
      </div>
    </div>

    <!-- Menu Items Container -->
    <div class="row" id="menuContainer">
      <!-- Menu items will be loaded here -->
    </div>

    <!-- Loading State -->
    <div class="row" id="loadingState" style="display: none;">
      <div class="col-12 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-3">Loading delicious items...</p>
      </div>
    </div>

    <!-- Empty State -->
    <div class="row" id="emptyState" style="display: none;">
      <div class="col-12 text-center">
        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
        <h4>No items found</h4>
        <p class="text-muted">Try adjusting your search or category filter</p>
      </div>
    </div>
  </div>
</section>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
  <div class="cart-header">
    <h4>Your Order</h4>
    <button class="close-cart" onclick="toggleCart()">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <div class="cart-items" id="cartItems">
    <!-- Cart items will be added here -->
  </div>
  <div class="cart-footer">
    <div class="cart-total">
      <strong>Total: £<span id="cartTotal">0.00</span></strong>
    </div>
    <button class="btn btn-primary btn-block" onclick="proceedToCheckout()">
      Proceed to Checkout
    </button>
  </div>
</div>

<!-- Cart Toggle Button -->
<div class="cart-toggle" onclick="toggleCart()">
  <i class="fas fa-shopping-cart"></i>
  <span class="cart-count" id="cartCount">0</span>
</div>

<style>
/* Takeaway Menu Specific Styles */
.takeaway-title {
  font-family: 'Georgia', serif;
  font-size: 3.5rem;
  font-weight: bold;
  color: #fff;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.takeaway-subtitle {
  font-size: 1.2rem;
  color: #f8f9fa;
  margin-top: 10px;
}

.menu-main-title {
  font-family: 'Georgia', serif;
  color: #2c3e50;
  font-weight: bold;
  position: relative;
}

.menu-main-title::after {
  content: '';
  display: block;
  width: 80px;
  height: 3px;
  background: linear-gradient(135deg, #8B4513, #D2691E);
  margin: 20px auto;
  border-radius: 2px;
}

.menu-subtitle {
  color: #7f8c8d;
  font-style: italic;
  font-size: 1.1rem;
}

.category-filters {
  margin-bottom: 30px;
}

.filter-btn {
  background: #fff;
  border: 2px solid #8B4513;
  color: #8B4513;
  padding: 10px 20px;
  margin: 5px;
  border-radius: 25px;
  font-weight: 600;
  transition: all 0.3s ease;
  cursor: pointer;
}

.filter-btn:hover,
.filter-btn.active {
  background: #8B4513;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
}

.search-container {
  position: relative;
}

.search-input {
  border-radius: 30px;
  padding: 15px 50px 15px 20px;
  border: 2px solid #ddd;
  font-size: 16px;
  transition: all 0.3s ease;
}

.search-input:focus {
  border-color: #8B4513;
  box-shadow: 0 0 15px rgba(139, 69, 19, 0.2);
  outline: none;
}

.search-icon {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #8B4513;
  font-size: 18px;
}

.menu-item-card {
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  margin-bottom: 30px;
  border: 1px solid #f0f0f0;
}

.menu-item-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.menu-item-image {
  height: 200px;
  background-size: cover;
  background-position: center;
  position: relative;
}

.item-price-badge {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #8B4513;
  color: #fff;
  padding: 8px 15px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 16px;
}

.menu-item-content {
  padding: 20px;
}

.item-name {
  font-family: 'Georgia', serif;
  font-size: 1.4rem;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 10px;
}

.item-description {
  color: #7f8c8d;
  font-size: 14px;
  line-height: 1.5;
  margin-bottom: 15px;
}

.item-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  font-size: 12px;
  color: #95a5a6;
}

.add-to-cart-btn {
  width: 100%;
  background: linear-gradient(135deg, #8B4513, #D2691E);
  border: none;
  color: #fff;
  padding: 12px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.add-to-cart-btn:hover {
  background: linear-gradient(135deg, #A0522D, #F4A460);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
}

/* Cart Sidebar Styles */
.cart-sidebar {
  position: fixed;
  right: -400px;
  top: 0;
  width: 400px;
  height: 100vh;
  background: #fff;
  box-shadow: -5px 0 20px rgba(0,0,0,0.1);
  transition: right 0.3s ease;
  z-index: 1000;
  display: flex;
  flex-direction: column;
}

.cart-sidebar.open {
  right: 0;
}

.cart-header {
  padding: 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close-cart {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #999;
}

.cart-items {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
}

.cart-footer {
  padding: 20px;
  border-top: 1px solid #eee;
}

.cart-toggle {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 60px;
  height: 60px;
  background: #8B4513;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 5px 20px rgba(139, 69, 19, 0.3);
  transition: all 0.3s ease;
  z-index: 999;
}

.cart-toggle:hover {
  transform: scale(1.1);
  background: #A0522D;
}

.cart-toggle i {
  color: #fff;
  font-size: 24px;
}

.cart-count {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #e74c3c;
  color: #fff;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
  .takeaway-title {
    font-size: 2.5rem;
  }
  
  .cart-sidebar {
    width: 100vw;
    right: -100vw;
  }
  
  .filter-btn {
    padding: 8px 15px;
    font-size: 14px;
  }
  
  .menu-item-card {
    margin-bottom: 20px;
  }
}
</style>

<script>
let menuItems = [];
let cart = [];
let categories = [];

// Load menu data
async function loadTakeawayMenu() {
  try {
    showLoading(true);
    const response = await fetch('api/takeaway_menu.php?action=categories');
    const data = await response.json();
    
    if (data.success) {
      categories = data.categories;
      menuItems = data.categories.flatMap(cat => 
        cat.items.map(item => ({...item, category_name: cat.name}))
      );
      
      displayCategories();
      displayMenuItems(menuItems);
    } else {
      showError('Failed to load menu');
    }
  } catch (error) {
    console.error('Error loading menu:', error);
    showError('Failed to load menu');
  } finally {
    showLoading(false);
  }
}

function displayCategories() {
  const categoryButtons = document.getElementById('categoryButtons');
  categoryButtons.innerHTML = categories.map(cat => 
    `<button class="filter-btn" data-category="${cat.id}">${cat.name}</button>`
  ).join('');
}

function displayMenuItems(items) {
  const container = document.getElementById('menuContainer');
  
  if (items.length === 0) {
    showEmptyState(true);
    container.innerHTML = '';
    return;
  }
  
  showEmptyState(false);
  
  container.innerHTML = items.map(item => `
    <div class="col-md-6 col-lg-4 menu-item" data-category="${item.category_id}">
      <div class="menu-item-card">
        <div class="menu-item-image" style="background-image: url('${item.image_url || 'images/menu-1.jpg'}')">
          <div class="item-price-badge">£${parseFloat(item.price).toFixed(2)}</div>
        </div>
        <div class="menu-item-content">
          <h5 class="item-name">${item.name}</h5>
          <p class="item-description">${item.description || 'Delicious item from our kitchen'}</p>
          <div class="item-meta">
            <span><i class="fas fa-clock"></i> ${item.preparation_time || 15} mins</span>
            <span><i class="fas fa-tag"></i> ${item.item_code}</span>
          </div>
          <button class="btn add-to-cart-btn" onclick="addToCart(${item.id}, '${item.name}', ${item.price})">
            <i class="fas fa-plus"></i> Add to Cart
          </button>
        </div>
      </div>
    </div>
  `).join('');
}

function filterByCategory(categoryId) {
  const filtered = categoryId === 'all' ? 
    menuItems : 
    menuItems.filter(item => item.category_id == categoryId);
  displayMenuItems(filtered);
}

function searchItems(searchTerm) {
  const filtered = menuItems.filter(item =>
    item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase()))
  );
  displayMenuItems(filtered);
}

function addToCart(itemId, itemName, price) {
  const existingItem = cart.find(item => item.id === itemId);
  
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({ id: itemId, name: itemName, price: price, quantity: 1 });
  }
  
  updateCartDisplay();
  showCartNotification(itemName);
}

function updateCartDisplay() {
  const cartCount = document.getElementById('cartCount');
  const cartTotal = document.getElementById('cartTotal');
  const cartItems = document.getElementById('cartItems');
  
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  
  cartCount.textContent = totalItems;
  cartTotal.textContent = totalPrice.toFixed(2);
  
  cartItems.innerHTML = cart.map(item => `
    <div class="cart-item">
      <div class="cart-item-info">
        <h6>${item.name}</h6>
        <p>£${item.price.toFixed(2)} x ${item.quantity}</p>
      </div>
      <div class="cart-item-actions">
        <button onclick="removeFromCart(${item.id})" class="btn btn-sm btn-danger">Remove</button>
      </div>
    </div>
  `).join('');
}

function removeFromCart(itemId) {
  cart = cart.filter(item => item.id !== itemId);
  updateCartDisplay();
}

function toggleCart() {
  const sidebar = document.getElementById('cartSidebar');
  sidebar.classList.toggle('open');
}

function proceedToCheckout() {
  if (cart.length === 0) {
    alert('Your cart is empty!');
    return;
  }
  
  // Store cart data and redirect to order page
  localStorage.setItem('takeawayCart', JSON.stringify(cart));
  window.location.href = 'order.php';
}

function showCartNotification(itemName) {
  // Simple notification - you can enhance this
  const notification = document.createElement('div');
  notification.className = 'cart-notification';
  notification.innerHTML = `${itemName} added to cart!`;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    z-index: 1001;
  `;
  
  document.body.appendChild(notification);
  setTimeout(() => notification.remove(), 3000);
}

function showLoading(show) {
  document.getElementById('loadingState').style.display = show ? 'block' : 'none';
}

function showEmptyState(show) {
  document.getElementById('emptyState').style.display = show ? 'block' : 'none';
}

function showError(message) {
  const container = document.getElementById('menuContainer');
  container.innerHTML = `
    <div class="col-12 text-center">
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> ${message}
      </div>
    </div>
  `;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
  loadTakeawayMenu();
  
  // Category filter buttons
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('filter-btn')) {
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
      e.target.classList.add('active');
      
      const category = e.target.getAttribute('data-category');
      filterByCategory(category);
    }
  });
  
  // Search functionality
  document.getElementById('searchInput').addEventListener('input', function(e) {
    searchItems(e.target.value);
  });
});
</script>

<?php include 'includes/footer.php'; ?>
