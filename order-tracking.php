
<?php include 'includes/header.php'; ?>

<style>
.tracking-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px 15px;
}

.tracking-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.order-progress {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px 20px;
    margin-bottom: 20px;
}

.progress-timeline {
    position: relative;
    padding-left: 30px;
}

.progress-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.progress-item {
    position: relative;
    padding-bottom: 30px;
    margin-bottom: 20px;
}

.progress-item:last-child {
    padding-bottom: 0;
    margin-bottom: 0;
}

.progress-icon {
    position: absolute;
    left: -22px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: white;
}

.progress-item.completed .progress-icon {
    background: #4CAF50;
}

.progress-item.current .progress-icon {
    background: #e52b34;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.progress-content h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
}

.progress-content p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.progress-time {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

.estimated-time {
    background: #e8f5e8;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    margin-bottom: 20px;
}

.contact-info {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.contact-btn {
    background: #e52b34;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    margin: 5px;
    text-decoration: none;
    display: inline-block;
}

.refresh-btn {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 20px;
}
</style>

<div class="tracking-container">
    <div class="tracking-header">
        <h2>🚚 Track Your Order</h2>
        <p id="orderIdDisplay">Loading...</p>
    </div>

    <div class="estimated-time" id="estimatedTime">
        <h4>⏰ Estimated Delivery Time</h4>
        <p id="deliveryTime">Calculating...</p>
    </div>

    <div class="order-progress">
        <h3>Order Progress</h3>
        <button class="refresh-btn" onclick="refreshOrder()">🔄 Refresh Status</button>
        
        <div class="progress-timeline" id="progressTimeline">
            <div class="loading">Loading order status...</div>
        </div>
    </div>

    <div class="contact-info">
        <h4>Need Help?</h4>
        <p>Contact us if you have any questions about your order</p>
        <a href="tel:+1234567890" class="contact-btn">📞 Call Restaurant</a>
        <a href="contact.php" class="contact-btn">💬 Contact Us</a>
    </div>
</div>

<script>
let orderId = null;
let refreshInterval = null;

async function loadOrderTracking() {
    const urlParams = new URLSearchParams(window.location.search);
    orderId = urlParams.get('order_id');
    
    if (!orderId) {
        document.getElementById('progressTimeline').innerHTML = '<div>Order ID not found</div>';
        return;
    }
    
    document.getElementById('orderIdDisplay').textContent = `Order #${orderId}`;
    
    try {
        const response = await fetch(`api/orders.php?order_id=${orderId}`);
        const result = await response.json();
        
        if (result.success) {
            displayOrderProgress(result.order);
            updateEstimatedTime(result.order);
        } else {
            document.getElementById('progressTimeline').innerHTML = '<div>Order not found</div>';
        }
    } catch (error) {
        console.error('Error loading order:', error);
        document.getElementById('progressTimeline').innerHTML = '<div>Error loading order status</div>';
    }
}

function displayOrderProgress(order) {
    const status = order.order_status;
    const createdAt = new Date(order.created_at);
    
    const progressSteps = [
        {
            id: 'pending',
            title: 'Order Received',
            description: 'Your order has been confirmed and sent to the kitchen',
            icon: '📝',
            time: formatTime(createdAt)
        },
        {
            id: 'preparing',
            title: 'Preparing Your Food',
            description: 'Our chefs are carefully preparing your delicious meal',
            icon: '👨‍🍳',
            time: status === 'preparing' || status === 'ready' || status === 'out_for_delivery' || status === 'delivered' ? formatTime(addMinutes(createdAt, 5)) : null
        },
        {
            id: 'ready',
            title: 'Ready for Pickup/Delivery',
            description: 'Your order is ready and packed with care',
            icon: '📦',
            time: status === 'ready' || status === 'out_for_delivery' || status === 'delivered' ? formatTime(addMinutes(createdAt, 20)) : null
        },
        {
            id: 'out_for_delivery',
            title: 'Out for Delivery',
            description: 'Your order is on its way to you',
            icon: '🚚',
            time: status === 'out_for_delivery' || status === 'delivered' ? formatTime(addMinutes(createdAt, 25)) : null
        },
        {
            id: 'delivered',
            title: 'Delivered',
            description: 'Enjoy your delicious meal!',
            icon: '✅',
            time: status === 'delivered' ? formatTime(addMinutes(createdAt, 40)) : null
        }
    ];
    
    const timeline = progressSteps.map(step => {
        let stepClass = '';
        if (step.id === status) {
            stepClass = 'current';
        } else if (isStepCompleted(step.id, status)) {
            stepClass = 'completed';
        }
        
        return `
            <div class="progress-item ${stepClass}">
                <div class="progress-icon">${step.icon}</div>
                <div class="progress-content">
                    <h4>${step.title}</h4>
                    <p>${step.description}</p>
                    ${step.time ? `<div class="progress-time">${step.time}</div>` : ''}
                </div>
            </div>
        `;
    }).join('');
    
    document.getElementById('progressTimeline').innerHTML = timeline;
}

function isStepCompleted(stepId, currentStatus) {
    const stepOrder = ['pending', 'preparing', 'ready', 'out_for_delivery', 'delivered'];
    const stepIndex = stepOrder.indexOf(stepId);
    const currentIndex = stepOrder.indexOf(currentStatus);
    return stepIndex < currentIndex;
}

function updateEstimatedTime(order) {
    const status = order.order_status;
    const createdAt = new Date(order.created_at);
    const now = new Date();
    
    let estimatedMinutes = 40; // Default total time
    
    switch (status) {
        case 'pending':
            estimatedMinutes = 40;
            break;
        case 'preparing':
            estimatedMinutes = 30;
            break;
        case 'ready':
            estimatedMinutes = 15;
            break;
        case 'out_for_delivery':
            estimatedMinutes = 10;
            break;
        case 'delivered':
            document.getElementById('deliveryTime').textContent = 'Your order has been delivered!';
            document.getElementById('estimatedTime').style.background = '#e8f5e8';
            return;
    }
    
    const estimatedDelivery = new Date(createdAt.getTime() + estimatedMinutes * 60000);
    const remainingTime = Math.max(0, Math.floor((estimatedDelivery - now) / 60000));
    
    if (remainingTime > 0) {
        document.getElementById('deliveryTime').textContent = `${remainingTime} minutes remaining`;
    } else {
        document.getElementById('deliveryTime').textContent = 'Should arrive any moment now!';
    }
}

function formatTime(date) {
    return date.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
}

function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes * 60000);
}

function refreshOrder() {
    loadOrderTracking();
}

// Auto-refresh every 30 seconds
function startAutoRefresh() {
    refreshInterval = setInterval(refreshOrder, 30000);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

// Load order tracking on page load
document.addEventListener('DOMContentLoaded', function() {
    loadOrderTracking();
    startAutoRefresh();
});

// Stop auto-refresh when page is not visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
