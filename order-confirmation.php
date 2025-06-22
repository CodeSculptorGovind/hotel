
<?php include 'includes/header.php'; ?>

<style>
.confirmation-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px 15px;
    text-align: center;
}

.confirmation-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 40px 20px;
    margin-bottom: 20px;
}

.success-icon {
    font-size: 64px;
    color: #4CAF50;
    margin-bottom: 20px;
}

.order-details {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 16px;
}

.detail-row.total {
    font-weight: bold;
    font-size: 18px;
    border-top: 2px solid #e0e0e0;
    padding-top: 10px;
    margin-top: 15px;
}

.order-items {
    margin: 20px 0;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e0e0e0;
}

.order-item:last-child {
    border-bottom: none;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 30px;
}

.btn-primary {
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 15px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    display: block;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
    text-decoration: none;
    display: block;
}

.status-tracker {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.status-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
}

.status-step {
    flex: 1;
    text-align: center;
    position: relative;
}

.status-step::after {
    content: '';
    position: absolute;
    top: 15px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #e0e0e0;
    z-index: 1;
}

.status-step:last-child::after {
    display: none;
}

.status-step.active::after {
    background: #4CAF50;
}

.status-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    color: white;
    position: relative;
    z-index: 2;
}

.status-step.active .status-circle {
    background: #4CAF50;
}

.status-label {
    font-size: 12px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .status-steps {
        flex-direction: column;
        gap: 20px;
    }
    
    .status-step::after {
        display: none;
    }
}
</style>

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="success-icon">✅</div>
        <h2>Order Confirmed!</h2>
        <p>Thank you for your order. We'll prepare it with care and deliver it fresh.</p>
        
        <div class="order-details" id="orderDetails">
            <div class="loading">Loading order details...</div>
        </div>
        
        <div class="action-buttons">
            <a href="order-tracking.php" class="btn-primary" id="trackOrderBtn" style="display: none;">
                Track Your Order
            </a>
            <a href="order.php" class="btn-secondary">Order Again</a>
            <a href="index.php" class="btn-secondary">Back to Home</a>
        </div>
    </div>
    
    <div class="status-tracker">
        <h3>Order Status</h3>
        <div class="status-steps">
            <div class="status-step active">
                <div class="status-circle">1</div>
                <div class="status-label">Order Placed</div>
            </div>
            <div class="status-step">
                <div class="status-circle">2</div>
                <div class="status-label">Preparing</div>
            </div>
            <div class="status-step">
                <div class="status-circle">3</div>
                <div class="status-label">On the Way</div>
            </div>
            <div class="status-step">
                <div class="status-circle">4</div>
                <div class="status-label">Delivered</div>
            </div>
        </div>
        <p><strong>Estimated Delivery:</strong> <span id="estimatedTime">30-45 minutes</span></p>
    </div>
</div>

<script>
async function loadOrderDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    
    if (!orderId) {
        document.getElementById('orderDetails').innerHTML = '<div>Order ID not found</div>';
        return;
    }
    
    try {
        const response = await fetch(`api/orders.php?order_id=${orderId}`);
        const result = await response.json();
        
        if (result.success) {
            displayOrderDetails(result.order);
            document.getElementById('trackOrderBtn').style.display = 'block';
            document.getElementById('trackOrderBtn').href = `order-tracking.php?order_id=${orderId}`;
        } else {
            document.getElementById('orderDetails').innerHTML = '<div>Order not found</div>';
        }
    } catch (error) {
        console.error('Error loading order:', error);
        document.getElementById('orderDetails').innerHTML = '<div>Error loading order details</div>';
    }
}

function displayOrderDetails(order) {
    const orderItems = JSON.parse(order.order_items || '[]');
    
    document.getElementById('orderDetails').innerHTML = `
        <div class="detail-row">
            <span>Order ID:</span>
            <span><strong>${order.order_id}</strong></span>
        </div>
        <div class="detail-row">
            <span>Customer:</span>
            <span>${order.customer_name}</span>
        </div>
        <div class="detail-row">
            <span>Phone:</span>
            <span>${order.customer_phone}</span>
        </div>
        <div class="detail-row">
            <span>Address:</span>
            <span>${order.customer_address}</span>
        </div>
        
        <div class="order-items">
            <h4>Order Items:</h4>
            ${orderItems.map(item => `
                <div class="order-item">
                    <span>${item.name} x${item.quantity}</span>
                    <span>₹${item.price * item.quantity}</span>
                </div>
            `).join('')}
        </div>
        
        <div class="detail-row">
            <span>Subtotal:</span>
            <span>₹${order.subtotal}</span>
        </div>
        <div class="detail-row">
            <span>Delivery Fee:</span>
            <span>₹${order.delivery_fee}</span>
        </div>
        <div class="detail-row total">
            <span>Total:</span>
            <span>₹${order.total}</span>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', loadOrderDetails);
</script>

<?php include 'includes/footer.php'; ?>
