<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/ImageSlide/imageSlide.css">
  <title>Sliding Images with Text</title>
</head>
<body>
  <div class="image-container">
    <div class="images-wrapper">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/s4.jpg" alt="image1">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img1.jpg" alt="image2">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img2.jpg" alt="image3">
      
    </div>
    <div class="dark-layer"></div>
    <div class="text-slider">
      <span class="slide">Welcome to the Future</span>
      <span class="slide">Innovation Starts Here</span>
      <span class="slide">Discover Limitless Opportunities</span>
      <span class="slide">Join Us Today!</span>
    </div>
  </div>

  <script>
    const slides = document.querySelectorAll('.text-slider .slide');
    let currentIndex = 0;

    function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) slide.classList.add('active');
    });
    }

    function startSlideshow() {
    showSlide(currentIndex);
    currentIndex = (currentIndex + 1) % slides.length;
    }

    setInterval(startSlideshow, 3000); // Match image sliding duration
    startSlideshow(); // Start the slideshow on page load
  </script>
</body>
</html>
