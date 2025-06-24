
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Menu</span></p>
                <h1 class="mb-0 bread">Our Menu</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 heading-section text-center ftco-animate mb-5">
                <h2 class="mb-4">Our Delicious Menu</h2>
                <p>Browse our delicious menu items across different categories.</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="search-container">
                    <input type="text" class="form-control" placeholder="Search for dishes..." id="searchInput" style="border-radius: 25px; padding: 12px 20px;">
                </div>
            </div>
        </div>

        <div class="row d-md-flex justify-content-center">
            <div class="col-lg-12 ftco-animate p-md-5">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-all-tab" data-toggle="pill" href="#v-pills-all" role="tab" aria-controls="v-pills-all" aria-selected="true">All Items</a>
                            <a class="nav-link" id="v-pills-breakfast-tab" data-toggle="pill" href="#v-pills-breakfast" role="tab" aria-controls="v-pills-breakfast" aria-selected="false">Breakfast</a>
                            <a class="nav-link" id="v-pills-lunch-tab" data-toggle="pill" href="#v-pills-lunch" role="tab" aria-controls="v-pills-lunch" aria-selected="false">Lunch</a>
                            <a class="nav-link" id="v-pills-dinner-tab" data-toggle="pill" href="#v-pills-dinner" role="tab" aria-controls="v-pills-dinner" aria-selected="false">Dinner</a>
                            <a class="nav-link" id="v-pills-desserts-tab" data-toggle="pill" href="#v-pills-desserts" role="tab" aria-controls="v-pills-desserts" aria-selected="false">Desserts</a>
                            <a class="nav-link" id="v-pills-wine-tab" data-toggle="pill" href="#v-pills-wine" role="tab" aria-controls="v-pills-wine" aria-selected="false">Wine</a>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex align-items-center">
                        <div class="tab-content" id="v-pills-tabContent">

                            <!-- All Items Tab -->
                            <div class="tab-pane fade show active" id="v-pills-all" role="tabpanel" aria-labelledby="v-pills-all-tab">
                                <div class="row" id="all-menu">
                                    <!-- Dummy items will be loaded here -->
                                </div>
                            </div>

                            <!-- Breakfast Tab -->
                            <div class="tab-pane fade" id="v-pills-breakfast" role="tabpanel" aria-labelledby="v-pills-breakfast-tab">
                                <div class="row" id="breakfast-menu">
                                    <!-- Breakfast items will be loaded here -->
                                </div>
                            </div>

                            <!-- Lunch Tab -->
                            <div class="tab-pane fade" id="v-pills-lunch" role="tabpanel" aria-labelledby="v-pills-lunch-tab">
                                <div class="row" id="lunch-menu">
                                    <!-- Lunch items will be loaded here -->
                                </div>
                            </div>

                            <!-- Dinner Tab -->
                            <div class="tab-pane fade" id="v-pills-dinner" role="tabpanel" aria-labelledby="v-pills-dinner-tab">
                                <div class="row" id="dinner-menu">
                                    <!-- Dinner items will be loaded here -->
                                </div>
                            </div>

                            <!-- Desserts Tab -->
                            <div class="tab-pane fade" id="v-pills-desserts" role="tabpanel" aria-labelledby="v-pills-desserts-tab">
                                <div class="row" id="desserts-menu">
                                    <!-- Desserts items will be loaded here -->
                                </div>
                            </div>

                            <!-- Wine Tab -->
                            <div class="tab-pane fade" id="v-pills-wine" role="tabpanel" aria-labelledby="v-pills-wine-tab">
                                <div class="row" id="wine-menu">
                                    <!-- Wine items will be loaded here -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
let menuItems = [];
let searchTimeout;

// Dummy menu data - 6 items per category
const dummyMenuData = [
    // Breakfast Items
    {
        id: 1,
        name: "Classic Pancakes",
        description: "Fluffy pancakes with maple syrup and butter",
        price: "12",
        category: "breakfast",
        image_url: "images/breakfast-1.jpg"
    },
    {
        id: 2,
        name: "English Breakfast",
        description: "Eggs, bacon, sausages, beans, and toast",
        price: "15.99",
        category: "breakfast",
        image_url: "images/breakfast-2.jpg"
    },
    {
        id: 3,
        name: "Avocado Toast",
        description: "Sourdough toast with avocado and cherry tomatoes",
        price: "11.99",
        category: "breakfast",
        image_url: "images/breakfast-3.jpg"
    },
    {
        id: 4,
        name: "Breakfast Burrito",
        description: "Scrambled eggs, cheese, peppers in tortilla",
        price: "13.99",
        category: "breakfast",
        image_url: "images/breakfast-4.jpg"
    },
    {
        id: 5,
        name: "French Toast",
        description: "Golden brioche with cinnamon and vanilla",
        price: "10.99",
        category: "breakfast",
        image_url: "images/breakfast-5.jpg"
    },
    {
        id: 6,
        name: "Eggs Benedict",
        description: "Poached eggs with hollandaise sauce",
        price: "14.99",
        category: "breakfast",
        image_url: "images/breakfast-6.jpg"
    },
    
    // Lunch Items
    {
        id: 7,
        name: "Grilled Chicken",
        description: "Perfectly grilled chicken breast with herbs",
        price: "18.99",
        category: "lunch",
        image_url: "images/lunch-1.jpg"
    },
    {
        id: 8,
        name: "Caesar Salad",
        description: "Fresh romaine lettuce with caesar dressing",
        price: "12.99",
        category: "lunch",
        image_url: "images/lunch-2.jpg"
    },
    {
        id: 9,
        name: "Beef Burger",
        description: "Juicy beef patty with lettuce and tomato",
        price: "15.99",
        category: "lunch",
        image_url: "images/lunch-3.jpg"
    },
    {
        id: 10,
        name: "Fish & Chips",
        description: "Beer battered cod with chunky chips",
        price: "19.99",
        category: "lunch",
        image_url: "images/lunch-4.jpg"
    },
    {
        id: 11,
        name: "Chicken Wrap",
        description: "Grilled chicken with fresh vegetables",
        price: "13.99",
        category: "lunch",
        image_url: "images/lunch-5.jpg"
    },
    {
        id: 12,
        name: "Pasta Salad",
        description: "Fresh pasta with seasonal vegetables",
        price: "11.99",
        category: "lunch",
        image_url: "images/lunch-6.jpg"
    },
    
    // Dinner Items
    {
        id: 13,
        name: "Ribeye Steak",
        description: "Premium ribeye steak grilled to perfection",
        price: "32.99",
        category: "dinner",
        image_url: "images/dinner-1.jpg"
    },
    {
        id: 14,
        name: "Grilled Salmon",
        description: "Atlantic salmon with seasonal vegetables",
        price: "26.99",
        category: "dinner",
        image_url: "images/dinner-2.jpg"
    },
    {
        id: 15,
        name: "Pasta Carbonara",
        description: "Creamy pasta with bacon and eggs",
        price: "18.99",
        category: "dinner",
        image_url: "images/dinner-3.jpg"
    },
    {
        id: 16,
        name: "Lobster Thermidor",
        description: "Fresh lobster with creamy sauce",
        price: "39.99",
        category: "dinner",
        image_url: "images/dinner-4.jpg"
    },
    {
        id: 17,
        name: "Lamb Chops",
        description: "Herb-crusted lamb with mint sauce",
        price: "28.99",
        category: "dinner",
        image_url: "images/dinner-5.jpg"
    },
    {
        id: 18,
        name: "Duck Confit",
        description: "Traditional French duck with orange glaze",
        price: "24.99",
        category: "dinner",
        image_url: "images/dinner-6.jpg"
    },
    
    // Desserts
    {
        id: 19,
        name: "Chocolate Cake",
        description: "Rich chocolate cake with vanilla ice cream",
        price: "8.99",
        category: "desserts",
        image_url: "images/dessert-1.jpg"
    },
    {
        id: 20,
        name: "Tiramisu",
        description: "Classic Italian coffee-flavored dessert",
        price: "9.99",
        category: "desserts",
        image_url: "images/dessert-2.jpg"
    },
    {
        id: 21,
        name: "Cheesecake",
        description: "New York style cheesecake with fruit",
        price: "8.99",
        category: "desserts",
        image_url: "images/dessert-3.jpg"
    },
    {
        id: 22,
        name: "Ice Cream Sundae",
        description: "Vanilla ice cream with chocolate sauce",
        price: "7.99",
        category: "desserts",
        image_url: "images/dessert-4.jpg"
    },
    {
        id: 23,
        name: "Crème Brûlée",
        description: "Vanilla custard with caramelized sugar",
        price: "10.99",
        category: "desserts",
        image_url: "images/dessert-5.jpg"
    },
    {
        id: 24,
        name: "Apple Pie",
        description: "Traditional apple pie with cinnamon",
        price: "6.99",
        category: "desserts",
        image_url: "images/menu-1.jpg"
    },
    
    // Wine Items
    {
        id: 25,
        name: "Red Wine Cabernet",
        description: "Full-bodied red wine from Napa Valley",
        price: "25.99",
        category: "wine",
        image_url: "images/wine-1.jpg"
    },
    {
        id: 26,
        name: "White Wine Chardonnay",
        description: "Crisp and refreshing white wine",
        price: "22.99",
        category: "wine",
        image_url: "images/wine-2.jpg"
    },
    {
        id: 27,
        name: "Pinot Noir",
        description: "Light and elegant red wine",
        price: "28.99",
        category: "wine",
        image_url: "images/wine-3.jpg"
    },
    {
        id: 28,
        name: "Sauvignon Blanc",
        description: "Zesty white wine with citrus notes",
        price: "20.99",
        category: "wine",
        image_url: "images/wine-4.jpg"
    },
    {
        id: 29,
        name: "Merlot",
        description: "Smooth and velvety red wine",
        price: "24.99",
        category: "wine",
        image_url: "images/wine-5.jpg"
    },
    {
        id: 30,
        name: "Rosé Wine",
        description: "Light and fruity pink wine",
        price: "19.99",
        category: "wine",
        image_url: "images/wine-6.jpg"
    }
];

// Debounced search function
function debounceSearch(func, delay) {
    return function(args) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => func.apply(this, args), delay);
    }
}

// Load menu items (using dummy data)
function loadMenu() {
    menuItems = dummyMenuData;
    displayAllMenuItems(menuItems);
    displayMenuByCategory(menuItems);
}

function displayAllMenuItems(items) {
    const allMenu = document.getElementById('all-menu');
    allMenu.innerHTML = renderMenuItems(items);
}

function displayMenuByCategory(menuItems) {
    const categories = ['breakfast', 'lunch', 'dinner', 'desserts', 'wine'];

    categories.forEach(category => {
        const categoryItems = menuItems.filter(item => 
            item.category && item.category.toLowerCase() === category.toLowerCase()
        );

        const container = document.getElementById(category + '-menu');
        if (container) {
            container.innerHTML = renderMenuItems(categoryItems);
        }
    });
}

function renderMenuItems(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center"><p>No items available in this category.</p></div>';
    }

    return items.map(item => `
        <div class="col-md-6">
            <div class="menus d-flex">
                <div class="menu-img img" style="background-image: url(${item.image_url || 'images/menu-1.jpg'});"></div>
                <div class="text">
                    <div class="d-flex">
                        <div class="one-half">
                            <h3>${item.name}</h3>
                        </div>
                        <div class="one-forth">
                            <span class="price">£${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                    </div>
                    <p>${item.description || 'Delicious item from our kitchen'}</p>
                </div>
            </div>
        </div>
    `).join('');
}

// Search functionality
const debouncedSearch = debounceSearch(function(searchTerm) {
    filterItems(searchTerm);
}, 300);

function filterItems(searchTerm = '') {
    let filtered = [...menuItems];

    if (searchTerm.trim()) {
        filtered = filtered.filter(item => 
            item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }

    displayAllMenuItems(filtered);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    loadMenu();

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        debouncedSearch(e.target.value);
    });
});
</script>

<?php include 'includes/footer.php'; ?>
