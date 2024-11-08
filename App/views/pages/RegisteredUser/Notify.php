<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/Public/CSS/RegisteredUser/Home.css?v=<?php echo time(); ?>">
    <title>Notify</title>
</head>
<body>

		<div class="notifi-box" id="box">
			<h2>Notifications <span>17</span></h2>
			<div class="notifi-item">
				<div class="text">
				   <h4>Elias Abdurrahman</h4>
				   <p>@lorem ipsum dolor sit amet</p>
			    </div> 
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>John Doe</h4>
				   <p>@lorem ipsum dolor sit amet</p>
			    </div> 
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>Emad Ali</h4>
				   <p>@lorem ipsum dolor sit amet</p>
			    </div> 
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>Ekram Abu </h4>
				   <p>@lorem ipsum dolor sit amet</p>
			    </div> 
			</div>

			<div class="notifi-item">
				<div class="text">
				   <h4>Jane Doe</h4>
				   <p>@lorem ipsum dolor sit amet</p>
			    </div> 
			</div>

		</div>

        <script>
            var box  = document.getElementById('box');
            var down = false;


            function toggleNotifi(){
                if (down) {
                    box.style.height  = '0px';
                    box.style.opacity = 0;
                    down = false;
                }else {
                    box.style.height  = '510px';
                    box.style.opacity = 1;
                    down = true;
                }
            }
        </script>
</body>
</html>