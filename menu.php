<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/imgbg2.jpg');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Menu</span></p>
        <h1 class="mb-0 bread menu-page-title">Our Restaurant Menu</h1>
        <p class="menu-page-subtitle">Discover our culinary excellence</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 heading-section text-center ftco-animate mb-5">
        <h2 class="mb-4">Our Delicious Menu</h2>
        <!--<p>A menu inspired by culture, designed for indulgence.</p>-->
      </div>
    </div>

    <!-- Search Bar -->
    <!--<div class="row justify-content-center mb-4">-->
    <!--  <div class="col-md-8">-->
    <!--    <div class="search-container">-->
    <!--      <input type="text" class="form-control" placeholder="Search for dishes..." id="searchInput" style="border-radius: 25px; padding: 12px 20px;">-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->

    <!-- Clickable Menu Cards -->
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5 mb-4">
        <a href="pdf/Mallroad House Table Menu V2.pdf" target="_blank" style="text-decoration: none; color: inherit;">
          <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
            <div style="background-image: url('images/insta-3.jpg'); height: 250px; background-size: cover; background-position: center;"></div>
            <div class="card-body text-center">
              <h5 class="card-title">Dine-In Menu</h5>
              <p class="card-text">Perfect for your table-side experience.</p>
              <div class="btn btn-lg" style="
                background: linear-gradient(135deg, #28a745, #218838);
                color: white;
                font-weight: bold;
                border-radius: 12px;
                padding: 10px 20px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
              ">
                <i class="fas fa-utensils me-2"></i>View Table Menu
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-lg-5 mb-4">
        <a href="pdf/Mallroad House Takeaway Menu.pdf" target="_blank" style="text-decoration: none; color: inherit;">
          <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
            <div style="background-image: url('images/dinner-1.jpg'); height: 250px; background-size: cover; background-position: center;"></div>
            <div class="card-body text-center">
              <h5 class="card-title">Takeaway Menu</h5>
              <p class="card-text">Order your favorites to-go or for delivery.</p>
              <div class="btn btn-lg" style="
                background: linear-gradient(135deg, #007bff, #0056b3);
                color: white;
                font-weight: bold;
                border-radius: 12px;
                padding: 10px 20px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
              ">
                <i class="fas fa-shopping-bag me-2"></i>View Menu
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Dynamic Menu Items -->
    <!--<div class="row" id="all-menu"></div>-->
  </div>
</section>

<script>
  let menuItems = [];
  let searchTimeout;

  function debounceSearch(func, delay) {
    return function(args) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => func.apply(this, args), delay);
    };
  }

  async function loadMenu() {
    try {
      const response = await fetch('api/menu.php');
      const data = await response.json();
      menuItems = Array.isArray(data) ? data : [];
      displayAllMenuItems(menuItems);
    } catch (error) {
      console.error('Error loading menu:', error);
      document.getElementById('all-menu').innerHTML = ``;
    }
  }

  function displayAllMenuItems(items) {
    const allMenu = document.getElementById('all-menu');
    allMenu.innerHTML = renderMenuItems(items);
  }

  function renderMenuItems(items) {
    if (items.length === 0) {
      return '<div class="col-12 text-center"><p>No items available in this category.</p></div>';
    }

    return items.map(item => `
      <div class="col-md-6 mb-4">
        <div class="menus d-flex shadow-sm p-3 rounded bg-white">
          <div class="menu-img img me-3" style="background-image: url(${item.image_url || 'images/menu-1.jpg'}); width: 100px; height: 100px; background-size: cover; border-radius: 10px;"></div>
          <div class="text">
            <div class="d-flex justify-content-between">
              <h5>${item.name}</h5>
              <span class="price text-success">£${parseFloat(item.price).toFixed(2)}</span>
            </div>
            <p class="mb-0">${item.description || 'Delicious item from our kitchen'}</p>
          </div>
        </div>
      </div>
    `).join('');
  }

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

  document.addEventListener('DOMContentLoaded', function() {
    loadMenu();
    document.getElementById('searchInput').addEventListener('input', function(e) {
      debouncedSearch(e.target.value);
    });
  });
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php include 'includes/footer.php'; ?>
