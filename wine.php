
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Wine & Liquor</span></p>
                <h1 class="mb-0 bread">Wine & Liquor Collection</h1>
            </div>
        </div>
    </div>
</section>

<!-- Age Verification Modal -->
<div class="age-verification" id="ageVerification" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
">
    <div class="age-verification-modal" style="
        background: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        max-width: 400px;
        margin: 20px;
    ">
        <h3 style="color: #8b0000; margin-bottom: 20px;">🍷 Age Verification Required</h3>
        <p>You must be 21 years or older to view alcoholic beverages.</p>
        <p>Are you 21 or older?</p>
        <button onclick="confirmAge(true)" style="
            background: #8b0000;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        ">Yes, I'm 21+</button>
        <button onclick="confirmAge(false)" style="
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        ">No, I'm under 21</button>
    </div>
</div>

<section class="ftco-section" id="wineContent" style="display: none;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 heading-section text-center ftco-animate mb-5">
                <h2 class="mb-4">Wine & Liquor Collection</h2>
                <p>Discover our premium selection of wines, spirits, and cocktails</p>
                <div class="alert alert-warning" role="alert">
                    ⚠️ Must be 21+ to purchase alcoholic beverages. Please drink responsibly.
                </div>
            </div>
        </div>

        <div class="row d-md-flex justify-content-center">
            <div class="col-lg-12 ftco-animate p-md-5">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-all-wine-tab" data-toggle="pill" href="#v-pills-all-wine" role="tab" aria-controls="v-pills-all-wine" aria-selected="true">All</a>
                            <a class="nav-link" id="v-pills-wine-tab" data-toggle="pill" href="#v-pills-wine-only" role="tab" aria-controls="v-pills-wine-only" aria-selected="false">🍷 Wine</a>
                            <a class="nav-link" id="v-pills-whiskey-tab" data-toggle="pill" href="#v-pills-whiskey" role="tab" aria-controls="v-pills-whiskey" aria-selected="false">🥃 Whiskey</a>
                            <a class="nav-link" id="v-pills-vodka-tab" data-toggle="pill" href="#v-pills-vodka" role="tab" aria-controls="v-pills-vodka" aria-selected="false">🍸 Vodka</a>
                            <a class="nav-link" id="v-pills-beer-tab" data-toggle="pill" href="#v-pills-beer" role="tab" aria-controls="v-pills-beer" aria-selected="false">🍺 Beer</a>
                            <a class="nav-link" id="v-pills-cocktails-tab" data-toggle="pill" href="#v-pills-cocktails" role="tab" aria-controls="v-pills-cocktails" aria-selected="false">🍹 Cocktails</a>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex align-items-center">
                        <div class="tab-content ftco-animate" id="v-pills-tabContent">

                            <!-- All Wine Items Tab -->
                            <div class="tab-pane fade show active" id="v-pills-all-wine" role="tabpanel" aria-labelledby="v-pills-all-wine-tab">
                                <div class="row" id="all-wine-menu">
                                    <div class="col-12 text-center">
                                        <p>Loading wine collection...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Wine Tab -->
                            <div class="tab-pane fade" id="v-pills-wine-only" role="tabpanel" aria-labelledby="v-pills-wine-tab">
                                <div class="row" id="wine-menu">
                                    <!-- Wine items will be loaded here -->
                                </div>
                            </div>

                            <!-- Whiskey Tab -->
                            <div class="tab-pane fade" id="v-pills-whiskey" role="tabpanel" aria-labelledby="v-pills-whiskey-tab">
                                <div class="row" id="whiskey-menu">
                                    <!-- Whiskey items will be loaded here -->
                                </div>
                            </div>

                            <!-- Vodka Tab -->
                            <div class="tab-pane fade" id="v-pills-vodka" role="tabpanel" aria-labelledby="v-pills-vodka-tab">
                                <div class="row" id="vodka-menu">
                                    <!-- Vodka items will be loaded here -->
                                </div>
                            </div>

                            <!-- Beer Tab -->
                            <div class="tab-pane fade" id="v-pills-beer" role="tabpanel" aria-labelledby="v-pills-beer-tab">
                                <div class="row" id="beer-menu">
                                    <!-- Beer items will be loaded here -->
                                </div>
                            </div>

                            <!-- Cocktails Tab -->
                            <div class="tab-pane fade" id="v-pills-cocktails" role="tabpanel" aria-labelledby="v-pills-cocktails-tab">
                                <div class="row" id="cocktails-menu">
                                    <!-- Cocktails items will be loaded here -->
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
    background: #8b0000;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 15px 20px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 20px rgba(139, 0, 0, 0.3);
    cursor: pointer;
    z-index: 1000;
    display: none;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
">
    <span class="cart-count" id="cartCount" style="
        background: white;
        color: #8b0000;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    ">0</span>
    🍷 Cart
</button>

<script>
let wineItems = [];
let wineCart = JSON.parse(localStorage.getItem('cart')) || [];

function confirmAge(isAdult) {
    if (isAdult) {
        document.getElementById('ageVerification').style.display = 'none';
        document.getElementById('wineContent').style.display = 'block';
        localStorage.setItem('ageVerified', 'true');
        loadWineItems();
    } else {
        window.location.href = 'order.php';
    }
}

// Check if age is already verified
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('ageVerified') === 'true') {
        document.getElementById('ageVerification').style.display = 'none';
        document.getElementById('wineContent').style.display = 'block';
        loadWineItems();
    }
});

async function loadWineItems() {
    try {
        const response = await fetch('api/menu.php');
        const data = await response.json();
        
        // Filter only liquor/wine items
        wineItems = data.filter(item => 
            item.category === 'liquor' || 
            item.category === 'wine' ||
            item.category === 'drinks' ||
            item.name.toLowerCase().includes('wine') ||
            item.name.toLowerCase().includes('beer') ||
            item.name.toLowerCase().includes('whiskey') ||
            item.name.toLowerCase().includes('vodka') ||
            item.name.toLowerCase().includes('cocktail')
        );
        
        displayAllWineItems(wineItems);
        displayWineByCategory(wineItems);
        updateCartDisplay();
    } catch (error) {
        console.error('Error loading wine items:', error);
        document.getElementById('all-wine-menu').innerHTML = '<div class="col-12 text-center">Error loading items. Please try again.</div>';
    }
}

function displayAllWineItems(items) {
    const allWineMenu = document.getElementById('all-wine-menu');
    allWineMenu.innerHTML = renderWineItems(items);
}

function displayWineByCategory(wineItems) {
    const categories = ['wine', 'whiskey', 'vodka', 'beer', 'cocktails'];
    
    categories.forEach(category => {
        const categoryItems = wineItems.filter(item => getWineCategory(item) === category);
        const container = document.getElementById(category + '-menu');
        if (container) {
            container.innerHTML = renderWineItems(categoryItems);
        }
    });
}

function renderWineItems(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center"><p>No items available in this category.</p></div>';
    }

    return items.map(item => `
        <div class="col-md-6">
            <div class="menus d-flex ftco-animate">
                <div class="menu-img img" style="background-image: url(${item.image_url || 'images/wine-1.jpg'});"></div>
                <div class="text">
                    <div class="d-flex">
                        <div class="one-half">
                            <h3>${item.name} <span class="badge badge-danger">21+</span></h3>
                        </div>
                        <div class="one-forth">
                            <span class="price">₹${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                    </div>
                    <p>${item.description || 'Premium quality beverage'}</p>
                    <small class="text-muted">Served chilled | 21+ only</small>
                    <div class="order-controls mt-3">
                        <div class="quantity-controls d-inline-flex align-items-center mr-3" id="wine-qty-controls-${item.id}" style="background: #8b0000; border-radius: 8px; display: none;">
                            <button class="btn btn-sm text-white" onclick="decreaseWineQuantity(${item.id})" style="border: none; background: transparent;">-</button>
                            <span class="text-white px-2" id="wine-qty-${item.id}">0</span>
                            <button class="btn btn-sm text-white" onclick="increaseWineQuantity(${item.id})" style="border: none; background: transparent;">+</button>
                        </div>
                        <button class="btn btn-sm" style="background: #8b0000; color: white;" onclick="addWineToCart(${item.id})" id="wine-add-btn-${item.id}">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function getWineCategory(item) {
    const name = item.name.toLowerCase();
    if (name.includes('wine') || name.includes('red') || name.includes('white')) return 'wine';
    if (name.includes('whiskey') || name.includes('scotch')) return 'whiskey';
    if (name.includes('vodka')) return 'vodka';
    if (name.includes('beer')) return 'beer';
    if (name.includes('cocktail') || name.includes('mojito') || name.includes('martini')) return 'cocktails';
    return 'wine';
}

function addWineToCart(itemId) {
    const item = wineItems.find(item => item.id == itemId);
    const existingItem = wineCart.find(cartItem => cartItem.id == itemId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        wineCart.push({
            id: item.id,
            name: item.name,
            price: parseFloat(item.price),
            quantity: 1,
            image_url: item.image_url,
            type: 'alcoholic'
        });
    }
    
    updateWineItemDisplay(itemId);
    saveWineCart();
    updateCartDisplay();
    showToast('Added to cart! 🍷');
}

function increaseWineQuantity(itemId) {
    const existingItem = wineCart.find(cartItem => cartItem.id == itemId);
    if (existingItem) {
        existingItem.quantity++;
        updateWineItemDisplay(itemId);
        saveWineCart();
        updateCartDisplay();
    }
}

function decreaseWineQuantity(itemId) {
    const existingItem = wineCart.find(cartItem => cartItem.id == itemId);
    if (existingItem) {
        existingItem.quantity--;
        if (existingItem.quantity <= 0) {
            wineCart = wineCart.filter(item => item.id != itemId);
            const qtyControls = document.getElementById(`wine-qty-controls-${itemId}`);
            qtyControls.style.display = 'none';
        }
        updateWineItemDisplay(itemId);
        saveWineCart();
        updateCartDisplay();
    }
}

function updateWineItemDisplay(itemId) {
    const qtyElement = document.getElementById(`wine-qty-${itemId}`);
    const qtyControls = document.getElementById(`wine-qty-controls-${itemId}`);
    const addBtn = document.getElementById(`wine-add-btn-${itemId}`);
    const existingItem = wineCart.find(cartItem => cartItem.id == itemId);
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

function saveWineCart() {
    localStorage.setItem('cart', JSON.stringify(wineCart));
}

function updateCartDisplay() {
    const cartFloating = document.getElementById('cartFloating');
    const cartCount = document.getElementById('cartCount');
    const totalItems = wineCart.reduce((total, item) => total + item.quantity, 0);
    
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
        background: #8b0000;
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
</script>

<?php include 'includes/footer.php'; ?>
