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
    </script>

    <?php
        $userRole = $_SESSION['userRole'] ?? 'GuestUser';
    ?>

    <?php
        $data = [
            'currentController' => 'GuestPages', // Adjust this based on your controller
            'currentMethod' => 'about', // Adjust this based on the method
            'userRole' => $userRole
        ];
    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    <div class="hero-container">
    
        <br/>
        <div class="header-container">
            <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
        </div>
        
        <div class="hero-section" style="background: url('<?php echo URLROOT; ?>/Public/images/aboutPage.jpg') no-repeat center center; background-size: cover;">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1><span class="typing-text">Transforming Travel Across Sri Lanka</span></h1>
                <p>Discover the story behind Trip Track and our mission to revolutionize bus travel with technology and exceptional service.</p>
                <div class="hero-buttons">
                    <a href="<?php echo URLROOT; ?>/GuestPages/home" class="hero-btn primary-btn">Get Started</a>
                    <a href="#learn-more" class="hero-btn secondary-btn">Learn More</a>
                </div>
            </div>
        </div>
        
        <div class="about-container">
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

            <div class="team-section">
                <div class="section-title">
                    <h2>Leadership</h2>
                    <p>Meet the visionary behind Trip Track</p>
                </div>
                
                <div class="team-member">
                <div class="left-column">
                    <div class="team-photo">
                    <img src="<?php echo URLROOT; ?>/Public/images/owner.jpg" alt="Nadika Perera">
                    </div>
                    <div class="team-name-role">
                    <h3>Nadika Perera</h3>
                    <span class="position">Founder & Chief Executive Officer</span>
                    </div>
                    <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="right-column">
                    <p>Nadika Perera is the visionary behind <span class="highlight">Trip Track</span>. With a passion for technology and a deep understanding of the transportation industry, Nadika founded <span class="highlight">Trip Track</span> to address the challenges faced by travelers in Sri Lanka.</p>
                    <p>His extensive experience in both the tech and transportation sectors has been instrumental in developing a platform that truly understands and addresses the needs of modern travelers.</p>
                    <p>Under Nadika's leadership, <span class="highlight">Trip Track</span> has grown into a trusted name in the travel industry, known for its commitment to customer satisfaction and innovation. When not working on improving <span class="highlight">Trip Track</span>, Nadika enjoys traveling and exploring new places, always on the lookout for ways to make travel more accessible and enjoyable for everyone.</p>
                </div>
                </div>

            </div>
            
            <div class="team-section">
                <div class="section-title">
                    <h2>Development Team</h2>
                    <p>Meet the talented developers and collaborators who built Trip Track</p>
                </div>
                
                <div class="team-member" style="margin-bottom: 40px;">
                    <div class="left-column">
                        <div class="team-photo">
                            <img src="<?php echo URLROOT; ?>/Public/images/placeholder.jpg" alt="Sahan Wickramasinghe">
                        </div>
                        <div class="team-name-role">
                            <h3>Sahan Wickramasinghe</h3>
                            <span class="position">Senior Backend Developer</span>
                        </div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="right-column">
                        <p>Sahan is our lead backend developer who architected the robust server-side infrastructure of <span class="highlight">Trip Track</span>. With expertise in PHP, MySQL, and API development, he designed the scalable booking system and user management features.</p>
                        <p>His experience in database optimization and security implementation ensures that all user data and transactions are handled safely and efficiently. Sahan's attention to detail in creating reliable backend services forms the backbone of our platform.</p>
                        <p>When not coding, Sahan enjoys exploring new web technologies and contributing to open-source projects in the transportation tech space.</p>
                    </div>
                </div>

                <div class="team-member" style="margin-bottom: 40px;">
                    <div class="left-column">
                        <div class="team-photo">
                            <img src="<?php echo URLROOT; ?>/Public/images/placeholder.jpg" alt="Kavitha Ranasinghe">
                        </div>
                        <div class="team-name-role">
                            <h3>Kavitha Ranasinghe</h3>
                            <span class="position">Frontend Developer & UI Specialist</span>
                        </div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                            <a href="#"><i class="fab fa-behance"></i></a>
                        </div>
                    </div>
                    <div class="right-column">
                        <p>Kavitha brings <span class="highlight">Trip Track's</span> user interface to life with her exceptional frontend development skills. Specializing in responsive design and user experience optimization, she ensures our platform works seamlessly across all devices.</p>
                        <p>Her expertise in CSS, JavaScript, and modern web standards has created an intuitive and visually appealing interface that makes bus booking simple and enjoyable for users of all technical backgrounds.</p>
                        <p>Kavitha is passionate about accessibility and continuously works to make <span class="highlight">Trip Track</span> usable by everyone, regardless of their abilities or device preferences.</p>
                    </div>
                </div>

                <div class="team-member" style="margin-bottom: 40px;">
                    <div class="left-column">
                        <div class="team-photo">
                            <img src="<?php echo URLROOT; ?>/Public/images/placeholder.jpg" alt="Ruwan Perera">
                        </div>
                        <div class="team-name-role">
                            <h3>Ruwan Perera</h3>
                            <span class="position">Database Administrator & Systems Analyst</span>
                        </div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-stack-overflow"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                    <div class="right-column">
                        <p>Ruwan is responsible for designing and maintaining the complex database systems that power <span class="highlight">Trip Track's</span> operations. His expertise in data modeling and database optimization ensures lightning-fast response times even during peak booking periods.</p>
                        <p>With a deep understanding of transportation logistics, Ruwan has created efficient data structures for managing routes, schedules, seat availability, and booking records across multiple bus operators throughout Sri Lanka.</p>
                        <p>His proactive approach to system monitoring and performance tuning keeps our platform running smoothly 24/7, providing reliable service to thousands of daily users.</p>
                    </div>
                </div>

                <div class="team-member" style="margin-bottom: 40px;">
                    <div class="left-column">
                        <div class="team-photo">
                            <img src="<?php echo URLROOT; ?>/Public/images/placeholder.jpg" alt="Dimantha Silva">
                        </div>
                        <div class="team-name-role">
                            <h3>Dimantha Silva</h3>
                            <span class="position">UX/UI Designer & Product Manager</span>
                        </div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-figma"></i></a>
                            <a href="#"><i class="fab fa-adobe"></i></a>
                        </div>
                    </div>
                    <div class="right-column">
                        <p>Dimantha is the creative force behind <span class="highlight">Trip Track's</span> user-centered design approach. Through extensive research and user testing, he has crafted an interface that perfectly balances functionality with aesthetic appeal.</p>
                        <p>His role extends beyond design to product management, where he coordinates feature development, gathers user feedback, and ensures that every update enhances the overall user experience.</p>
                        <p>Dimantha's background in design thinking and agile methodologies has been instrumental in creating a platform that truly serves the needs of Sri Lankan travelers.</p>
                    </div>
                </div>

                <div class="team-member" style="margin-bottom: 40px;">
                    <div class="left-column">
                        <div class="team-photo">
                            <img src="<?php echo URLROOT; ?>/Public/images/placeholder.jpg" alt="Priya Jayawardena">
                        </div>
                        <div class="team-name-role">
                            <h3>Priya Jayawardena</h3>
                            <span class="position">Quality Assurance Engineer & DevOps</span>
                        </div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-docker"></i></a>
                        </div>
                    </div>
                    <div class="right-column">
                        <p>Priya ensures that every feature of <span class="highlight">Trip Track</span> meets our high standards for quality and reliability. Her comprehensive testing strategies and automation frameworks catch issues before they reach our users.</p>
                        <p>Beyond quality assurance, Priya manages our deployment processes and server infrastructure, implementing continuous integration practices that allow us to deliver updates quickly and safely.</p>
                        <p>Her meticulous approach to testing and system reliability has earned the trust of users who depend on <span class="highlight">Trip Track</span> for their daily travel needs.</p>
                    </div>
                </div>
            </div>
            
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
    </div>
        <?php require APPROOT.'/views/inc/Components/Footer/footer.php'; ?>

    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'GuestUser'); ?>;
        localStorage.setItem('userRole', userRole);
        
        document.addEventListener('DOMContentLoaded', function() {
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
            
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
            
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
</body>
</html>