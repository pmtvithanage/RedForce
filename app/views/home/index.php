<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/home_style.css">


<!-- Navigation -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
            <span class="logo-text">RED FORCE</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#home" class="nav-link">HOME</a>
            </li>
            <li class="nav-item">
                <a href="#stats" class="nav-link">ABOUT</a>
            </li>
            <li class="nav-item">
                <a href="#services" class="nav-link">SERVICE</a>
            </li>
            <li class="nav-item">
                <a href="#jobs" class="nav-link">JOBS</a>
            </li>
            <li class="nav-item">
                <a href="#contact" class="nav-link">CONTACT</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo URL_ROOT; ?>/users/login" class="nav-link login-btn">LOGIN</a>
            </li>
        </ul>
        <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero" style="background-image: url('<?php echo URL_ROOT; ?>/img/hero.png');">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">Secure. Track. Manage.</h1>
            <p class="hero-subtitle">Streamline your security operations with RedForce — the complete solution for officer deployment, incident reporting, and performance tracking.</p>
            <div class="hero-buttons">
                <a href="#GetService" class="btn btn-primary">Get Service</a>
                <a href="<?php echo URL_ROOT; ?>/users/login" class="btn btn-secondary">Login</a>
            </div>
        </div>
        <div class="hero-logo">
            <div class="hero-emblem">
                <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE Emblem">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section id="stats" class="stats">
    <div class="stats-container">
        <div class="stat-item">
            <h3>1500+</h3>
            <p>Officers</p>
        </div>
        <div class="stat-item">
            <h3>50+</h3>
            <p>Clients</p>
        </div>
        <div class="stat-item">
            <h3>10,000+</h3>
            <p>Shifts Managed</p>
        </div>
        <div class="stat-item">
            <h3>300+</h3>
            <p>Posts Secured</p>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Securing Lives & Businesses with Excellence</h2>
                <p>RedForce Security Services operates nationwide, serving a diverse client base with advanced security systems and highly disciplined personnel. With over 1500+ officers, we deliver high-quality security services and maintain our commitment to safeguarding people, property, and assets across the nation.</p>
            </div>
            <div class="about-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officer">
            </div>
        </div>
    </div>
</section>

<!-- Clients Section -->
<section id="clients" class="clients">
    <div class="container">
        <h2>Our Clients</h2>
        <p class="section-subtitle">Partnering with leading businesses and organizations, we deliver reliable security solutions that meet unique needs.</p>
        <div class="clients-marquee">
            <div class="marquee-track">
                <div class="marquee-group">
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/peoples-bank.png" alt="People's Bank">
                        <p>People's Bank</p>
                    </div>
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/slt.png" alt="SLT">
                        <p>Sri Lanka Telecom</p>
                    </div>
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/sls-bank.png" alt="SLS Bank">
                        <p>SLS Bank</p>
                    </div>
                </div>
                <div class="marquee-group" aria-hidden="true">
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/peoples-bank.png" alt="People's Bank">
                        <p>People's Bank</p>
                    </div>
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/slt.png" alt="SLT">
                        <p>Sri Lanka Telecom</p>
                    </div>
                    <div class="client-logo">
                        <img src="<?php echo URL_ROOT; ?>/img/sls-bank.png" alt="SLS Bank">
                        <p>SLS Bank</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services">
    <div class="container">
        <h2>Our Services</h2>
        <p class="section-subtitle">We offer a comprehensive range of security services to businesses, institutions, and individuals. Our professionally trained officers and smart technology ensure your security needs are met with excellence.</p>
        <div class="services-grid">
            <div class="service-card">
                <h3>Complete Corporate Security Management</h3>
                <p>End-to-end security solutions for corporate environments with trained personnel and advanced monitoring systems.</p>
            </div>
            <div class="service-card">
                <h3>Complete Electronic Security Systems</h3>
                <p>State-of-the-art electronic security including CCTV, access control, and alarm systems.</p>
            </div>
            <div class="service-card">
                <h3>Custom Corporate Security & Consultancy</h3>
                <p>Tailored security strategies and expert consultation for your specific business requirements.</p>
            </div>
            <div class="service-card">
                <h3>Access Card Control Systems</h3>
                <p>Advanced access control solutions for secure entry management and visitor tracking.</p>
            </div>
            <div class="service-card">
                <h3>Special Operations Security</h3>
                <p>Specialized security services for high-risk operations and critical missions.</p>
            </div>
            <div class="service-card">
                <h3>High Risk Facility Security</h3>
                <p>Enhanced security protocols for facilities requiring maximum protection levels.</p>
            </div>
            <div class="service-card">
                <h3>Anti-Terrorism Protection & Security</h3>
                <p>Comprehensive anti-terrorism measures and threat assessment services.</p>
            </div>
            <div class="service-card">
                <h3>Personal Protection Services</h3>
                <p>Individual and VIP protection services with trained bodyguards and security personnel.</p>
            </div>
        </div>
    </div>
</section>

<!-- Jobs Section -->
<section id="jobs" class="jobs">
    <div class="container">
        <h2>Join Our Elite Team of Security Professionals</h2>
        <p class="section-subtitle">Become part of our 1500+ strong officer network dedicated to safeguarding people and property across the nation.</p>
        <div class="jobs-grid">
            <div class="job-card">
                <div class="job-image">
                    <img src="<?php echo URL_ROOT; ?>/img/premise-officer.png" alt="Premise Officer">
                    <div class="job-banner">As a Premise Officer</div>
                </div>
                <a href="<?php echo URL_ROOT; ?>/home/premise_officer" class="view-btn">View</a>
            </div>
            <div class="job-card">
                <div class="job-image">
                    <img src="<?php echo URL_ROOT; ?>/img/care-taker.png" alt="Care Taker">
                    <div class="job-banner">As a Care-Taker</div>
                </div>
                <a href="<?php echo URL_ROOT; ?>/home/care_taker" class="view-btn">View</a>
            </div>
            <div class="job-card">
                <div class="job-image">
                    <img src="<?php echo URL_ROOT; ?>/img/mobile-rider.png" alt="Mobile Rider">
                    <div class="job-banner">As a Mobile Rider</div>
                </div>
                <a href="<?php echo URL_ROOT; ?>/home/mobile_rider" class="view-btn">View</a>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section id="GetService"class="mission" style="background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('<?php echo URL_ROOT; ?>/img/get-service.png');">
    <div class="container">
        <div class="mission-content">
            <h2>Your Safety, Our Mission</h2>
            <p>Partner with RED FORCE for trusted, technology-driven security solutions tailored to protect your people, property, and peace of mind.</p>
            <a href="<?php echo URL_ROOT; ?>/home/Service" class="btn btn-primary">Get Service</a>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact">
    <div class="container">
        <h2>Contact Us</h2>
        <div class="contact-info">
            <div class="contact-item">
                <h3>Address</h3>
                <p>46/2, Colombha Thanthri Mawatha, EthulKotte Kotte</p>
            </div>
            <div class="contact-item">
                <h3>Email</h3>
                <p>redforcesolutions@outlook.com</p>
            </div>
            <div class="contact-item">
                <h3>Phone</h3>
                <p>Office: +94-11-286-6490</p>
                <p>Mobile: +94-71-403-3502</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-info">
                <p><strong>Address:</strong> 46/2, Colombha Thanthri Mawatha, EthulKotte Kotte</p>
                <p><strong>Office:</strong> +94-11-286-6490 | <strong>Mobile:</strong> +94-71-403-3502</p>
                <p><strong>Email:</strong> redforcesolutions@outlook.com</p>
            </div>
            <div class="footer-copyright">
                <p>&copy; 2025 REDFORCE Security Services (Pvt) Limited. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="<?php echo URL_ROOT; ?>/js/home/main.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
