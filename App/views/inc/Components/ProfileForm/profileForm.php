<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/CSS/Components/ProfileForm/profileForm.css?v=<?php echo time(); ?>">


<div class="updateBox hidden" id="updateBox">
    <div class="updateBoxContent">
        <form class="profileForm" action="<?php echo URLROOT ?>/RegisteredPages/profileUpdate" method="POST">
            <div class="detail">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo $profile['Name']; ?>">
                </div>
                <div class="detail">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $profile['Email']; ?>">
                </div>
                <div class="detail">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" value="<?php echo $profile['Contact_number']; ?>">
                </div>
                <div class="detail">
                    <label>NIC</label>
                    <input type="text" name="nic" value="<?php echo $profile['NIC']; ?>">
                </div>
                <div class="detail">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo $profile['Address']; ?>">
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
 </script>