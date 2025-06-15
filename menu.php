
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_5.jpg');" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text align-items-end justify-content-center">
			<div class="col-md-9 ftco-animate text-center mb-5">
				<h1 class="mb-2 bread">Our Menu</h1>
				<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="fa fa-chevron-right"></i></a></span> <span>Menu <i class="fa fa-chevron-right"></i></span></p>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-2">
			<div class="col-md-7 text-center heading-section ftco-animate">
				<span class="subheading">Specialties</span>
				<h2 class="mb-4">Our Menu</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 nav-link-wrap mb-5">
				<div class="nav ftco-animate nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="v-pills-breakfast-tab" data-toggle="pill" href="#v-pills-breakfast" role="tab" aria-controls="v-pills-breakfast" aria-selected="true">Breakfast</a>
					<a class="nav-link" id="v-pills-lunch-tab" data-toggle="pill" href="#v-pills-lunch" role="tab" aria-controls="v-pills-lunch" aria-selected="false">Lunch</a>
					<a class="nav-link" id="v-pills-dinner-tab" data-toggle="pill" href="#v-pills-dinner" role="tab" aria-controls="v-pills-dinner" aria-selected="false">Dinner</a>
					<a class="nav-link" id="v-pills-desserts-tab" data-toggle="pill" href="#v-pills-desserts" role="tab" aria-controls="v-pills-desserts" aria-selected="false">Desserts</a>
					<a class="nav-link" id="v-pills-wine-tab" data-toggle="pill" href="#v-pills-wine" role="tab" aria-controls="v-pills-wine" aria-selected="false">Wine</a>
					<a class="nav-link" id="v-pills-drinks-tab" data-toggle="pill" href="#v-pills-drinks" role="tab" aria-controls="v-pills-drinks" aria-selected="false">Drinks</a>
				</div>
			</div>
			<div class="col-md-12 d-flex align-items-center">
				<div class="tab-content ftco-animate" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="v-pills-breakfast" role="tabpanel" aria-labelledby="v-pills-breakfast-tab">
						<div class="row">
							<div class="menu-items-container" id="breakfast-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-lunch" role="tabpanel" aria-labelledby="v-pills-lunch-tab">
						<div class="row">
							<div class="menu-items-container" id="lunch-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-dinner" role="tabpanel" aria-labelledby="v-pills-dinner-tab">
						<div class="row">
							<div class="menu-items-container" id="dinner-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-desserts" role="tabpanel" aria-labelledby="v-pills-desserts-tab">
						<div class="row">
							<div class="menu-items-container" id="desserts-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-wine" role="tabpanel" aria-labelledby="v-pills-wine-tab">
						<div class="row">
							<div class="menu-items-container" id="wine-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="v-pills-drinks" role="tabpanel" aria-labelledby="v-pills-drinks-tab">
						<div class="row">
							<div class="menu-items-container" id="drinks-items">
								<!-- Menu items will be loaded here via JavaScript -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include 'includes/footer.php'; ?>
