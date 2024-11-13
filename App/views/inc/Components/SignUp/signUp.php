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
        <input type="text" name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>

        <!----Contact Number---->
        <div class="form-input-title">Contact Number</div>
        <input type="text" name="number" id="number" value="<?php echo isset($data['number']) ? $data['number'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['number_err']) ? $data['number_err'] : ''; ?></span>

        <!----NIC---->
        <div class="form-input-title">NIC</div>
        <input type="text" name="nic" id="nic" value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['nic_err']) ? $data['nic_err'] : ''; ?></span>

        <!----Address---->
        <div class="form-input-title">Address</div>
        <input type="text" name="address" id="address" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>

        <!----Email---->
        <div class="form-input-title">Email</div>
        <input type="text" name="email" id="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>

        <!----Password---->
        <div class="form-input-title">Password</div>
        <input type="text" name="password" id="password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>

        <!----Confirm Password---->
        <div class="form-input-title">Confirm Password</div>
        <input type="text" name="confirm" id="confirm" value="<?php echo isset($data['confirm']) ? $data['confirm'] : ''; ?>">
        <span class="form-invalid"><?php echo isset($data['confirm_err']) ? $data['confirm_err'] : ''; ?></span>

        <p><input type="checkbox">I agree to the Terms of Service and Privacy Policy.</p>

        <center><input class="button" type="submit" value="Register"></center>


    </form>
 </div>





















