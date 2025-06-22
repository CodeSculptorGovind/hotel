
<?php include 'includes/header.php'; ?>

<style>
.wine-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 15px;
}

.wine-header {
    text-align: center;
    margin-bottom: 40px;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.wine-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 30px;
}

.wine-category-btn {
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.wine-category-btn.active {
    background: #8b0000;
    color: white;
    border-color: #8b0000;
}

.wine-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

@media (min-width: 768px) {
    .wine-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .wine-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.wine-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.wine-card:hover {
    transform: translateY(-5px);
}

.wine-card-image {
    width: 100%;
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.wine-card-content {
    padding: 20px;
}

.wine-card-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #8b0000;
}

.wine-card-type {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-weight: 600;
}

.wine-card-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.wine-card-price {
    font-size: 20px;
    font-weight: bold;
    color: #8b0000;
    margin-bottom: 15px;
}

.wine-card-details {
    font-size: 12px;
    color: #888;
    margin-bottom: 15px;
}

.wine-order-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.wine-quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.wine-quantity-btn {
    width: 35px;
    height: 35px;
    border: 2px solid #8b0000;
    background: white;
    color: #8b0000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.wine-quantity-btn:hover, .wine-quantity-btn.active {
    background: #8b0000;
    color: white;
}

.wine-add-btn {
    background: #8b0000;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.age-verification {
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
}

.age-verification-modal {
    background: white;
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    max-width: 400px;
    margin: 20px;
}

.age-verification h3 {
    color: #8b0000;
    margin-bottom: 20px;
}

.age-btn {
    background: #8b0000;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    margin: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.age-btn.secondary {
    background: #6c757d;
}

.wine-disclaimer {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}
</style>

<!-- Age Verification Modal -->
<div class="age-verification" id="ageVerification">
    <div class="age-verification-modal">
        <h3>🍷 Age Verification Required</h3>
        <p>You must be 21 years or older to view alcoholic beverages.</p>
        <p>Are you 21 or older?</p>
        <button class="age-btn" onclick="confirmAge(true)">Yes, I'm 21+</button>
        <button class="age-btn secondary" onclick="confirmAge(false)">No, I'm under 21</button>
    </div>
</div>

<div class="wine-container" id="wineContent" style="display: none;">
    <div class="wine-header">
        <h1>🍷 Wine & Liquor Collection</h1>
        <p>Discover our premium selection of wines, spirits, and cocktails</p>
        
        <div class="wine-disclaimer">
            ⚠️ Must be 21+ to purchase alcoholic beverages. Please drink responsibly.
        </div>
        
        <div class="wine-categories">
            <button class="wine-category-btn active" data-category="all">All</button>
            <button class="wine-category-btn" data-category="wine">🍷 Wine</button>
            <button class="wine-category-btn" data-category="whiskey">🥃 Whiskey</button>
            <button class="wine-category-btn" data-category="vodka">🍸 Vodka</button>
            <button class="wine-category-btn" data-category="beer">🍺 Beer</button>
            <button class="wine-category-btn" data-category="cocktails">🍹 Cocktails</button>
        </div>
    </div>

    <div class="wine-grid" id="wineGrid">
        <div class="loading">Loading wine collection...</div>
    </div>
</div>

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
        
        displayWineItems(wineItems);
    } catch (error) {
        console.error('Error loading wine items:', error);
        document.getElementById('wineGrid').innerHTML = '<div class="loading">Error loading items. Please try again.</div>';
    }
}

function displayWineItems(items) {
    const wineGrid = document.getElementById('wineGrid');
    
    if (items.length === 0) {
        wineGrid.innerHTML = '<div class="loading">No items found in this category</div>';
        return;
    }

    wineGrid.innerHTML = items.map(item => `
        <div class="wine-card" data-category="${getWineCategory(item)}">
            <div class="wine-card-image" style="background-image: url('${item.image_url || 'images/wine-1.jpg'}')"></div>
            <div class="wine-card-content">
                <div class="wine-card-type">${getWineCategory(item)}</div>
                <div class="wine-card-title">${item.name}</div>
                <div class="wine-card-description">${item.description || 'Premium quality beverage'}</div>
                <div class="wine-card-details">Served chilled | 21+ only</div>
                <div class="wine-card-price">₹${item.price}</div>
                <div class="wine-order-controls">
                    <div class="wine-quantity-controls">
                        <button class="wine-quantity-btn" onclick="decreaseWineQuantity(${item.id})">-</button>
                        <span id="wine-qty-${item.id}">0</span>
                        <button class="wine-quantity-btn" onclick="increaseWineQuantity(${item.id})">+</button>
                    </div>
                    <button class="wine-add-btn" onclick="addWineToCart(${item.id})">Add to Cart</button>
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

// Category filtering
document.querySelectorAll('.wine-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.wine-category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const category = this.dataset.category;
        let filteredItems = wineItems;
        
        if (category !== 'all') {
            filteredItems = wineItems.filter(item => getWineCategory(item) === category);
        }
        
        displayWineItems(filteredItems);
    });
});

function increaseWineQuantity(itemId) {
    const qtyElement = document.getElementById(`wine-qty-${itemId}`);
    let currentQty = parseInt(qtyElement.textContent);
    currentQty++;
    qtyElement.textContent = currentQty;
}

function decreaseWineQuantity(itemId) {
    const qtyElement = document.getElementById(`wine-qty-${itemId}`);
    let currentQty = parseInt(qtyElement.textContent);
    if (currentQty > 0) {
        currentQty--;
        qtyElement.textContent = currentQty;
    }
}

function addWineToCart(itemId) {
    const qtyElement = document.getElementById(`wine-qty-${itemId}`);
    const quantity = parseInt(qtyElement.textContent);
    
    if (quantity === 0) {
        alert('Please select quantity first');
        return;
    }
    
    const item = wineItems.find(item => item.id == itemId);
    const existingItem = wineCart.find(cartItem => cartItem.id == itemId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        wineCart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: quantity,
            image_url: item.image_url,
            type: 'alcoholic'
        });
    }
    
    // Reset quantity display
    qtyElement.textContent = '0';
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(wineCart));
    
    // Show success message
    showToast('Added to cart! 🍷');
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
