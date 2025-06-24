<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Cart</span></p>
                <h1 class="mb-0 bread">Your Cart</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-container">
                    <h3 class="mb-4">Order Summary</h3>
                    <div id="cartItems">
                        <!-- Cart items will be loaded here -->
                    </div>

                    <div class="empty-cart text-center" id="emptyCart" style="display: none;">
                        <h4>Your cart is empty</h4>
                        <p>Add some delicious items to get started!</p>
                        <a href="order.php" class="btn btn-primary">Browse Menu</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary bg-light p-4 rounded">
                    <h4 class="mb-3">Order Details</h4>
                    <div class="order-total mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span id="subtotal">£0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax (5%):</span>
                            <span id="tax">£0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <span>Total:</span>
                            <span id="total">£0.00</span>
                        </div>
                    </div>

                    <div class="order-form" id="orderForm" style="display: none;">
                        <h5 class="mb-3">Customer Information</h5>
                        <form id="checkoutForm">
                            <div class="form-group">
                                <label for="customerName">Full Name *</label>
                                <input type="text" class="form-control" id="customerName" required>
                            </div>
                            <div class="form-group">
                                <label for="customerEmail">Email Address *</label>
                                <input type="email" class="form-control" id="customerEmail" required>
                            </div>
                            <div class="form-group">
                                <label for="customerPhone">Phone Number *</label>
                                <input type="tel" class="form-control" id="customerPhone" required>
                            </div>
                            <div class="form-group">
                                <label for="specialInstructions">Special Instructions (Optional)</label>
                                <textarea class="form-control" id="specialInstructions" rows="3" placeholder="Any special requests for your order..."></textarea>
                            </div>

                            <div class="alert alert-info">
                                <strong>Takeaway Order</strong><br>
                                Your order will be prepared for pickup. You'll receive an order confirmation with pickup details.
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="placeOrderBtn">
                                Place Order
                            </button>
                        </form>
                    </div>

                    <button class="btn btn-outline-primary btn-block" id="proceedBtn" onclick="showOrderForm()">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Success Modal -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Order Placed Successfully!</h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fa fa-check-circle fa-3x text-success"></i>
                </div>
                <h4>Thank you for your order!</h4>
                <p>Your order has been received and is being prepared.</p>
                <div class="order-details bg-light p-3 rounded mb-3">
                    <h6>Order Details:</h6>
                    <p><strong>Order ID:</strong> <span id="orderIdDisplay"></span></p>
                    <p><strong>Estimated Pickup Time:</strong> <span id="pickupTime"></span></p>
                </div>
                <p class="text-muted">We'll send you updates via email. You can also track your order using the link below.</p>
                <a href="#" id="trackOrderLink" class="btn btn-primary">Track Your Order</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Continue Shopping</button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];

document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    updateOrderSummary();
});

function loadCartItems() {
    const cartContainer = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');

    if (cart.length === 0) {
        cartContainer.style.display = 'none';
        emptyCart.style.display = 'block';
        document.getElementById('orderForm').style.display = 'none';
        document.getElementById('proceedBtn').style.display = 'none';
        return;
    }

    cartContainer.style.display = 'block';
    emptyCart.style.display = 'none';
    document.getElementById('proceedBtn').style.display = 'block';

    cartContainer.innerHTML = cart.map(item => `
        <div class="cart-item border-bottom py-3">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="${item.image_url || 'images/menu-1.jpg'}" class="img-fluid rounded" alt="${item.name}">
                </div>
                <div class="col-md-4">
                    <h6 class="mb-1">${item.name}</h6>
                    <small class="text-muted">£${item.price.toFixed(2)} each</small>
                    ${item.type === 'alcoholic' ? '<span class="badge badge-warning ml-2">21+</span>' : ''}
                </div>
                <div class="col-md-3">
                    <div class="quantity-controls d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary" onclick="decreaseCartQuantity(${item.id})">-</button>
                        <span class="mx-3">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="increaseCartQuantity(${item.id})">+</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <strong>£${(item.price * item.quantity).toFixed(2)}</strong>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function updateOrderSummary() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const tax = subtotal * 0.05;
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = `£${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `£${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `£${total.toFixed(2)}`;
}

function increaseCartQuantity(itemId) {
    const item = cart.find(item => item.id == itemId);
    if (item) {
        item.quantity++;
        saveCart();
        loadCartItems();
        updateOrderSummary();
    }
}

function decreaseCartQuantity(itemId) {
    const item = cart.find(item => item.id == itemId);
    if (item) {
        item.quantity--;
        if (item.quantity <= 0) {
            removeFromCart(itemId);
        } else {
            saveCart();
            loadCartItems();
            updateOrderSummary();
        }
    }
}

function removeFromCart(itemId) {
    cart = cart.filter(item => item.id != itemId);
    saveCart();
    loadCartItems();
    updateOrderSummary();
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function showOrderForm() {
    document.getElementById('orderForm').style.display = 'block';
    document.getElementById('proceedBtn').style.display = 'none';
}

// Order placement
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const orderData = {
        customer_name: document.getElementById('customerName').value,
        customer_email: document.getElementById('customerEmail').value,
        customer_phone: document.getElementById('customerPhone').value,
        special_instructions: document.getElementById('specialInstructions').value,
        items: cart,
        subtotal: cart.reduce((total, item) => total + (item.price * item.quantity), 0),
        tax: cart.reduce((total, item) => total + (item.price * item.quantity), 0) * 0.05,
        total: cart.reduce((total, item) => total + (item.price * item.quantity), 0) * 1.05,
        order_type: 'takeaway'
    };

    try {
        document.getElementById('placeOrderBtn').disabled = true;
        document.getElementById('placeOrderBtn').innerHTML = 'Processing...';

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
            cart = [];
            saveCart();

            // Show success modal
            document.getElementById('orderIdDisplay').textContent = result.order_id;
            document.getElementById('pickupTime').textContent = result.estimated_pickup;
            document.getElementById('trackOrderLink').href = `order-status.php?order_id=${result.tracking_token}`;

            $('#orderSuccessModal').modal('show');
        } else {
            throw new Error(result.message || 'Failed to place order');
        }
    } catch (error) {
        alert('Error placing order: ' + error.message);
    } finally {
        document.getElementById('placeOrderBtn').disabled = false;
        document.getElementById('placeOrderBtn').innerHTML = 'Place Order';
    }
});
</script>

<?php include 'includes/footer.php'; ?>