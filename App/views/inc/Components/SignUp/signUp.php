 <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/SignUp/signUp.css?v=<?php echo time(); ?>">
 
 <div class="form-container">
    <div class="form-header">
        <center><img class="logo2" src="<?php echo URLROOT; ?>/public/images/logo2.png" alt="Logo">
        </center>
    </div>
    <h3>filling all fields are mandotary</h3>

    <form action="<?php echo URLROOT ?>/GuestPages/GuestSignUp" method="POST">

        <!----Full Name---->
        <div class="form-input-title">Full Name</div>
        <input type="text" name="name" id="name" value="<?php echo $data['name']; ?>">
        <span class="form-invalid"><?php echo $data['name_err']; ?></span>

        <!----Contact Number---->
        <div class="form-input-title">Contact Number</div>
        <input type="text" name="number" id="number" value="<?php echo $data['number']; ?>">
        <span class="form-invalid"><?php echo $data['number_err']; ?></span>

        <!----NIC---->
        <div class="form-input-title">NIC</div>
        <input type="text" name="nic" id="nic" value="<?php echo $data['nic']; ?>">
        <span class="form-invalid"><?php echo $data['nic_err']; ?></span>

        <!----Address---->
        <div class="form-input-title">Address</div>
        <input type="text" name="address" id="address" value="<?php echo $data['address']; ?>">
        <span class="form-invalid"><?php echo $data['address_err']; ?></span>

        <!----Email---->
        <div class="form-input-title">Email</div>
        <input type="text" name="email" id="email" value="<?php echo $data['email']; ?>">
        <span class="form-invalid"><?php echo $data['email_err']; ?></span>

        <!----Password---->
        <div class="form-input-title">Password</div>
        <input type="password" name="password" id="password" value="<?php echo $data['password']; ?>">
        <span class="form-invalid"><?php echo $data['password_err']; ?></span>

        <!----Confirm Password---->
        <div class="form-input-title">Confirm Password</div>
        <input type="password" name="confirm" id="confirm" value="<?php echo $data['confirm']; ?>">
        <span class="form-invalid"><?php echo $data['confirm_err']; ?></span>

        <input type="checkbox">I agree to the Terms of Service and Privacy Policy.

        <center><input class="button" type="submit" value="Register"></center>


    </form>
 </div>





















