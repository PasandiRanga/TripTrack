// Profile image drag-and-drop
const dropArea = document.querySelector(".form-drag-area");
const dropText = document.querySelector(".description");
const browseButton = document.querySelector(".form_upload");
const inputPath = document.querySelector("#profile_image");
const placeholder = document.querySelector("#placeholder");
const validate = document.querySelector(".profile_image_validation");
let file;

// Browse option and upload functionality
browseButton.onclick = () => {
    inputPath.click();
};

inputPath.addEventListener("change", function () {
    file = this.files[0];
    showImage();
});

dropArea.addEventListener("dragover", (event) => {
    event.preventDefault();
    dropArea.classList.add("active");
    dropText.textContent = "Release to Upload the Image";
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("active");
    dropText.textContent = "Drag & Drop to Upload Image";
});

dropArea.addEventListener("drop", (event) => {
    event.preventDefault();
    file = event.dataTransfer.files[0];

    // Adding the file to the input element programmatically
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    inputPath.files = dataTransfer.files;
    showImage();
    dropArea.classList.remove("active");
});

function showImage() {
    const fileType = file.type;

    // Valid image extensions
    const validExtensions = ["image/jpeg", "image/jpg", "image/png"];

    if (validExtensions.includes(fileType)) {
        const fileReader = new FileReader();
        fileReader.onload = () => {
            const fileURL = fileReader.result;

            // Set image preview
            placeholder.setAttribute("src", fileURL);
        };

        fileReader.readAsDataURL(file);

        // Show validation tick
        validate.classList.add("active");
    } else {
            alert("This is not a valid image file. Please upload a JPEG, JPG, or PNG file.");
            dropArea.classList.remove("active");
    }
}

// Show password functionality
const togglePassword = document.querySelector("#togglePassword");
const passwordField = document.querySelector("#password");
const toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
const confirmPasswordField = document.querySelector("#confirm");

togglePassword.addEventListener("click", () => {
    // Toggle password visibility
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);

    // Toggle the icon
    togglePassword.classList.toggle("fa-eye-slash");
});

toggleConfirmPassword.addEventListener("click", () => {
    // Toggle confirm password visibility
    const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
    confirmPasswordField.setAttribute("type", type);

    // Toggle the icon
    toggleConfirmPassword.classList.toggle("fa-eye-slash");
});

// OTP Related Functions
function showConfirmBox() {
    document.getElementById("confirmBox").classList.remove("hidden");
}

function closeConfirmBox() {
    document.getElementById("confirmBox").classList.add("hidden");
}

document.getElementById("Register").addEventListener("click", function(e) {
    e.preventDefault();
    console.log("Rgister button clicked");
    // // Clear previous error messages
    // document.querySelectorAll('.form-invalid').forEach(el => el.textContent = '');

    // Validate form
    if (1) {
        const email = document.getElementById('email').value;
        console.log("Form validated successfully. Requesting OTP...");


        // Request OTP
        fetch(`${URLROOT}/GuestPages/sendOTP`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showConfirmBox(); // Show OTP popup
                console.log('OTP for testing:', data.debug_otp || 'Hidden'); // Debugging
            } else {
                alert(data.message || 'Failed to send OTP. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            alert('An error occurred while sending OTP. Please try again.');
        });
    }
});


// Verify OTP button click handler
document.getElementById("verify").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.querySelector("form");
    const enteredOTP = document.getElementById("otp").value;
    
    // Add OTP to form data
    const otpInput = document.createElement('input');
    otpInput.type = 'hidden';
    otpInput.name = 'entered_otp';
    otpInput.value = enteredOTP;
    form.appendChild(otpInput);
    
    // Submit the form
    form.submit();
});

// Resend OTP handler
document.querySelector(".resend-otp a").addEventListener("click", function(e) {
    e.preventDefault();
    const email = document.getElementById("email").value;
    
    fetch(`${URLROOT}/GuestPages/sendOTP`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('New OTP has been sent to your email');
        } else {
            alert(data.message || 'Failed to send OTP. Please try again.');
        }
    });
});