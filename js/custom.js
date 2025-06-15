
// Custom JavaScript for Mall Road House

document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Table Reservation Form
    const reservationForms = document.querySelectorAll('.appointment-form');
    
    reservationForms.forEach(form => {
        // Add service type selection
        const firstRow = form.querySelector('.row');
        if (firstRow && !form.querySelector('.service-type-row')) {
            const serviceTypeRow = document.createElement('div');
            serviceTypeRow.className = 'row service-type-row';
            serviceTypeRow.innerHTML = `
                <div class="col-md-12 mb-3">
                    <label class="form-label text-white mb-2">Service Type:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="service_type" id="dine_in" value="dine_in" checked>
                        <label class="btn btn-outline-light" for="dine_in">
                            <i class="fa fa-cutlery"></i> Dine In
                        </label>
                        
                        <input type="radio" class="btn-check" name="service_type" id="takeaway" value="takeaway">
                        <label class="btn btn-outline-light" for="takeaway">
                            <i class="fa fa-shopping-bag"></i> Takeaway
                        </label>
                    </div>
                </div>
            `;
            firstRow.insertAdjacentElement('beforebegin', serviceTypeRow);
        }
        
        // Add special requests field
        const submitButton = form.querySelector('input[type="submit"]');
        if (submitButton && !form.querySelector('.special-requests')) {
            const specialRequestsDiv = document.createElement('div');
            specialRequestsDiv.className = 'col-md-12 special-requests';
            specialRequestsDiv.innerHTML = `
                <div class="form-group">
                    <textarea class="form-control" name="special_requests" placeholder="Special requests or dietary requirements (optional)" rows="3"></textarea>
                </div>
            `;
            submitButton.closest('.col-md-12').insertAdjacentElement('beforebegin', specialRequestsDiv);
        }
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const name = this.querySelector('input[placeholder="Name"]').value;
            const email = this.querySelector('input[placeholder="Email"]').value;
            const phone = this.querySelector('input[placeholder="Phone"]').value;
            const date = this.querySelector('.book_date').value;
            const time = this.querySelector('.book_time').value;
            const guests = this.querySelector('select').value;
            const serviceType = this.querySelector('input[name="service_type"]:checked').value;
            const specialRequests = this.querySelector('textarea[name="special_requests"]').value;

            if (name && email && phone && date && time && guests) {
                // Submit reservation request
                const reservationData = {
                    name: name,
                    email: email,
                    phone: phone,
                    date: date,
                    time: time,
                    guests: guests,
                    request_type: serviceType,
                    special_requests: specialRequests
                };
                
                fetch('api/reservations.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(reservationData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Thank you ${name}! Your ${serviceType.replace('_', ' ')} request has been submitted successfully.\n\nYour reservation ID is: ${data.encoded_id}\n\nPlease save this ID to check your reservation status. You will receive a confirmation email once your request is reviewed.`);
                        
                        // Show status checker info
                        const statusInfo = document.createElement('div');
                        statusInfo.className = 'alert alert-info mt-3';
                        statusInfo.innerHTML = `
                            <h6><i class="fa fa-info-circle"></i> Track Your Reservation</h6>
                            <p>Visit <a href="reservation-status.html" target="_blank">Reservation Status</a> and enter your ID: <strong>${data.encoded_id}</strong></p>
                        `;
                        this.insertAdjacentElement('afterend', statusInfo);
                        
                        // Reset form
                        this.reset();
                        
                        // Remove status info after 10 seconds
                        setTimeout(() => {
                            statusInfo.remove();
                        }, 10000);
                    } else {
                        alert('Failed to submit reservation request. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            } else {
                alert('Please fill in all required fields to complete your reservation request.');
            }
        });
    });
    
    // Update service type behavior
    document.addEventListener('change', function(e) {
        if (e.target.name === 'service_type') {
            const form = e.target.closest('form');
            const guestSelect = form.querySelector('select');
            const timeField = form.querySelector('.book_time');
            
            if (e.target.value === 'takeaway') {
                // For takeaway, guests field becomes quantity
                guestSelect.innerHTML = `
                    <option value="">Quantity</option>
                    <option value="1">1 order</option>
                    <option value="2">2 orders</option>
                    <option value="3">3 orders</option>
                    <option value="4">4 orders</option>
                    <option value="5">5+ orders</option>
                `;
                timeField.placeholder = "Pickup Time";
            } else {
                // For dine-in, restore guests
                guestSelect.innerHTML = `
                    <option value="">Guest</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5+</option>
                `;
                timeField.placeholder = "Time";
            }
        }
    });
    
    // Menu Filter Functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuItems = document.querySelectorAll('.menu-wrap');

    // Add data attributes to menu items for filtering
    if (menuItems.length > 0) {
        menuItems.forEach((item, index) => {
            const heading = item.querySelector('.heading-menu h3');
            if (heading) {
                const menuType = heading.textContent.toLowerCase();
                if (menuType.includes('wine') || menuType.includes('drinks') || menuType.includes('bar')) {
                    item.setAttribute('data-category', 'bar');
                    item.classList.add('menu-item');
                } else {
                    item.setAttribute('data-category', 'restaurant');
                    item.classList.add('menu-item');
                }
            }
        });
    }
});

// Order functionality placeholder
function handleOrder(button) {
    const menuItem = button.closest('.menu-wrap');
    const menuType = menuItem.querySelector('.heading-menu h3').textContent;
    
    alert(`Order functionality for ${menuType} will be implemented soon! This will include:\n\n• Delivery to specific zip codes\n• Order tracking\n• Payment integration\n• Admin panel for order management`);
}

function handleTakeaway(button) {
    const menuItem = button.closest('.menu-wrap');
    const menuType = menuItem.querySelector('.heading-menu h3').textContent;
    
    alert(`Takeaway order for ${menuType} will be ready in 15-20 minutes. This feature will include:\n\n• Order preparation tracking\n• SMS notifications\n• Pickup time estimation`);
}
