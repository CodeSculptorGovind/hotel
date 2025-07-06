<?php include 'includes/header.php'; ?>

<section class="ftco-section" style="padding: 8em 0 5em 0;">
  

    <!-- Filter Section -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-10">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search menu items...">
              </div>
              <div class="col-md-4">
                <select id="categoryFilter" class="form-select">
                  <option value="">All Categories</option>
                </select>
              </div>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="popularOnly">
                  <label class="form-check-label" for="popularOnly">
                    Popular items only
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dynamic Menu Items -->
    <div class="row" id="all-menu" style="margin-top: 2rem; padding-left: 90px; padding-right: 90px;"></div>
  </div>
</section>

<script>
  let menuItems = [];
  let categories = [];
  let searchTimeout;

  function debounceSearch(func, delay) {
    return function(args) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => func.apply(this, args), delay);
    };
  }

  async function loadMenu() {
    try {
      const response = await fetch('api/takeaway_menu.php?action=categories');
      const data = await response.json();
      
      if (data.success && data.categories) {
        categories = data.categories;
        menuItems = [];
        
        // Flatten items from all categories
        categories.forEach(category => {
          if (category.items) {
            category.items.forEach(item => {
              item.category_name = category.name;
              menuItems.push(item);
            });
          }
        });
        
        populateCategoryFilter();
        displayAllMenuItems(menuItems);
      } else {
        document.getElementById('all-menu').innerHTML = '<div class="col-12 text-center"><p>No menu items available.</p></div>';
      }
    } catch (error) {
      console.error('Error loading menu:', error);
      document.getElementById('all-menu').innerHTML = '<div class="col-12 text-center"><p>Error loading menu. Please try again later.</p></div>';
    }
  }

  function populateCategoryFilter() {
    const categorySelect = document.getElementById('categoryFilter');
    categorySelect.innerHTML = '<option value="">All Categories</option>';
    
    categories.forEach(category => {
      const option = document.createElement('option');
      option.value = category.id;
      option.textContent = category.name;
      categorySelect.appendChild(option);
    });
  }

  function displayAllMenuItems(items) {
    const allMenu = document.getElementById('all-menu');
    allMenu.innerHTML = renderMenuItems(items);
  }

  function renderMenuItems(items) {
    if (items.length === 0) {
      return '<div class="col-12 text-center"><p>No items found matching your criteria.</p></div>';
    }

    return items.map(item => `
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
          <div style="
            background-image: url(${item.image_url || 'images/menu-1.jpg'}); 
            height: 200px; 
            background-size: cover; 
            background-position: center;
            position: relative;
          ">
            ${item.is_popular ? '<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Popular</span>' : ''}
            ${item.allergens ? '<span class="badge bg-danger position-absolute bottom-0 start-0 m-2"><i class="fas fa-exclamation-triangle" style="color: white;"></i></span>' : ''}
          </div>
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="card-title mb-0">${item.name}</h6>
              <span class="badge" style="color: white; background-color:rgb(43, 229, 139);">${item.item_code || ''}</span>
            </div>
            <small class="text-muted mb-2">${item.category_name}</small>
            <p class="card-text text-muted small mb-3">${item.description || 'Delicious item from our kitchen'}</p>
            <div class="mt-auto">
              <div class="d-flex justify-content-between align-items-center">
                <strong class="h5 text-success mb-0">£${parseFloat(item.price).toFixed(2)}</strong>
                ${item.preparation_time ? `<small class="text-muted"><i class="fas fa-clock"></i> ${item.preparation_time} min</small>` : ''}
              </div>
              ${item.allergens ? `<small class="text-danger mt-1"><i class="fas fa-exclamation-triangle"></i> Contains: ${item.allergens}</small>` : ''}
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }

  const debouncedSearch = debounceSearch(function() {
    filterItems();
  }, 300);

  function filterItems() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const popularOnly = document.getElementById('popularOnly').checked;
    
    let filtered = [...menuItems];
    
    // Search filter
    if (searchTerm.trim()) {
      filtered = filtered.filter(item =>
        item.name.toLowerCase().includes(searchTerm) ||
        (item.description && item.description.toLowerCase().includes(searchTerm)) ||
        (item.allergens && item.allergens.toLowerCase().includes(searchTerm))
      );
    }
    
    // Category filter
    if (categoryFilter) {
      filtered = filtered.filter(item => item.category_id == categoryFilter);
    }
    
    // Popular filter
    if (popularOnly) {
      filtered = filtered.filter(item => item.is_popular == 1);
    }
    
    displayAllMenuItems(filtered);
  }

  document.addEventListener('DOMContentLoaded', function() {
    loadMenu();
    
    // Add event listeners
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const popularOnly = document.getElementById('popularOnly');
    
    if (searchInput) {
      searchInput.addEventListener('input', debouncedSearch);
    }
    if (categoryFilter) {
      categoryFilter.addEventListener('change', filterItems);
    }
    if (popularOnly) {
      popularOnly.addEventListener('change', filterItems);
    }
  });
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php include 'includes/footer.php'; ?>
