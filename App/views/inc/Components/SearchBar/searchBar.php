<?php
    include APPROOT . '/views/inc/Components/Button/button.php';
?>

<div class="search-bar-container">
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <input type="text" placeholder="From" class="search-input">
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-bus"></i></div>
        <input type="text" placeholder="To" class="search-input">
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        <input type="date" class="search-input">
    </div>
    <div class="input-group">
        <div class="icon"><i class="fas fa-clock"></i></div>
        <input type="time" class="search-input">
    </div>
    <?php echo createButton(1, 'Search'); ?> <!-- Use Button 1 -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', (event) => {
            if (event.target && event.target.matches('.button-style-1')) {
                const from = document.querySelector('input[placeholder="From"]').value;
                const to = document.querySelector('input[placeholder="To"]').value;
                const travelDate = document.querySelector('input[type="date"]').value;
                const time = document.querySelector('input[type="time"]').value;
                
                console.log("From:", from);
                console.log("To:", to);
                console.log("Travel Date:", travelDate);
                console.log("Time:", time);
            }
        });
    });
</script>
