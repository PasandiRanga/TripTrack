const dropArea = document.querySelector(".form-drag-area");
const dropText = document.querySelector(".description");
const browseButton = document.querySelector(".form_upload");
const inputPath = document.querySelector("#profile_image");
const placeholder = document.querySelector("#placeholder");
const validate = document.querySelector(".profile_image_validation");
let file;

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
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    inputPath.files = dataTransfer.files;
    showImage();
    dropArea.classList.remove("active");
});

function showImage() {
    const fileType = file.type;

    const validExtensions = ["image/jpeg", "image/jpg", "image/png"];

    if (validExtensions.includes(fileType)) {
        const fileReader = new FileReader();
        fileReader.onload = () => {
            const fileURL = fileReader.result;

            placeholder.setAttribute("src", fileURL);
        };

        fileReader.readAsDataURL(file);

        validate.classList.add("active");
    } else {
            alert("This is not a valid image file. Please upload a JPEG, JPG, or PNG file.");
            dropArea.classList.remove("active");
    }
}

const togglePassword = document.querySelector("#togglePassword");
const passwordField = document.querySelector("#password");
const toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
const confirmPasswordField = document.querySelector("#confirm");

togglePassword.classList.remove("fa-eye");
togglePassword.classList.add("fa-eye-slash");

toggleConfirmPassword.classList.remove("fa-eye");
toggleConfirmPassword.classList.add("fa-eye-slash");

togglePassword.addEventListener("click", () => {
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
});

toggleConfirmPassword.addEventListener("click", () => {
    const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
    confirmPasswordField.setAttribute("type", type);
    
    toggleConfirmPassword.classList.toggle("fa-eye");
    toggleConfirmPassword.classList.toggle("fa-eye-slash");
});


function showConfirmBox() {
    document.getElementById("confirmBox").classList.remove("hidden");
}

function closeConfirmBox() {
    document.getElementById("confirmBox").classList.add("hidden");
}

document.getElementById("Register").addEventListener("click", function(e) {
    e.preventDefault();
    console.log("Rgister button clicked");

    if (1) {
        const email = document.getElementById('email').value;
        console.log("Form validated successfully. Requesting OTP...");


        fetch(`${URLROOT}/GuestPages/sendOTP`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showConfirmBox(); 
                console.log('OTP for testing:', data.debug_otp || 'Hidden'); 
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


document.getElementById("verify").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.querySelector("form");
    const enteredOTP = document.getElementById("otp").value;
    
    const otpInput = document.createElement('input');
    otpInput.type = 'hidden';
    otpInput.name = 'entered_otp';
    otpInput.value = enteredOTP;
    form.appendChild(otpInput);
    
    form.submit();
});

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