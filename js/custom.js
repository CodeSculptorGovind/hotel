// Custom JavaScript for Mall Road House

document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Table Reservation Form
    const reservationForms = document.querySelectorAll('.appointment-form');

    reservationForms.forEach(form => {
        // Remove service type selection - only dine-in reservations

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
            const serviceType = 'dine_in'; // Only dine-in reservations
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

    // Reservation form is now only for dine-in

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

// Sample menu data
const menuData = {
    breakfast: [
        { name: "Classic Pancakes", description: "Fluffy pancakes with maple syrup and butter", price: 12.99, image: "images/breakfast-1.jpg" },
        { name: "English Breakfast", description: "Eggs, bacon, sausages, beans, and toast", price: 15.99, image: "images/breakfast-2.jpg" },
        { name: "Avocado Toast", description: "Sourdough toast with avocado, cherry tomatoes", price: 11.99, image: "images/breakfast-3.jpg" },
        { name: "Breakfast Burrito", description: "Scrambled eggs, cheese, peppers in tortilla", price: 13.99, image: "images/breakfast-4.jpg" }
    ],
    lunch: [
        { name: "Grilled Chicken Salad", description: "Mixed greens, grilled chicken, vegetables", price: 16.99, image: "images/lunch-1.jpg" },
        { name: "Beef Burger", description: "Juicy beef patty with cheese and fries", price: 18.99, image: "images/lunch-2.jpg" },
        { name: "Caesar Salad", description: "Romaine lettuce, parmesan, croutons", price: 14.99, image: "images/lunch-3.jpg" },
        { name: "Fish & Chips", description: "Beer battered cod with chunky chips", price: 19.99, image: "images/lunch-4.jpg" }
    ],
    dinner: [
        { name: "Grilled Salmon", description: "Atlantic salmon with seasonal vegetables", price: 26.99, image: "images/dinner-1.jpg" },
        { name: "Ribeye Steak", description: "Prime ribeye with mashed potatoes", price: 32.99, image: "images/dinner-2.jpg" },
        { name: "Pasta Carbonara", description: "Creamy pasta with bacon and parmesan", price: 22.99, image: "images/dinner-3.jpg" },
        { name: "Lamb Chops", description: "Herb-crusted lamb with mint sauce", price: 28.99, image: "images/dinner-4.jpg" }
    ],
    desserts: [
        { name: "Chocolate Cake", description: "Rich chocolate layer cake with berries", price: 8.99, image: "images/dessert-1.jpg" },
        { name: "Tiramisu", description: "Classic Italian coffee-flavored dessert", price: 9.99, image: "images/dessert-2.jpg" },
        { name: "Ice Cream Sundae", description: "Vanilla ice cream with chocolate sauce", price: 7.99, image: "images/dessert-3.jpg" },
        { name: "Cheesecake", description: "New York style cheesecake with fruit", price: 8.99, image: "images/dessert-4.jpg" }
    ],
    liquor: [
        { name: "Premium Whiskey", description: "Single malt Scotch whiskey, aged 12 years", price: 18.99, image: "images/wine-1.jpg" },
        { name: "Red Wine", description: "Cabernet Sauvignon, full-bodied", price: 12.99, image: "images/wine-2.jpg" },
        { name: "Craft Beer", description: "Local IPA with citrus notes", price: 6.99, image: "images/drink-1.jpg" },
        { name: "Signature Cocktail", description: "House special mojito with fresh mint", price: 9.99, image: "images/drink-2.jpg" },
        { name: "White Wine", description: "Chardonnay, crisp and refreshing", price: 11.99, image: "images/wine-3.jpg" },
        { name: "Premium Vodka", description: "Top-shelf vodka, smooth finish", price: 15.99, image: "images/wine-4.jpg" }
    ]
};

// Load menu items
function loadMenuItems(filter = 'all') {
    const menuContainer = document.getElementById('menuPreview');
    if (!menuContainer) return;

    // Fetch menu items from API
    fetch('api/menu.php')
        .then(response => response.json())
        .then(data => {
            displayMenuItems(data, filter);
        })
        .catch(error => {
            console.error('Error loading menu:', error);
            // Show fallback message
            menuContainer.innerHTML = '<div class="col-12 text-center"><p>Unable to load menu items. Please try again later.</p></div>';
        });
}

function displayMenuItems(items, filter = 'all') {
    const menuContainer = document.getElementById('menuPreview');
    if (!menuContainer) return;

    let filteredItems = items;
    if (filter !== 'all') {
        filteredItems = items.filter(item => item.category === filter);
    }

    if (filteredItems.length === 0) {
        menuContainer.innerHTML = '<div class="col-12 text-center"><p>No menu items found.</p></div>';
        return;
    }

    const menuHTML = filteredItems.slice(0, 6).map(item => `
        <div class="col-md-6 col-lg-4 menu-item" data-category="${item.category}">
            <div class="menu-wrap">
                <div class="heading-menu text-center ftco-animate">
                    <h3>${item.name}</h3>
                </div>
                <div class="menus d-flex ftco-animate">
                    <div class="menu-img img" style="background-image: url(${item.image_url || 'images/menu-1.jpg'});"></div>
                    <div class="text">
                        <div class="d-flex">
                            <div class="one-half">
                                <h3>${item.name}</h3>
                            </div>
                            <div class="one-forth">
                                <span class="price">$${parseFloat(item.price).toFixed(2)}</span>
                            </div>
                        </div>
                        <p>${item.description || 'Delicious menu item'}</p>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    menuContainer.innerHTML = menuHTML;
}

// Load combo items
function loadComboItems() {
    fetch('api/menu.php?type=combos')
        .then(response => response.json())
        .then(data => {
            // Display combos if needed
            console.log('Combos loaded:', data);
        })
        .catch(error => {
            console.error('Error loading combos:', error);
        });
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Load menu items if on menu page
    loadMenuItems();

    // Filter button functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('filter-btn')) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');

            // Filter items
            const filter = e.target.getAttribute('data-filter');
            loadMenuItems(filter);
        }
    });
});

// Order functionality
function addToOrder(itemName, price) {
    alert(`${itemName} ($${price}) added to your order!\n\nTakeaway ordering system coming soon. Features will include:\n\n• Order customization\n• Pickup time selection\n• Payment processing\n• Order tracking\n• SMS notifications`);
}

// Remove old order functions
function handleOrder(button) {
    // This function is no longer needed
}

function handleTakeaway(button) {
    // This function is no longer needed
}

// Status form setup
function setupStatusForm() {
    const form = document.getElementById('statusForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const encodedId = document.getElementById('encodedId').value;

        if (!encodedId) {
            alert('Please enter your reservation ID');
            return;
        }

        checkReservationStatus(encodedId);
    });
}

// Quick reservation form setup for home page
function setupQuickReservationForm() {
    const form = document.getElementById('quickReservationForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            name: document.getElementById('quickName').value,
            email: document.getElementById('quickEmail').value,
            phone: document.getElementById('quickPhone').value,
            date: document.getElementById('quickDate').value,
            time: document.getElementById('quickTime').value,
            guests: document.getElementById('quickGuests').value,
            request_type: 'dine_in'
        };

        // Validate form
        if (!formData.name || !formData.email || !formData.phone || !formData.date || !formData.time || !formData.guests) {
            alert('Please fill in all fields');
            return;
        }

        // Submit reservation
        submitReservation(formData, form);
    });
}

// Load menu preview for home page
function loadMenuPreview() {
    fetch('api/menu.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayMenuPreview(data.menu);
        }
    })
    .catch(error => {
        console.error('Error loading menu preview:', error);
    });
}

// Display menu preview
function displayMenuPreview(menuItems) {
    const container = document.getElementById('menuPreview');
    if (!container) return;

    // Group items by category and show max 3 items per category
    const categories = ['breakfast', 'lunch', 'dinner', 'desserts', 'wine', 'drinks'];
    let html = '';

    categories.forEach(category => {
        const categoryItems = menuItems.filter(item => item.category === category && item.is_available).slice(0, 3);

        if (categoryItems.length > 0) {
            html += `
                <div class="col-md-6 col-lg-4">
                    <div class="menu-wrap">
                        <div class="heading-menu text-center ftco-animate">
                            <h3>${category.charAt(0).toUpperCase() + category.slice(1)}</h3>
                        </div>
            `;

            categoryItems.forEach((item, index) => {
                const borderClass = index === categoryItems.length - 1 ? 'border-bottom-0' : '';
                html += `
                    <div class="menus ${borderClass} d-flex ftco-animate">
                        <div class="menu-img img" style="background-image: url(${item.image_url || 'images/menu-1.jpg'});"></div>
                        <div class="text">
                            <div class="d-flex">
                                <div class="one-half">
                                    <h3>${item.name}</h3>
                                </div>
                                <div class="one-forth">
                                    <span class="price">₹${item.price}</span>
                                </div>
                            </div>
                            <p>${item.description || 'Delicious and freshly prepared'}</p>
                        </div>
                    </div>
                `;
            });

            html += `
                        <span class="flat flaticon-bread" style="left: 0;"></span>
                        <span class="flat flaticon-breakfast" style="right: 0;"></span>
                    </div>
                </div>
            `;
        }
    });

    container.innerHTML = html;
}

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu when clicking on a link
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (navbarCollapse.classList.contains('show')) {
                document.querySelector('.navbar-toggler').click();
            }
        });
    });

    // Touch/swipe improvements for mobile
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
    }

    // Prevent iOS zoom on form inputs
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const viewportMeta = document.querySelector('meta[name="viewport"]');
        if (viewportMeta) {
            viewportMeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no';
        }
    }

    // Smooth scroll for anchor links on mobile
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Load menu items on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('menuContainer')) {
        loadMenuItems();
    }

    if (document.getElementById('reservationForm')) {
        setupReservationForm();
    }

    if (document.getElementById('takeawayForm')) {
        setupTakeawayForm();
    }

    if (document.getElementById('statusForm')) {
        setupStatusForm();
    }

    if (document.getElementById('quickReservationForm')) {
        setupQuickReservationForm();
    }

    if (document.getElementById('menuPreview')) {
        loadMenuPreview();
    }
});