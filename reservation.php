
<?php include 'includes/header.php'; ?>

<section class="hero-wrap hero-wrap-2" style="background-image: url('images/imgbg1.jpg');" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text align-items-end justify-content-center">
			<div class="col-md-9 ftco-animate text-center mb-5">
				<h1 class="mb-2 bread">Book A Table Now</h1>
				<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="fa fa-chevron-right"></i></a></span> <span>Reservation <i class="fa fa-chevron-right"></i></span></p>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section ftco-wrap-about ftco-no-pb ftco-no-pt">
	<div class="container-fluid">
		<div class="container">
			<div class="reservation-alert">
				<h5><i class="fa fa-info-circle"></i> Reservation Policy</h5>
				<p><strong>Important:</strong> Your table will be reserved for 10 minutes before and 30 minutes after your scheduled time. Please arrive on time to ensure your reservation is maintained. You will receive a confirmation email upon successful booking.</p>
			</div>
		</div>
		<div class="row no-gutters">
			<div class="col-12 p-4 p-md-5 d-flex align-items-center justify-content-center bg-primary">
				<div class="w-100" style="max-width: 1200px;">
					<form action="#" class="appointment-form" id="reservationForm">
						<h3 class="mb-4 text-center">Book your Table</h3>
						<div class="row">
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Full Name *</label>
									<input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Email Address *</label>
									<input type="email" name="email" class="form-control" placeholder="Enter your email" required>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Phone Number *</label>
									<input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Reservation Date *</label>
									<div class="input-wrap">
										<div class="icon"><span class="fa fa-calendar"></span></div>
										<input type="text" name="date" class="form-control book_date" placeholder="Select date" required>
									</div>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Preferred Time *</label>
									<div class="input-wrap">
										<div class="icon"><span class="fa fa-clock-o"></span></div>
										<input type="text" name="time" class="form-control book_time" placeholder="Select time" required>
									</div>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Number of Guests *</label>
									<div class="form-field">
										<div class="select-wrap">
											<div class="icon"><span class="fa fa-chevron-down"></span></div>
											<select name="guests" id="" class="form-control" required>
												<option value="">Select guests</option>
												<option value="1">1 Guest</option>
												<option value="2">2 Guests</option>
												<option value="3">3 Guests</option>
												<option value="4">4 Guests</option>
												<option value="5">5 Guests</option>
												<option value="6">6+ Guests</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Request Type *</label>
									<div class="form-field">
										<div class="select-wrap">
											<div class="icon"><span class="fa fa-cutlery"></span></div>
											<select name="request_type" class="form-control" required>
												<option value="">Select type</option>
												<option value="dine_in">Dine In</option>
												<option value="takeaway">Takeaway</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6"></div>
							<div class="col-md-12 mb-3">
								<div class="form-group">
									<label class="text-white mb-2">Special Requests (Optional)</label>
									<textarea class="form-control" name="special_requests" placeholder="Any dietary requirements, special occasions, or seating preferences..." rows="3"></textarea>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<div class="form-group">
									<input type="submit" value="Request Table Reservation" class="btn btn-white py-3 px-5">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- <section class="ftco-section">
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
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
					<p><a href="#" class="btn btn-primary">Learn more</a></p>
				</div>
			</div>
		</div>
	</div>
</section> -->

<?php include 'includes/footer.php'; ?>
