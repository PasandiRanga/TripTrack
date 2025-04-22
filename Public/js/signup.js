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

// Initialize icons as fa-eye-slash (password hidden initially)
togglePassword.classList.remove("fa-eye");
togglePassword.classList.add("fa-eye-slash");

toggleConfirmPassword.classList.remove("fa-eye");
toggleConfirmPassword.classList.add("fa-eye-slash");

togglePassword.addEventListener("click", () => {
    // Toggle password visibility
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    
    // Toggle the icon - when showing password (text), use fa-eye
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
});

toggleConfirmPassword.addEventListener("click", () => {
    // Toggle confirm password visibility
    const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
    confirmPasswordField.setAttribute("type", type);
    
    // Toggle the icon - when showing password (text), use fa-eye
    toggleConfirmPassword.classList.toggle("fa-eye");
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



/*
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

// Helper function to display error message for a field
function displayError(fieldName, message) {
    const errorSpan = document.querySelector(`[name="${fieldName}"]`).nextElementSibling;
    if (errorSpan && errorSpan.classList.contains('form-invalid')) {
        errorSpan.textContent = message;
    }
}

// Form validation function
function validateForm() {
    let isValid = true;
    // Clear previous error messages
    document.querySelectorAll('.form').forEach(el => el.textContent = '');
    
    // Validate name
    const name = document.getElementById('name').value;
    if (!name.trim()) {
        displayError('name', 'Please enter a name');
        isValid = false;
    }
    
    // Validate number
    const number = document.getElementById('number').value;
    if (!number.trim()) {
        displayError('number', 'Please enter a contact number');
        isValid = false;
    } else if (!/^\d+$/.test(number)) {
        displayError('number', 'The contact number must contain only numbers');
        isValid = false;
    } else if (number.length !== 10) {
        displayError('number', 'The contact number must be exactly 10 digits long');
        isValid = false;
    } else if (number[0] !== '0') {
        displayError('number', 'The contact number must start with 0');
        isValid = false;
    }
    
    // Validate NIC
    const nic = document.getElementById('nic').value;
    if (!nic.trim()) {
        displayError('nic', 'Please enter a NIC');
        isValid = false;
    } else if (!(/^\d{12}$/.test(nic) || /^\d{9}V$/.test(nic))) {
        displayError('nic', 'NIC must be exactly 12 digits or 9 digits followed by "V" at the end');
        isValid = false;
    }
    
    // Validate address
    const address = document.getElementById('address').value;
    if (!address.trim()) {
        displayError('address', 'Please enter an address');
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email').value;
    if (!email.trim()) {
        displayError('email', 'Please enter an email');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        displayError('email', 'Please enter a valid email format (e.g., abc@gmail.com)');
        isValid = false;
    }
    
    // Validate password
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm').value;
    
    if (!password) {
        displayError('password', 'Please enter a password');
        isValid = false;
    } else if (password.length < 8) {
        displayError('password', 'Password must be at least 8 characters long');
        isValid = false;
    } else if (!/[A-Z]/.test(password)) {
        displayError('password', 'Password must contain at least one uppercase letter');
        isValid = false;
    } else if (!/[a-z]/.test(password)) {
        displayError('password', 'Password must contain at least one lowercase letter');
        isValid = false;
    } else if (!/\d/.test(password)) {
        displayError('password', 'Password must contain at least one number');
        isValid = false;
    } else if (!/[\W_]/.test(password)) {
        displayError('password', 'Password must contain at least one special character');
        isValid = false;
    }
    
    // Validate confirm password
    if (!confirm) {
        displayError('confirm', 'Please confirm the password');
        isValid = false;
    } else if (password !== confirm) {
        displayError('confirm', 'Passwords do not match');
        isValid = false;
    }
    
    // Validate terms checkbox
    const terms = document.getElementById('terms');
    if (!terms.checked) {
        alert('Please agree to the Terms of Service and Privacy Policy');
        isValid = false;
    }
    
    console.log("Form validation result:", isValid);
    return isValid;
}

document.getElementById("Register").addEventListener("click", function(e) {
    e.preventDefault();
    console.log("Register button clicked");
    
    // Validate form fields first
    const formIsValid = validateForm();
    
    if (formIsValid) {
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
                console.log('OTP sent successfully');
            } else {
                alert(data.message || 'Failed to send OTP. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            alert('An error occurred while sending OTP. Please try again.');
        });
    } else {
        console.log("Form validation failed - not sending OTP request");
    }
});

// Verify OTP button click handler
document.getElementById("verify").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.querySelector("form");
    const enteredOTP = document.getElementById("otp").value;
    
    if (!enteredOTP.trim()) {
        document.querySelector('input[name="otp"] + .form-invalid').textContent = 'Please enter the OTP';
        return;
    }
    
    // Add OTP to form data
    let otpInput = document.querySelector('input[name="entered_otp"]');
    if (!otpInput) {
        otpInput = document.createElement('input');
        otpInput.type = 'hidden';
        otpInput.name = 'entered_otp';
        form.appendChild(otpInput);
    }
    otpInput.value = enteredOTP;
    
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
*/