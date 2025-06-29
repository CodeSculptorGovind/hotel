<?php include 'includes/header.php'; ?>

<section class="hero-wrap">
	<div class="home-slider owl-carousel js-fullheight">
		<div class="slider-item js-fullheight" style="background-image:url(images/imgbg1.jpg);">
			<div class="overlay"></div>
			<div class="container">
				<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
					<div class="col-md-12 ftco-animate">
						<div class="text w-100 mt-5 text-center">
							<span class="subheading">Mall Road House</h2></span>
							<h1> Crafted with passion, served with elegance.</h1>
							<span class="subheading-2"></span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="slider-item js-fullheight" style="background-image:url(images/imgbg2.jpg);">
			<div class="overlay"></div>
			<div class="container">
				<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
					<div class="col-md-12 ftco-animate">
						<div class="text w-100 mt-5 text-center">
							<span class="subheading">Mall Road House</h2></span>
							<h1>Crafted with passion, served with elegance.</h1>
							<span class="subheading-2 sub"> </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section ftco-wrap-about ftco-no-pb ftco-no-pt">
	<div class="col-md-12 text-center">
		<div class="row no-gutters" >
			<div class="col-sm-4 p-4 p-md-5 d-flex align-items-center justify-content-center bg-primary">
				<form id="quickReservationForm" class="appointment-form">
					<h3 class="mb-3">Book your Table</h3>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" id="quickName" class="form-control" placeholder="Name" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="email" id="quickEmail" class="form-control" placeholder="Email" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" id="quickPhone" class="form-control" placeholder="Phone" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="fa fa-calendar"></span></div>
									<input type="text" id="quickDate" class="form-control book_date" placeholder="Check-In" required>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="fa fa-clock-o"></span></div>
									<input type="text" id="quickTime" class="form-control book_time" placeholder="Time" required>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="form-field">
									<div class="select-wrap">
										<div class="icon"><span class="fa fa-chevron-down"></span></div>
										<select id="quickGuests" class="form-control" required>
											<option value="">Guest</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="submit" value="Request Reservation" class="btn btn-white py-3 px-4">
							</div>
						</div>
					</div>
				</form>
			</div>
		<!--	<div class="col-sm-8 wrap-about py-5 ftco-animate img" style="background-image: url(images/about.jpg);">
				<div class="row pb-5 pb-md-0">
					<div class="col-md-12 col-lg-7">
						<div class="heading-section mt-5 mb-4">
							<div class="pl-lg-3 ml-md-5">
								<span class="subheading">About</span>
								<h2 class="mb-4">Welcome to Mall Road House</h2>
							</div>
						</div>
						<div class="pl-lg-3 ml-md-5">
							<p>Experience the finest dining in Shimla at Mall Road House. Located on the iconic Mall Road, we offer exceptional cuisine crafted with fresh, local ingredients. Our skilled chefs combine traditional recipes with modern techniques to create memorable dining experiences. We also provide convenient takeaway and delivery services to selected zip codes, plus table reservations for special occasions.</p>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</section>

<section class="ftco-section ftco-intro" style="background-image: url(images/imgbg2.jpg);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<span>Now Available</span>
				<h2> Delivery &amp; Takeaway</h2>
				<div class="col-md-12 text-center">
		<a href="order.php" class="btn btn-white btn-outline-white">Order Now</a>
			</div>
	
			</div>
		</div>
	</div>
</section>

<!-- <section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-2">
			<div class="col-md-7 text-center heading-section ftco-animate">
				<span class="subheading">Specialties</span>
				<h2 class="mb-4">Our Menu</h2>
			</div>
		</div>
		<div class="row" id="menuPreview">
			<!-- Menu items will be loaded here dynamically -->
		</div>
	</div>
</section> 

<!-- <section class="ftco-section testimony-section" style="background-image: url(images/bg_5.jpg);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row justify-content-center mb-3 pb-2">
			<div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
				<span class="subheading">Testimony</span>
				<h2 class="mb-4">Happy Customer</h2>
			</div>
		</div>
		<div class="row ftco-animate justify-content-center">
			<div class="col-md-7">
				<div class="carousel-testimony owl-carousel ftco-owl">
					<div class="item">
						<div class="testimony-wrap text-center">
							<div class="text p-3">
								<p class="mb-4">Exceptional dining experience! The food quality and service at Mall Road House is outstanding. Highly recommend for special occasions.</p>
								<div class="user-img mb-4" style="background-image: url(images/person_1.jpg)">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="fa fa-quote-left"></i>
									</span>
								</div>
								<p class="name">Sarah Johnson</p>
								<span class="position">Food Blogger</span>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="testimony-wrap text-center">
							<div class="text p-3">
								<p class="mb-4">Amazing atmosphere and delicious food. Their takeaway service is also very convenient. A must-visit restaurant in Shimla!</p>
								<div class="user-img mb-4" style="background-image: url(images/person_2.jpg)">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="fa fa-quote-left"></i>
									</span>
								</div>
								<p class="name">Michael Chen</p>
								<span class="position">Travel Enthusiast</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section bg-light">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-2">
			<div class="col-md-7 text-center heading-section ftco-animate">
				<span class="subheading">Chef</span>
				<h2 class="mb-4">Our Master Chef</h2>
			</div>
		</div>	
		<div class="row">
			<div class="col-12 col-sm-6 col-lg-3 ftco-animate">
				<div class="staff">
					<div class="img" style="background-image: url(images/chef-4.jpg);"></div>
					<div class="text px-4 pt-2">
						<h3>John Gustavo</h3>
						<span class="position mb-2">CEO, Co Founder</span>
						<div class="faded">
							<p>Passionate about creating exceptional culinary experiences with fresh, local ingredients.</p>
							<ul class="ftco-social d-flex">
								<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3 ftco-animate">
				<div class="staff">
					<div class="img" style="background-image: url(images/chef-2.jpg);"></div>
					<div class="text px-4 pt-2">
						<h3>Michelle Fraulen</h3>
						<span class="position mb-2">Head Chef</span>
						<div class="faded">
							<p>Specializes in fusion cuisine, bringing together traditional and modern cooking techniques.</p>
							<ul class="ftco-social d-flex">
								<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3 ftco-animate">
				<div class="staff">
					<div class="img" style="background-image: url(images/chef-3.jpg);"></div>
					<div class="text px-4 pt-2">
						<h3>Alfred Smith</h3>
						<span class="position mb-2">Chef Cook</span>
						<div class="faded">
							<p>Expert in regional cuisines and innovative dessert preparations.</p>
							<ul class="ftco-social d-flex">
								<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3 ftco-animate">
				<div class="staff">
					<div class="img" style="background-image: url(images/chef-1.jpg);"></div>
					<div class="text px-4 pt-2">
						<h3>Antonio Santibanez</h3>
						<span class="position mb-2">Chef Cook</span>
						<div class="faded">
							<p>Master of international flavors and traditional cooking methods.</p>
							<ul class="ftco-social d-flex">
								<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
								<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb">
	<div class="container">
		<div class="row d-flex">
			<div class="col-md-6 d-flex">
				<div class="img img-2 w-100 mr-md-2" style="background-image: url(images/bg_6.jpg);"></div>
				<div class="img img-2 w-100 ml-md-2" style="background-image: url(images/bg_4.jpg);"></div>
			</div>
			<div class="col-md-6 ftco-animate makereservation p-4 p-md-5">
				<div class="heading-section ftco-animate mb-5">
					<span class="subheading">This is our secrets</span>
					<h2 class="mb-4">Perfect Ingredients</h2>
					<p>We source the finest local ingredients from Himachal Pradesh's rich valleys and combine them with traditional cooking techniques to create unforgettable dining experiences. Our chefs are passionate about quality and freshness in every dish.</p>
					<p><a href="about.php" class="btn btn-primary">Learn more</a></p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section bg-light">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-2">
			<div class="col-md-7 text-center heading-section ftco-animate">
				<span class="subheading">Blog</span>
				<h2 class="mb-4">Recent Blog</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 col-lg-4 ftco-animate">
				<div class="blog-entry">
					<a href="blog-single.php" class="block-20" style="background-image: url('images/image_1.jpg');">
					</a>
					<div class="text px-4 pt-3 pb-4">
						<div class="meta">
							<div><a href="#">January 15, 2024</a></div>
							<div><a href="#">Admin</a></div>
						</div>
						<h3 class="heading"><a href="#">Discover the Secret Behind Our Signature Dishes</a></h3>
						<p class="clearfix">
							<a href="blog-single.php" class="float-left read btn btn-primary">Read more</a>
							<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 5</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6 col-lg-4 ftco-animate">
				<div class="blog-entry">
					<a href="blog-single.php" class="block-20" style="background-image: url('images/image_2.jpg');">
					</a>
					<div class="text px-4 pt-3 pb-4">
						<div class="meta">
							<div><a href="#">January 10, 2024</a></div>
							<div><a href="#">Admin</a></div>
						</div>
						<h3 class="heading"><a href="#">Farm-to-Table: Our Local Ingredient Sources</a></h3>
						<p class="clearfix">
							<a href="blog-single.php" class="float-left read btn btn-primary">Read more</a>
							<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6 col-lg-4 ftco-animate">
				<div class="blog-entry">
					<a href="blog-single.php" class="block-20" style="background-image: url('images/image_3.jpg');">
					</a>
					<div class="text px-4 pt-3 pb-4">
						<div class="meta">
							<div><a href="#">January 5, 2024</a></div>
							<div><a href="#">Admin</a></div>
						</div>
						<h3 class="heading"><a href="#">Exploring Traditional Himachali Cuisine</a></h3>
						<p class="clearfix">
							<a href="blog-single.php" class="float-left read btn btn-primary">Read more</a>
							<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 8</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section> -->

<section class="ftco-section ftco-no-pt ftco-no-pb ftco-intro bg-primary">
	<div class="container py-5">
		<div class="row py-2">
			<div class="col-md-12 text-center">
				<h2>We Make Delicious &amp; Nutritious Food</h2>
				<a href="reservation.php" class="btn btn-white btn-outline-white">Book A Table Now</a>
			</div>
		</div>
	</div>
</section>

<?php include 'includes/footer.php'; ?>