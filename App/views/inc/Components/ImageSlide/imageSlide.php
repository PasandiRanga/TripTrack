<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/imageSlide/imageSlide.css?v=<?php echo time(); ?>">
  <title>Image and Text Slider</title>
</head>
<body>
  <div class="image-container">
    <div class="images-wrapper">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img5.jpg" alt="image5">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img6.jpg" alt="image6">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img8.jpg" alt="image8">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img2.jpg" alt="image2">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img10.jpg" alt="image10">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img12.jpg" alt="image12">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img15.jpg" alt="image15">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img17.jpg" alt="image17">
      <img class="image" src="<?php echo URLROOT; ?>/public/images/imageSlide/img19.jpg" alt="image19">
    </div>

    <div class="dark-layer"></div>
    <div class="text-slider">
      <span class="slide"><span class="slide1">No lines, no stress</span><span class="slide2">&nbsp;just click and go!</span></span>
      <span class="slide"><span class="slide1">Hassle-free booking,</span><span class="slide2"> smooth journeys.</span></span>
      <span class="slide"><span class="slide1">Your journey, </span><span class="slide2">our priority.</span></span>
      <span class="slide"><span class="slide1">Seamless travel solutions </span><span class="slide2">at your fingertips.</span></span>
      <span class="slide"><span class="slide1">Your travel companion,</span><span class="slide2"> 24/7.</span></span>
      <span class="slide"><span class="slide1">The road to convenience </span><span class="slide2">starts here.</span></span>
      <span class="slide"><span class="slide1">Book. Track. Relax.</span></span>
      <span class="slide"><span class="slide1">No lines,</span><span class="slide2"> no stress</span></span>
      <span class="slide"><span class="slide1">The future of bus travel,</span><span class="slide2">today!</span></span>
    </div>
  </div>

  <script>
    const imagesWrapper = document.querySelector('.images-wrapper');
    const slides = document.querySelectorAll('.text-slider .slide');
    const images = document.querySelectorAll('.image');
    let currentIndex = 0;

    function showSlide(index) {
      // Update image position
      const imageWidth = images[0].clientWidth;
      imagesWrapper.style.transform = `translateX(-${index * imageWidth}px)`;

      // Handle text transition
      slides.forEach((slide, i) => {
        slide.style.opacity = '0';
        slide.style.transform = 'translateY(20px)';
        if (i === index) {
          slide.style.opacity = '1';
          slide.style.transform = 'translateY(0)';
        }
      });
    }

    function startSlideshow() {
      showSlide(currentIndex);
      currentIndex = (currentIndex + 1) % images.length;
    }

    setInterval(startSlideshow, 3000);
    startSlideshow();
  </script>
</body>
</html>
