<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-bread" style="background-image: url('images/imgbg2.jpg');">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs">
          <span class="mr-2">
            <a href="index.php" style="color: #fff; text-decoration: none;">Home</a>
          </span> 
          <span style="color: #e52b34;">Menu</span>
        </p>
        <h1 class="mb-0 bread menu-page-title">Our Restaurant Menu</h1>
        <p class="menu-page-subtitle">Discover our culinary excellence</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section" style="padding: 5em 0;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 heading-section text-center ftco-animate mb-5">
        <h2 class="mb-4" style="color: #2c3e50; font-size: 2.5rem;">Our Delicious Menu</h2>
        <p style="color: #7f8c8d; font-size: 1.1rem;">A menu inspired by culture, designed for indulgence.</p>
      </div>
    </div>

    <!-- Clickable Menu Cards with better spacing -->
    <div class="row justify-content-center" style="margin-top: 3rem;">
      <div class="col-md-6 col-lg-5 mb-4 d-flex">
        <a href="pdf/Mallroad House Table Menu V2.pdf" target="_blank" style="text-decoration: none; color: inherit; width: 100%;">
          <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden; transition: all 0.3s ease;">
            <div style="background-image: url('images/insta-3.jpg'); height: 280px; background-size: cover; background-position: center;"></div>
            <div class="card-body text-center d-flex flex-column justify-content-between" style="padding: 2rem;">
              <div>
                <h4 class="card-title" style="color: #2c3e50; font-weight: 700; margin-bottom: 1rem;">Dine-In Menu</h4>
                <p class="card-text" style="color: #7f8c8d; font-size: 1rem; line-height: 1.6;">Perfect for your table-side experience with our full restaurant offerings.</p>
              </div>
              <div class="btn btn-lg mt-3" style="
                background: linear-gradient(135deg, #8B4513, #D2691E);
                color: white;
                font-weight: bold;
                border-radius: 25px;
                padding: 12px 25px;
                box-shadow: 0 6px 20px rgba(139, 69, 19, 0.3);
                border: none;
                transition: all 0.3s ease;
              ">
                <i class="fas fa-utensils me-2"></i>View Table Menu
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-lg-5 mb-4 d-flex">
        <a href="pdf/Mallroad House Takeaway Menu.pdf" target="_blank" style="text-decoration: none; color: inherit; width: 100%;">
          <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden; transition: all 0.3s ease;">
            <div style="background-image: url('images/dinner-1.jpg'); height: 280px; background-size: cover; background-position: center;"></div>
            <div class="card-body text-center d-flex flex-column justify-content-between" style="padding: 2rem;">
              <div>
                <h4 class="card-title" style="color: #2c3e50; font-weight: 700; margin-bottom: 1rem;">Takeaway Menu</h4>
                <p class="card-text" style="color: #7f8c8d; font-size: 1rem; line-height: 1.6;">Order your favorites to-go or for convenient delivery service.</p>
              </div>
              <div class="btn btn-lg mt-3" style="
                background: linear-gradient(135deg, #e52b34, #ff4757);
                color: white;
                font-weight: bold;
                border-radius: 25px;
                padding: 12px 25px;
                box-shadow: 0 6px 20px rgba(229, 43, 52, 0.3);
                border: none;
                transition: all 0.3s ease;
              ">
                <i class="fas fa-shopping-bag me-2"></i>View Menu
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Additional Info Section -->
    <div class="row justify-content-center mt-5">
      <div class="col-md-8 text-center">
        <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 2rem; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
          <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 1rem;">Need Help Choosing?</h5>
          <p style="color: #7f8c8d; margin-bottom: 1.5rem;">Our friendly staff is ready to help you select the perfect dishes for your dining experience.</p>
          <a href="contact.php" class="btn" style="
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 25px;
            text-decoration: none;
            border: none;
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
          ">
            Contact Us
          </a>
        </div>
      </div>
    </div>

    <!-- Dynamic Menu Items -->
    <div class="row" id="all-menu" style="margin-top: 3rem;"></div>
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
