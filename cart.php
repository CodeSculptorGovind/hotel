
<?php include 'includes/header.php'; ?>

<style>
.cart-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px 15px;
}

.cart-header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    text-align: center;
}

.cart-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.cart-item-price {
    font-size: 14px;
    color: #e52b34;
    font-weight: bold;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 8px;
}

.cart-quantity-btn {
    width: 30px;
    height: 30px;
    border: 1px solid #e0e0e0;
    background: #f8f9fa;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
}

.cart-quantity-btn:hover {
    background: #e52b34;
    color: white;
    border-color: #e52b34;
}

.remove-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
}

.cart-summary {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
    position: sticky;
    bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 16px;
}

.summary-row.total {
    font-weight: bold;
    font-size: 18px;
    border-top: 2px solid #e0e0e0;
    padding-top: 10px;
    margin-top: 10px;
    color: #e52b34;
}

.customer-details {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 14px;
}

.form-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
}

.form-input:focus {
    border-color: #e52b34;
}

.place-order-btn {
    width: 100%;
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 20px;
}

.place-order-btn:hover {
    background: #c41e3a;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.back-btn {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}
</style>

<div class="cart-container">
    <a href="order.php" class="back-btn">← Back to Menu</a>
    
    <div class="cart-header">
        <h2>Your Cart</h2>
        <p id="cartItemCount">0 items</p>
    </div>

    <div id="cartItems"></div>
    
    <div class="cart-summary" id="cartSummary" style="display: none;">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span id="subtotal">₹0</span>
        </div>
        <div class="summary-row">
            <span>Delivery Fee:</span>
            <span id="deliveryFee">₹40</span>
        </div>
        <div class="summary-row total">
            <span>Total:</span>
            <span id="total">₹0</span>
        </div>
    </div>

    <div class="customer-details" id="customerDetails" style="display: none;">
        <h3>Delivery Details</h3>
        <form id="orderForm">
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" class="form-input" id="customerName" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="tel" class="form-input" id="customerPhone" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" id="customerEmail">
            </div>
            <div class="form-group">
                <label class="form-label">Delivery Address *</label>
                <textarea class="form-input" id="customerAddress" rows="3" required placeholder="Enter your complete address"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Special Instructions</label>
                <textarea class="form-input" id="specialInstructions" rows="2" placeholder="Any special requests or instructions"></textarea>
            </div>
        </form>
        
        <button class="place-order-btn" onclick="placeOrder()">
            Place Order - <span id="finalTotal">₹0</span>
        </button>
    </div>

    <div class="empty-cart" id="emptyCart">
        <h3>Your cart is empty</h3>
        <p>Add some delicious items to get started!</p>
        <a href="order.php" class="place-order-btn" style="display: inline-block; text-decoration: none; margin-top: 20px;">
            Browse Menu
        </a>
    </div>
</div>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];
const deliveryFee = 40;

function displayCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartSummary = document.getElementById('cartSummary');
    const customerDetails = document.getElementById('customerDetails');
    const emptyCart = document.getElementById('emptyCart');
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '';
        cartSummary.style.display = 'none';
        customerDetails.style.display = 'none';
        emptyCart.style.display = 'block';
        return;
    }
    
    emptyCart.style.display = 'none';
    cartSummary.style.display = 'block';
    customerDetails.style.display = 'block';
    
    cartItemsContainer.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-image" style="background-image: url('${item.image_url || 'images/menu-1.jpg'}')"></div>
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">₹${item.price} each</div>
                <div class="cart-item-controls">
                    <button class="cart-quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span style="min-width: 20px; text-align: center;">${item.quantity}</span>
                    <button class="cart-quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    <button class="remove-btn" onclick="removeItem(${item.id})">Remove</button>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: bold; color: #e52b34;">₹${item.price * item.quantity}</div>
            </div>
        </div>
    `).join('');
    
    updateSummary();
}

function updateQuantity(itemId, change) {
    const item = cart.find(item => item.id == itemId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeItem(itemId);
        } else {
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCart();
        }
    }
}

function removeItem(itemId) {
    cart = cart.filter(item => item.id != itemId);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

function updateSummary() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const total = subtotal + deliveryFee;
    const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
    
    document.getElementById('cartItemCount').textContent = `${itemCount} item${itemCount !== 1 ? 's' : ''}`;
    document.getElementById('subtotal').textContent = `₹${subtotal}`;
    document.getElementById('total').textContent = `₹${total}`;
    document.getElementById('finalTotal').textContent = `₹${total}`;
}

async function placeOrder() {
    const form = document.getElementById('orderForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const total = subtotal + deliveryFee;
    
    const orderData = {
        customer_name: document.getElementById('customerName').value,
        customer_phone: document.getElementById('customerPhone').value,
        customer_email: document.getElementById('customerEmail').value,
        customer_address: document.getElementById('customerAddress').value,
        special_instructions: document.getElementById('specialInstructions').value,
        order_items: cart,
        subtotal: subtotal,
        delivery_fee: deliveryFee,
        total: total,
        order_type: 'takeaway'
    };
    
    try {
        const response = await fetch('api/orders.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear cart
            localStorage.removeItem('cart');
            
            // Redirect to order confirmation
            window.location.href = `order-confirmation.php?order_id=${result.order_id}`;
        } else {
            alert('Error placing order. Please try again.');
        }
    } catch (error) {
        console.error('Error placing order:', error);
        alert('Error placing order. Please try again.');
    }
}

// Load cart on page load
document.addEventListener('DOMContentLoaded', displayCart);
</script>

<?php include 'includes/footer.php'; ?>
