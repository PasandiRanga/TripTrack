
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About Us - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/Footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/GuestUser/aboutus.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

</head>
<body>
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Counter animation for numbers section
            function animateCounters() {
                const counters = document.querySelectorAll('.number-count');
                const speed = 200;
                
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(animateCounters, 1);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                });
            }
            
            // Check if element is in viewport
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
            
            // Start animation when scrolled into view
            const numbersSection = document.querySelector('.numbers-section');
            let animated = false;
            
            window.addEventListener('scroll', function() {
                if (!animated && isInViewport(numbersSection)) {
                    animateCounters();
                    animated = true;
                }
            });
        });
    </script>

    <?php
    $userRole = $_SESSION['userRole'] ?? 'GuestUser';
    
    $data = [
        'currentController' => 'GuestPages',
        'currentMethod' => 'about',
        'userRole' => $userRole
    ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    <div class="hero-container">
        <div class="header-container">
            <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        </div>
        
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1>Transforming Travel Across Sri Lanka</h1>
                <p>Discover the story behind Trip Track and our mission to revolutionize bus travel with technology and exceptional service.</p>
                <div class="hero-buttons">
                    <a href="<?php echo URLROOT; ?>/users/register" class="hero-btn primary-btn">Get Started</a>
                    <a href="#learn-more" class="hero-btn secondary-btn">Learn More</a>
                </div>
            </div>
        </div>
        
        <div class="about-container">
            <!-- About Section -->
            <div id="learn-more" class="section-title">
                <h2>About Trip Track</h2>
                <p>Your reliable travel companion for bus journeys across Sri Lanka</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <h3>Your Partner in Comfortable Travel</h3>
                    <p>Founded with a vision to transform the way people travel in Sri Lanka, <span class="highlight">Trip Track</span> has grown to become the country's most trusted bus ticket booking platform. We're dedicated to making your travel experience as seamless and enjoyable as possible.</p>
                    <p>With our user-friendly platform, you can book bus tickets, check schedules, and plan your journeys with ease. We partner with the most reliable bus operators across the country to ensure that you have access to comfortable and punctual services for all your travel needs.</p>
                    <p>At <span class="highlight">Trip Track</span>, we understand that travel is more than just moving from one place to another — it's about experiences, connections, and memories. That's why we go beyond just ticket booking to provide a comprehensive travel solution.</p>
                </div>
                <div class="about-image">
                    <div class="about-img-container">
                        <img src="<?php echo URLROOT; ?>/Public/images/logo2.png" alt="Trip Track">
                    </div>
                </div>
            </div>
            
            <!-- Mission & Vision -->
            <div class="mission-vision">
                <div class="mission-box">
                    <div class="box-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide a seamless, reliable, and user-friendly platform that connects travelers with quality bus services across Sri Lanka, making travel planning easy and stress-free for everyone.</p>
                    <p>We aim to continuously improve the travel experience through innovation, exceptional customer service, and strategic partnerships with the best transport providers in the country.</p>
                </div>
                <div class="vision-box">
                    <div class="box-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To revolutionize the way people travel across Sri Lanka by harnessing the power of technology to create the most comprehensive and accessible transportation network in the country.</p>
                    <p>We envision a future where planning and booking travel is effortless, allowing our users to focus on what matters most — enjoying their journey and creating lasting memories.</p>
                </div>
            </div>
            
            <!-- Values Section -->
            <div class="values-section">
                <div class="section-title">
                    <h2>Our Core Values</h2>
                    <p>The principles that guide everything we do</p>
                </div>
                
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Reliability</h3>
                        <p>We are committed to providing accurate information and dependable service that our users can count on for every journey.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3>Innovation</h3>
                        <p>We continuously evolve our platform with cutting-edge technology to improve the travel experience for all our users.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Customer Focus</h3>
                        <p>Your satisfaction drives everything we do. We listen to feedback and make improvements based on your needs.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Integrity</h3>
                        <p>We operate with honesty and transparency in all our dealings with customers, partners, and employees.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Accessibility</h3>
                        <p>We strive to make travel accessible to everyone by offering a range of options to suit different needs and budgets.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Excellence</h3>
                        <p>We aim for excellence in everything we do, constantly raising the bar for quality service in the travel industry.</p>
                    </div>
                </div>
            </div>
            
            <!-- Numbers Section -->
            <div class="numbers-section">
                <div class="numbers-container">
                    <div class="number-item">
                        <div class="number-count" data-target="50000">0</div>
                        <div class="number-label">Happy Travelers</div>
                    </div>
                    <div class="number-item">
                        <div class="number-count" data-target="200">0</div>
                        <div class="number-label">Bus Routes</div>
                    </div>
                    <div class="number-item">
                        <div class="number-count" data-target="25">0</div>
                        <div class="number-label">Districts Covered</div>
                    </div>
                    <div class="number-item">
                        <div class="number-count" data-target="98">0</div>
                        <div class="number-label">Satisfaction Rate (%)</div>
                    </div>
                </div>
            </div>
            
            <!-- Team Section -->
            <div class="team-section">
                <div class="section-title">
                    <h2>Leadership</h2>
                    <p>Meet the visionary behind Trip Track</p>
                </div>
                
                <div class="team-member">
                    <div class="team-photo">
                        <img src="<?php echo URLROOT; ?>/Public/images/owner.jpg" alt="Nadika Perera">
                    </div>
                    <div class="team-info">
                        <h3>Nadika Perera</h3>
                        <span class="position">Founder & Chief Executive Officer</span>
                        <p>Nadika Perera is the visionary behind <span class="highlight">Trip Track</span>. With a passion for technology and a deep understanding of the transportation industry, Nadika founded <span class="highlight">Trip Track</span> to address the challenges faced by travelers in Sri Lanka.</p>
                        <p>His extensive experience in both the tech and transportation sectors has been instrumental in developing a platform that truly understands and addresses the needs of modern travelers.</p>
                        <p>Under Nadika's leadership, <span class="highlight">Trip Track</span> has grown into a trusted name in the travel industry, known for its commitment to customer satisfaction and innovation. When not working on improving <span class="highlight">Trip Track</span>, Nadika enjoys traveling and exploring new places, always on the lookout for ways to make travel more accessible and enjoyable for everyone.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonials Section -->
            <div class="testimonials-section">
                <div class="section-title">
                    <h2>What Our Users Say</h2>
                    <p>Experiences shared by travelers who use Trip Track</p>
                </div>
                
                <div class="testimonials-container">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>Trip Track has completely transformed how I travel around Sri Lanka. The booking process is seamless, and I love getting updates about my journey in real-time.</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="/api/placeholder/60/60" alt="User Avatar">
                            </div>
                            <div class="author-info">
                                <h4>Dinesh Jayawardena</h4>
                                <span>Regular Traveler</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>As someone who travels frequently for work, Trip Track has been a game-changer. I can quickly compare different routes and times, and their customer service is exceptional.</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="/api/placeholder/60/60" alt="User Avatar">
                            </div>
                            <div class="author-info">
                                <h4>Malini Fernando</h4>
                                <span>Business Traveler</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>Planning trips with my family used to be stressful until I discovered Trip Track. Now I can book multiple tickets at once and have all the information I need in one place.</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="/api/placeholder/60/60" alt="User Avatar">
                            </div>
                            <div class="author-info">
                                <h4>Roshan Gunasekera</h4>
                                <span>Family Traveler</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <div class="cta-content">
                    <h2>Ready to Experience Better Travel?</h2>
                    <p>Join thousands of satisfied travelers who rely on Trip Track for their journeys across Sri Lanka. Book your next trip today and discover the difference.</p>
                    <a href="<?php echo URLROOT; ?>/users/register" class="cta-btn">Start Your Journey</a>
                </div>
            </div>
        </div>
        
        <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>
    </div>
</body>
</html>