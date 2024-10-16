<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bus Layout <?php echo SITENAME; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/RegisteredUser/Bus_layout.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/header/header.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/navbar/navbar.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
    <!-- Header Placeholder -->
    <script>
        var userRole = <?php echo json_encode($_SESSION['userRole'] ?? 'RegisteredUser'); ?>;
        localStorage.setItem('userRole', userRole); // Ensure this is set before the header loads
    </script>
    <!-- Header and Navbar -->
    <?php require APPROOT.'/views/inc/Components/Header/header.php'; ?>
    <?php require APPROOT.'/views/inc/Components/NavBar/navbar.php'; ?>

   
    <div class="container">
        <div class="left-column">
            <div class="box left-box">
                <div class="abc">
                    <div class="left-content">
                        <div class="route">Colombo >>> Kurunegala</div>
                        <div class="detail">
                            29th August - 6.00AM<br />
                            Route number: 87<br />
                            Bus number: ht76543
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="service">Average</div>
                        <div class="extra">Normal service - non-AC<br />
                        Available seats: 49/54</div>
                    </div>
                </div>

                <div class="container">
                    <figure class="image-caption-container">
                        <img src="./../../Images/bus.jpg">
                        <div class="caption">
                            Colombo For Bus stand<br/>7.45AM
                        </div>
                    </figure>
                    <hr>
                    <figure class="image-caption-container">
                        <img src="./../../Images/Map.jpg">
                        <div class="caption">
                            Shripura<br/>3.45PM
                        </div>
                    </figure>
                </div>
                
                <div class="container">
                    <button class="more">View bus stops and times</button>
                    <button class="more">View rating and reviews</button>
                    <div style="font-weight: bold; font-size: 30px;">LKR 1400</div>
                </div>
            </div>

            <br/>

            <div class="box left-box" style="background-color:#C5F2E9;">
                <div class="form-container">
                    <form id="bookingForm">
                        <label for="seats">Seats Layout:</label>
                        <input type="text" id="seats" name="seats" required placeholder="Please select the seats">
                        
                        <label for="total">Seats:</label>
                        <input type="text" id="total" name="total" required placeholder="Total">
            
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
            
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" required>
            
                        <label for="contact">Contact No:</label>
                        <input type="tel" id="contact" name="contact" pattern="[0-9]{10}" required>
            
                        <label for="nic">NIC No:</label>
                        <input type="text" id="nic" name="nic" required>
            
                        <label for="destination">Destination:</label>
                        <input type="text" id="destination" name="destination" required>
            
                        <label for="payment">Payment method:</label>
                        <div class="payment-options">
                            <label><input type="radio" name="payment" value="Cash"> Cash</label>
                            <label><input type="radio" name="payment" value="Online"> Online</label>
                        </div>
            
                        <label for="ticket">Receive ticket via:</label>
                        <div class="ticket-options">
                            <label><input type="radio" name="ticket" value="Cash"> Cash</label>
                            <label><input type="radio" name="ticket" value="Online"> Online</label>
                        </div>
                        
                        <button type="submit" class="proceedButton">Proceed</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="right-column" id="right-column">
            <div class="box right-box">
                <!-- Seat Layout -->
                <div class="button-container">
                    <button class="number-button">1</button>
                    <button class="number-button">2</button>
                    <button class="disable">51</button>
                    <button class="number-button">19</button>
                    <button class="number-button">20</button>
                    <button class="number-button">21</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">3</button>
                    <button class="number-button">4</button>
                    <button class="disable">51</button>
                    <button class="number-button">22</button>
                    <button class="number-button">23</button>
                    <button class="number-button">24</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">5</button>
                    <button class="number-button">6</button>
                    <button class="disable">51</button>
                    <button class="number-button">25</button>
                    <button class="number-button">26</button>
                    <button class="number-button">27</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">7</button>
                    <button class="number-button">8</button>
                    <button class="disable">51</button>
                    <button class="number-button">28</button>
                    <button class="number-button">29</button>
                    <button class="number-button">30</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">9</button>
                    <button class="number-button">10</button>
                    <button class="disable"></button>
                    <button class="number-button">31</button>
                    <button class="number-button">32</button>
                    <button class="number-button">33</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">11</button>
                    <button class="number-button">12</button>
                    <button class="disable"></button>
                    <button class="number-button">34</button>
                    <button class="number-button">35</button>
                    <button class="number-button">36</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">13</button>
                    <button class="number-button">14</button>
                    <button class="disable"></button>
                    <button class="number-button">37</button>
                    <button class="number-button">38</button>
                    <button class="number-button">39</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">15</button>
                    <button class="number-button">16</button>
                    <button class="disable"></button>
                    <button class="number-button">40</button>
                    <button class="number-button">41</button>
                    <button class="number-button">42</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">17</button>
                    <button class="number-button">18</button>
                    <button class="disable"></button>
                    <button class="number-button">43</button>
                    <button class="number-button">44</button>
                    <button class="number-button">45</button>
                </div><br/>

                <div class="button-container">
                    <button class="disable"></button>
                    <button class="disable"></button>
                    <button class="disable"></button>
                    <button class="number-button">46</button>
                    <button class="number-button">47</button>
                    <button class="number-button">48</button>
                </div><br/>

                <div class="button-container">
                    <button class="number-button">49</button>
                    <button class="number-button">50</button>
                    <button class="number-button">51</button>
                    <button class="number-button">52</button>
                    <button class="number-button">53</button>
                    <button class="number-button">54</button>
                </div><br/>
            </div>

            <script>
                document.getElementById('seats').addEventListener('click', function() {
                    document.getElementById('right-column').style.display = 'block';
                });
            </script>
</body>
</html>
