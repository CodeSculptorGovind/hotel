
// Custom JavaScript for Mall Road House

document.addEventListener('DOMContentLoaded', function() {
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

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter menu items
            menuItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    item.classList.remove('hidden');
                } else {
                    item.style.display = 'none';
                    item.classList.add('hidden');
                }
            });
        });
    });

    // Table Reservation Form Enhancement
    const reservationForm = document.querySelector('.appointment-form');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const name = this.querySelector('input[placeholder="Name"]').value;
            const email = this.querySelector('input[placeholder="Email"]').value;
            const phone = this.querySelector('input[placeholder="Phone"]').value;
            const date = this.querySelector('.book_date').value;
            const time = this.querySelector('.book_time').value;
            const guests = this.querySelector('select').value;

            if (name && email && phone && date && time && guests) {
                // Simulate reservation confirmation
                alert(`Thank you ${name}! Your table for ${guests} guests has been reserved for ${date} at ${time}. You will receive a confirmation email at ${email}. Please arrive 10 minutes early. Reservation will be held for 30 minutes past your scheduled time.`);
                
                // Reset form
                this.reset();
            } else {
                alert('Please fill in all required fields to complete your reservation.');
            }
        });
    }

    // Order Now Functionality (placeholder)
    function addOrderButtons() {
        const menuSections = document.querySelectorAll('.menu-wrap');
        menuSections.forEach(section => {
            if (!section.querySelector('.order-section')) {
                const orderSection = document.createElement('div');
                orderSection.className = 'order-section text-center mt-3';
                orderSection.innerHTML = `
                    <button class="btn order-btn" onclick="handleOrder(this)">
                        Order for Delivery
                    </button>
                    <button class="btn btn-outline-primary ml-2" onclick="handleTakeaway(this)">
                        Takeaway
                    </button>
                `;
                section.appendChild(orderSection);
            }
        });
    }

    // Call the function to add order buttons
    addOrderButtons();
});

// Order handling functions
function handleOrder(button) {
    const menuItem = button.closest('.menu-wrap');
    const menuType = menuItem.querySelector('.heading-menu h3').textContent;
    
    // Simulate order process
    alert(`Order functionality for ${menuType} will be implemented soon! This will include:\n\n• Delivery to specific zip codes\n• Order tracking\n• Payment integration\n• Admin panel for order management`);
}

function handleTakeaway(button) {
    const menuItem = button.closest('.menu-wrap');
    const menuType = menuItem.querySelector('.heading-menu h3').textContent;
    
    // Simulate takeaway process
    alert(`Takeaway order for ${menuType} will be ready in 15-20 minutes. This feature will include:\n\n• Order preparation tracking\n• SMS notifications\n• Pickup time estimation`);
}

// Email notification for table reservations
function sendReservationEmail(reservationData) {
    // This would integrate with an email service
    console.log('Reservation email would be sent with data:', reservationData);
}
