<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/imageSlide/imageSlide.css?v=<?php echo time(); ?>">
  
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
      <span class="slide">Seamless Booking</span>
      <span class="slide">Travel Hasslefree</span>
      <span class="slide">Book Any Time</span>
    </div>
  </div>

  <script>
    const imagesWrapper = document.querySelector('.images-wrapper');
    const slides = document.querySelectorAll('.text-slider .slide');
    const images = document.querySelectorAll('.image');
    let currentIndex = 0;

    function showSlide(index) {
      // Slide images one by one
      const imageWidth = images[0].clientWidth;
      imagesWrapper.style.transform = `translateX(-${index * imageWidth}px)`;

      // Update text
      slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) slide.classList.add('active');
      });
    }

    function startSlideshow() {
      showSlide(currentIndex);
      currentIndex = (currentIndex + 1) % images.length;
    }

    setInterval(startSlideshow, 3000); // Slide duration in ms
    startSlideshow(); // Start the slideshow on page load
  </script>
</body>
</html>
