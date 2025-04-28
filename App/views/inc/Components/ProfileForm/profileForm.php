<div class="updateBox <?php echo isset($_SESSION['profile_data']) && $_SESSION['profile_data']['has_errors'] ? '' : 'hidden'; ?>" id="updateBox">
    <div class="updateBoxContent">
        <form class="profileForm" action="<?php echo URLROOT ?>/RegisteredPages/profileUpdate" method="POST">
            <?php 
            $formData = isset($_SESSION['profile_data']) ? $_SESSION['profile_data'] : $profile;
            $name = isset($formData['name']) ? $formData['name'] : $profile['Name'];
            $email = isset($formData['email']) ? $formData['email'] : $profile['Email'];
            $contact_number = isset($formData['contact_number']) ? $formData['contact_number'] : $profile['Contact_number'];
            $nic = isset($formData['nic']) ? $formData['nic'] : $profile['NIC'];
            $address = isset($formData['address']) ? $formData['address'] : $profile['Address'];
            ?>
            
            <div class="detail">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <?php if(isset($_SESSION['profile_data']['name_err']) && !empty($_SESSION['profile_data']['name_err'])) : ?>
                    <span class="error"><?php echo $_SESSION['profile_data']['name_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="detail">
                <label>Email Address</label>
                <input type="mail" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if(isset($_SESSION['profile_data']['email_err']) && !empty($_SESSION['profile_data']['email_err'])) : ?>
                    <span class="error"><?php echo $_SESSION['profile_data']['email_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="detail">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
                <?php if(isset($_SESSION['profile_data']['contact_number_err']) && !empty($_SESSION['profile_data']['contact_number_err'])) : ?>
                    <span class="error"><?php echo $_SESSION['profile_data']['contact_number_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="detail">
                <label>NIC</label>
                <input type="text" name="nic" value="<?php echo htmlspecialchars($nic); ?>">
                <?php if(isset($_SESSION['profile_data']['nic_err']) && !empty($_SESSION['profile_data']['nic_err'])) : ?>
                    <span class="error"><?php echo $_SESSION['profile_data']['nic_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="detail">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
                <?php if(isset($_SESSION['profile_data']['address_err']) && !empty($_SESSION['profile_data']['address_err'])) : ?>
                    <span class="error"><?php echo $_SESSION['profile_data']['address_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <button class="done">Done</button>
        </form>
        <div class="close-btn" onclick="closeUpdateBox()">×</div>
    </div>
</div>

<script>
    function showUpdateBox() {
        document.getElementById('updateBox').classList.remove('hidden');
    }
    
    function closeUpdateBox() {
        document.getElementById('updateBox').classList.add('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(isset($_SESSION['profile_data']) && $_SESSION['profile_data']['has_errors']) : ?>
            showUpdateBox();
        <?php endif; ?>
    });
</script>

<?php
if (isset($_SESSION['profile_data'])) {
    unset($_SESSION['profile_data']);
}
?>