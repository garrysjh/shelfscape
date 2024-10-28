<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"/>
    <link rel="stylesheet" href="styles/reset.css" />
  </head>
  <body>
    <header>
      <nav class="navbar">
        <div class="logo">
            <a href="index.php">
          <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
        </a>
        </div>
        <div class="nav-links">
          <a href="books.php">Books</a>
          <div class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="books.php?category=Fantasy">Fantasy</a>
              <a href="books.php?category=Fiction">Fiction</a>
              <a href="books.php?category=Romance">Romance</a>
              <a href="books.php?category=Classics">Classics</a>
              <a href="books.php?category=Horror">Horror</a>
            </div>
          </div>
          <a href="events.html">Events</a>
          <a href="aboutus.html">About</a>
          <a href="donate.html">Donate</a>
        </div>
        <div class="search-bar">
          <form action="books.php" method="GET">
            <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" />
            <button type="submit" class="search-button">Search</button>
            <i class="fas fa-search"></i>
          </form>
        </div>
        <div class="account-icon">
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
            <div class="dropdown">
            <img src="assets/icons/user.png" alt="User Icon" class="usericon"/>
              <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php">Logout</a>
              </div>
            </div>
          <?php else: ?>
            <a href="login.php">
              <img src="assets/icons/user.png" alt="User Icon" class="usericon"/>
            </a>
          <?php endif; ?>
        </div>
      </nav>
      <div class="content"></div> </div>
    </header>
    		<!-- Start Contact Us -->
		<section class="contact-us section">
			<div class="container">
				<div class="inner">
					<div class="row"> 
						<div class="col-lg-6">
							<div class="contact-us-left">
								<!--Start Google-map -->
								<div id="myMap">    
									<iframe  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.72281751501!2d103.67816467586454!3d1.3426961986446004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da0f75a53bec9d%3A0xd08547c621f6dca6!2sSchool%20of%20Electrical%20and%20Electronic%20Engineering%20(EEE)!5e0!3m2!1sen!2ssg!4v1730082262426!5m2!1sen!2ssg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
									</iframe>
									</div>
								<!--/End Google-map -->
							</div>
						</div>
						<div class="col-lg-6">
							<div class="contact-us-form">
								<h2>Contact With Us</h2>
								<p>If you have any questions please fell free to contact with us.</p>
								<!-- Form -->
								<form class="form" method="post" action="mail/mail.php">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<input type="text" name="name" placeholder="Name" required="">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="email" name="email" placeholder="Email" required="">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="text" name="phone" placeholder="Phone" required="">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="text" name="subject" placeholder="Subject" required="">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<textarea name="message" placeholder="Your Message" required=""></textarea>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group login-btn">
												<button class="btn" type="submit">Send</button>
											</div>
											<div class="checkbox">
												<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox">Do you want to subscribe our Newsletter ?</label>
											</div>
										</div>
									</div>
								</form>
								<!--/ End Form -->
							</div>
						</div>
					</div>
				</div>
				<div class="contact-info">
					<div class="row">
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont icofont-ui-call"></i>
								<div class="content">
									<h3>+(000) 1234 56789</h3>
									<p>info@company.com</p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont-google-map"></i>
								<div class="content">
									<h3>2 Fir e Brigade Road</h3>
									<p>Chittagonj, Lakshmipur</p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont icofont-wall-clock"></i>
								<div class="content">
									<h3>Mon - Sat: 8am - 5pm</h3>
									<p>Sunday Closed</p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
					</div>
				</div>
			</div>
		</section>
		<!--/ End Contact Us -->
		
				<!-- Footer Area -->
				<footer id="footer" class="footer ">
					<!-- Footer Top -->
					<div class="footer-top">
						<div class="container">
							<div class="row">
								<div class="col-lg-3 col-md-6 col-12">
									<div class="single-footer">
										<h2>About Us</h2>
										<p>Lorem ipsum dolor sit am consectetur adipisicing elit do eiusmod tempor incididunt ut labore dolore magna.</p>
										<!-- Social -->
										<ul class="social">
											<li><a href="#"><i class="icofont-facebook"></i></a></li>
											<li><a href="#"><i class="icofont-google-plus"></i></a></li>
											<li><a href="#"><i class="icofont-twitter"></i></a></li>
											<li><a href="#"><i class="icofont-vimeo"></i></a></li>
											<li><a href="#"><i class="icofont-pinterest"></i></a></li>
										</ul>
										<!-- End Social -->
									</div>
								</div>
								<div class="col-lg-3 col-md-6 col-12">
									<div class="single-footer f-link">
										<h2>Quick Links</h2>
										<div class="row">
											<div class="col-lg-6 col-md-6 col-12">
												<ul>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Home</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>About Us</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Services</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Our Cases</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Other Links</a></li>	
												</ul>
											</div>
											<div class="col-lg-6 col-md-6 col-12">
												<ul>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Consuling</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Finance</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Testimonials</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>FAQ</a></li>
													<li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Contact Us</a></li>	
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6 col-12">
									<div class="single-footer">
										<h2>Open Hours</h2>
										<p>Lorem ipsum dolor sit ame consectetur adipisicing elit do eiusmod tempor incididunt.</p>
										<ul class="time-sidual">
											<li class="day">Monday - Fridayp <span>8.00-20.00</span></li>
											<li class="day">Saturday <span>9.00-18.30</span></li>
											<li class="day">Monday - Thusday <span>9.00-15.00</span></li>
										</ul>
									</div>
								</div>
								<div class="col-lg-3 col-md-6 col-12">
									<div class="single-footer">
										<h2>Newsletter</h2>
										<p>subscribe to our newsletter to get allour news in your inbox.. Lorem ipsum dolor sit amet, consectetur adipisicing elit,</p>
										<form action="mail/mail.php" method="get" target="_blank" class="newsletter-inner">
											<input name="email" placeholder="Email Address" class="common-input" onfocus="this.placeholder = ''"
												onblur="this.placeholder = 'Your email address'" required="" type="email">
											<button class="button"><i class="icofont icofont-paper-plane"></i></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/ End Footer Top -->
					<!-- Copyright -->
					<div class="copyright">
						<div class="container">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-12">
									<div class="copyright-content">
										<p>© Copyright 2018  |  All Rights Reserved by <a href="https://www.wpthemesgrid.com" target="_blank">wpthemesgrid.com</a> </p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/ End Copyright -->
				</footer>
				<!--/ End Footer Area -->
			</body>
			</html>
			