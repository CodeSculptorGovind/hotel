
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Order Status</span></p>
                <h1 class="mb-0 bread">Track Your Order</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="loadingMessage" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Loading order details...</p>
                </div>
                
                <div id="orderNotFound" class="text-center" style="display: none;">
                    <div class="alert alert-warning">
                        <h4>Order Not Found</h4>
                        <p>The order could not be found or may have expired. Order tracking links are valid for 24 hours.</p>
                        <a href="order.php" class="btn btn-primary">Place New Order</a>
                    </div>
                </div>
                
                <div id="orderDetails" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Order Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Order Information</h6>
                                    <p><strong>Order ID:</strong> <span id="orderId"></span></p>
                                    <p><strong>Status:</strong> <span id="orderStatus" class="badge"></span></p>
                                    <p><strong>Order Type:</strong> <span id="orderType"></span></p>
                                    <p><strong>Order Date:</strong> <span id="orderDate"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Customer Information</h6>
                                    <p><strong>Name:</strong> <span id="customerName"></span></p>
                                    <p><strong>Email:</strong> <span id="customerEmail"></span></p>
                                    <p><strong>Phone:</strong> <span id="customerPhone"></span></p>
                                    <p><strong>Estimated Pickup:</strong> <span id="estimatedPickup"></span></p>
                                </div>
                            </div>
                            
                            <div id="specialInstructions" style="display: none;">
                                <h6>Special Instructions</h6>
                                <p id="instructionsText" class="text-muted"></p>
                            </div>
                            
                            <h6>Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItems">
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="order-summary mt-3">
                                <div class="row">
                                    <div class="col-md-6 ml-auto">
                                        <div class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <span id="subtotalAmount"></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Tax:</span>
                                            <span id="taxAmount"></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between font-weight-bold">
                                            <span>Total:</span>
                                            <span id="totalAmount"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="order.php" class="btn btn-primary">Order Again</a>
                        <a href="contact.php" class="btn btn-outline-secondary ml-2">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    
    if (!orderId) {
        showOrderNotFound();
        return;
    }
    
    loadOrderDetails(orderId);
});

async function loadOrderDetails(trackingToken) {
    try {
        const response = await fetch(`api/orders.php?tracking_token=${trackingToken}`);
        const data = await response.json();
        
        if (data.success === false) {
            throw new Error(data.message);
        }
        
        displayOrderDetails(data);
    } catch (error) {
        console.error('Error loading order:', error);
        showOrderNotFound();
    }
}

function displayOrderDetails(order) {
    document.getElementById('loadingMessage').style.display = 'none';
    document.getElementById('orderDetails').style.display = 'block';
    
    // Order information
    document.getElementById('orderId').textContent = order.order_id;
    document.getElementById('orderStatus').textContent = order.status.toUpperCase();
    document.getElementById('orderStatus').className = `badge badge-${getStatusColor(order.status)}`;
    document.getElementById('orderType').textContent = order.order_type.toUpperCase();
    document.getElementById('orderDate').textContent = new Date(order.created_at).toLocaleString();
    
    // Customer information
    document.getElementById('customerName').textContent = order.customer_name;
    document.getElementById('customerEmail').textContent = order.customer_email;
    document.getElementById('customerPhone').textContent = order.customer_phone;
    document.getElementById('estimatedPickup').textContent = new Date(order.estimated_pickup).toLocaleString();
    
    // Special instructions
    if (order.special_instructions) {
        document.getElementById('specialInstructions').style.display = 'block';
        document.getElementById('instructionsText').textContent = order.special_instructions;
    }
    
    // Order items
    const itemsContainer = document.getElementById('orderItems');
    itemsContainer.innerHTML = order.items.map(item => `
        <tr>
            <td>${item.name}</td>
            <td>£${parseFloat(item.price).toFixed(2)}</td>
            <td>${item.quantity}</td>
            <td>£${parseFloat(item.subtotal).toFixed(2)}</td>
        </tr>
    `).join('');
    
    // Order summary
    document.getElementById('subtotalAmount').textContent = `£${parseFloat(order.subtotal).toFixed(2)}`;
    document.getElementById('taxAmount').textContent = `£${parseFloat(order.tax).toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `£${parseFloat(order.total).toFixed(2)}`;
}

function showOrderNotFound() {
    document.getElementById('loadingMessage').style.display = 'none';
    document.getElementById('orderNotFound').style.display = 'block';
}

function getStatusColor(status) {
    switch (status.toLowerCase()) {
        case 'pending': return 'warning';
        case 'preparing': return 'info';
        case 'ready': return 'success';
        case 'completed': return 'primary';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
