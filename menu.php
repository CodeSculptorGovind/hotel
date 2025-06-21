
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
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            </div>
        </div>
        <div class="row d-md-flex justify-content-center">
            <div class="col-lg-12 ftco-animate p-md-5">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-breakfast-tab" data-toggle="pill" href="#v-pills-breakfast" role="tab" aria-controls="v-pills-breakfast" aria-selected="true">Breakfast</a>
                            <a class="nav-link" id="v-pills-lunch-tab" data-toggle="pill" href="#v-pills-lunch" role="tab" aria-controls="v-pills-lunch" aria-selected="false">Lunch</a>
                            <a class="nav-link" id="v-pills-dinner-tab" data-toggle="pill" href="#v-pills-dinner" role="tab" aria-controls="v-pills-dinner" aria-selected="false">Dinner</a>
                            <a class="nav-link" id="v-pills-desserts-tab" data-toggle="pill" href="#v-pills-desserts" role="tab" aria-controls="v-pills-desserts" aria-selected="false">Desserts</a>
                            <a class="nav-link" id="v-pills-wine-tab" data-toggle="pill" href="#v-pills-wine" role="tab" aria-controls="v-pills-wine" aria-selected="false">Wine</a>
                            <a class="nav-link" id="v-pills-drinks-tab" data-toggle="pill" href="#v-pills-drinks" role="tab" aria-controls="v-pills-drinks" aria-selected="false">Drinks</a>
                            <a class="nav-link" id="v-pills-combos-tab" data-toggle="pill" href="#v-pills-combos" role="tab" aria-controls="v-pills-combos" aria-selected="false">Combos</a>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex align-items-center">
                        <div class="tab-content ftco-animate" id="v-pills-tabContent">

                            <!-- Breakfast Tab -->
                            <div class="tab-pane fade show active" id="v-pills-breakfast" role="tabpanel" aria-labelledby="v-pills-breakfast-tab">
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

                            <!-- Drinks Tab -->
                            <div class="tab-pane fade" id="v-pills-drinks" role="tabpanel" aria-labelledby="v-pills-drinks-tab">
                                <div class="row" id="drinks-menu">
                                    <!-- Drinks items will be loaded here -->
                                </div>
                            </div>

                            <!-- Combos Tab -->
                            <div class="tab-pane fade" id="v-pills-combos" role="tabpanel" aria-labelledby="v-pills-combos-tab">
                                <div class="row" id="combos-menu">
                                    <!-- Combo items will be loaded here -->
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
document.addEventListener('DOMContentLoaded', function() {
    // Load menu items when page loads
    loadAllMenuItems();
    loadComboItems();
});

function loadAllMenuItems() {
    fetch('api/menu.php')
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data)) {
            displayMenuByCategory(data);
        } else {
            console.error('Invalid menu data received');
        }
    })
    .catch(error => {
        console.error('Error loading menu items:', error);
    });
}

function loadComboItems() {
    fetch('api/menu.php?type=combos')
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data)) {
            displayComboItems(data);
        } else {
            console.error('Invalid combo data received');
        }
    })
    .catch(error => {
        console.error('Error loading combo items:', error);
    });
}

function displayMenuByCategory(menuItems) {
    const categories = ['breakfast', 'lunch', 'dinner', 'desserts', 'wine', 'drinks'];
    
    categories.forEach(category => {
        const categoryItems = menuItems.filter(item => 
            item.category && item.category.toLowerCase() === category.toLowerCase() && 
            (parseInt(item.is_available) === 1 || item.is_available === true || item.is_available === '1')
        );
        
        const container = document.getElementById(category + '-menu');
        if (container) {
            container.innerHTML = renderMenuItems(categoryItems);
        }
    });
}

function displayComboItems(comboItems) {
    const container = document.getElementById('combos-menu');
    if (container) {
        container.innerHTML = renderComboItems(comboItems);
    }
}

function renderMenuItems(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center"><p>No items available in this category.</p></div>';
    }

    return items.map(item => `
        <div class="col-md-6">
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
                    <p>${item.description || 'Delicious item from our kitchen'}</p>
                </div>
            </div>
        </div>
    `).join('');
}

function renderComboItems(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center"><p>No combo items available.</p></div>';
    }

    return items.map(item => `
        <div class="col-md-6">
            <div class="menus d-flex ftco-animate">
                <div class="menu-img img" style="background-image: url(${item.image_url || 'images/menu-1.jpg'});"></div>
                <div class="text">
                    <div class="d-flex">
                        <div class="one-half">
                            <h3>${item.name} <span class="badge badge-info">Combo</span></h3>
                        </div>
                        <div class="one-forth">
                            <span class="price">$${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                    </div>
                    <p>${item.description || 'Special combo deal'}</p>
                    ${item.combo_items ? `<small class="text-muted">Includes: ${item.combo_items}</small>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}
</script>

<?php include 'includes/footer.php'; ?>
