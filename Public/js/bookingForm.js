document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const userRole = document.getElementById('bookingForm').getAttribute('data-user-role');
    const urlRoot = document.getElementById('bookingForm').getAttribute('data-urlroot');
    let selectedPaymentMethod = ''; 
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('Bookingemail');
    const contactInput = document.getElementById('contact');
    const nicInput = document.getElementById('nic');
    const selectedSeatsInput = document.getElementById('selectedSeats');
    const noOfSeatsInput = document.getElementById('noOfseats');
    const checkoutButton = document.getElementById('checkoutButton');
    const fromSelect = document.getElementById('from');
    const toSelect = document.getElementById('to');
    const numofseats = document.getElementById('noOfseats');
    const penaltyFee = parseFloat(document.getElementById('bookingForm').getAttribute('data-penalty-fee') || 0);

    // Get all error elements
    const nameError = document.getElementById('NameError');
    const emailError = document.getElementById('EmailError');
    const contactError = document.getElementById('ContactError');
    const nicError = document.getElementById('nicError');
    const fromError = document.getElementById('fromError');
    const toError = document.getElementById('toError');
    const noOfSeatsError = document.getElementById('noOfseatsError');
    const selectedSeatsError = document.getElementById('selectedSeatsError');
    const paymentMethodError = document.getElementById('paymentMethodError');
    
    // Ensure all error messages are initially hidden
    document.querySelectorAll('.error-message').forEach(error => {
        error.style.display = 'none';
    });

    // Function to show/hide error message
    function showError(inputElement, errorElement, message, isError) {
        if (isError) {
            inputElement.classList.add('input-error');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else {
            inputElement.classList.remove('input-error');
            errorElement.style.display = 'none';
        }
    }

    // Validation functions
    function validateName() {
        const name = nameInput.value.trim();
        const nameRegex = /^[a-zA-Z\s]+$/;
        
        if (name === '') {
            showError(nameInput, nameError, 'Name is required', true);
            return false;
        } else if (!nameRegex.test(name)) {
            showError(nameInput, nameError, 'Name should only contain letters and spaces', true);
            return false;
        } else {
            showError(nameInput, nameError, '', false);
            return true;
        }
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            showError(emailInput, emailError, 'Email is required', true);
            return false;
        } else if (!emailRegex.test(email)) {
            showError(emailInput, emailError, 'Please enter a valid email address', true);
            return false;
        } else {
            showError(emailInput, emailError, '', false);
            return true;
        }
    }

    function validateContact() {
        const contact = contactInput.value.trim();
        const contactRegex = /^\d{10}$/;
        
        if (contact === '') {
            showError(contactInput, contactError, 'Contact number is required', true);
            return false;
        } else if (!contactRegex.test(contact)) {
            showError(contactInput, contactError, 'Contact number must be exactly 10 digits', true);
            return false;
        } else {
            showError(contactInput, contactError, '', false);
            return true;
        }
    }

    function validateNIC() {
        const nic = nicInput.value.trim();
        // NIC regex for either 9 digits followed by 'v' or 'V', or exactly 12 digits
        const nicRegex = /^(\d{9}[vV]|\d{12})$/;
        
        if (nic === '') {
            showError(nicInput, nicError, 'NIC number is required', true);
            return false;
        } else if (!nicRegex.test(nic)) {
            showError(nicInput, nicError, 'NIC must be either 9 digits followed by v or 12 digits', true);
            return false;
        } else {
            showError(nicInput, nicError, '', false);
            return true;
        }
    }

    function validateLocations() {
        const from = fromSelect.value;
        const to = toSelect.value;
        
        if (from === '') {
            showError(fromSelect, fromError, 'Please select a departure location', true);
            return false;
        } else {
            showError(fromSelect, fromError, '', false);
        }
        
        if (to === '') {
            showError(toSelect, toError, 'Please select an arrival location', true);
            return false;
        } else {
            showError(toSelect, toError, '', false);
        }
        
        if (from === to && from !== '') {
            showError(fromSelect, fromError, 'Departure and arrival locations cannot be the same', true);
            return false;
        }
        
        return true;
    }

    function validateSeats() {
        const seatsSelected = selectedSeatsInput.value.trim() !== '';
        const seatCount = parseInt(noOfSeatsInput.value) > 0;
        
        if (!seatsSelected) {
            showError(selectedSeatsInput, selectedSeatsError, 'Please select your seats', true);
        } else {
            showError(selectedSeatsInput, selectedSeatsError, '', false);
        }
        
        if (!seatCount) {
            showError(noOfSeatsInput, noOfSeatsError, 'Please select at least one seat', true);
        } else {
            showError(noOfSeatsInput, noOfSeatsError, '', false);
        }
        
        return seatsSelected && seatCount;
    }

    function validatePaymentMethod() {
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        const isSelected = Array.from(paymentMethods).some(radio => radio.checked);
        
        if (!isSelected) {
            paymentMethodError.style.display = 'block';
        } else {
            paymentMethodError.style.display = 'none';
        }
        
        return isSelected;
    }

    // Format input as it's being typed
    contactInput.addEventListener('input', function() {
        // Remove non-digit characters and limit to 10 digits
        this.value = this.value.replace(/\D/g, '').substring(0, 10);
        validateContact();
    });

    nicInput.addEventListener('input', function() {
        // Allow only digits and 'v' or 'V', limit to appropriate length
        let value = this.value.replace(/[^0-9vV]/g, '');
        
        // If last character is 'v' or 'V', ensure it's at position 10
        if (/[vV]/.test(value.charAt(value.length - 1)) && value.length > 10) {
            const digits = value.replace(/[vV]/g, '');
            value = digits.substring(0, 9) + value.charAt(value.length - 1);
        } else if (!/[vV]/.test(value) && value.length > 12) {
            value = value.substring(0, 12);
        }
        
        this.value = value;
        validateNIC();
    });

    // Add event listeners for validation
    nameInput.addEventListener('input', validateName);
    emailInput.addEventListener('input', validateEmail);
    fromSelect.addEventListener('change', function() {
        validateLocations();
    });
    toSelect.addEventListener('change', function() {
        validateLocations();
    });

    numofseats.addEventListener('input', function() {
        validateSeats();
    });

    // Event listener for payment method selection
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            validatePaymentMethod();
            validateForm();
        });
    });
    
    // Function to validate the entire form
    function validateForm() {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isContactValid = validateContact();
        const isNICValid = validateNIC();
        const areLocationsValid = validateLocations();
        const areSeatsValid = validateSeats();
        const isPaymentMethodValid = validatePaymentMethod();
        
        // Enable checkout button only if all validations pass
        checkoutButton.disabled = !(
            isNameValid && 
            isEmailValid && 
            isContactValid && 
            isNICValid && 
            areLocationsValid && 
            areSeatsValid && 
            isPaymentMethodValid
        );
        
        checkoutButton.style.opacity = checkoutButton.disabled ? '0.6' : '1';
    }
    
    // Handle payment method logic
    function updateFormAction(paymentMethod) {
        if (paymentMethod === 'Cash') {
            if (userRole === 'RegisteredUser') {
                bookingForm.action = urlRoot + '/RegisteredPages/RegisteredReceipt';
                return true;
            } else {
                selectedPaymentMethod = paymentMethod;
                document.getElementById("confirmBox").classList.remove("hidden");
                return false;
            }
        } else if (paymentMethod === 'Online') {
            if (userRole === 'RegisteredUser') {
                bookingForm.action = urlRoot + '/RegisteredPages/paymentPortal';
                return true;
            } else {
                selectedPaymentMethod = paymentMethod;
                document.getElementById("confirmBox").classList.remove("hidden");
                return false;
            }
        }
    }

    function processBooking(paymentMethod) {
        bookingForm.action = urlRoot + '/GuestPages/paymentPortal';
        bookingForm.submit();
    }

    // Window functions for confirm box
    window.confirmAction = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        processBooking(selectedPaymentMethod);
    };

    window.closeConfirmBoxandLogin = function() {
        document.getElementById("confirmBox").classList.add("hidden");
        if (document.getElementById("signInBox")) {
            document.getElementById("signInBox").classList.remove("hidden");
        }
    };

    window.closeConfirmBox = function() {
        document.getElementById("confirmBox").classList.add("hidden");
    };

    // Show the login box on page load if required
    const showPopup = bookingForm.getAttribute('data-show-popup') === 'true';
    if (showPopup && document.getElementById('signInBox')) {
        document.getElementById('signInBox').classList.remove('hidden');
    }

    // Update form submission handling
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Perform full validation
        const isValid = validateName() && 
                        validateEmail() && 
                        validateContact() && 
                        validateNIC() && 
                        validateLocations() && 
                        validateSeats() && 
                        validatePaymentMethod();
        
        if (!isValid) {a
            return false;
        }

        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
        
        // If updateFormAction returns true, submit the form directly
        // Otherwise, the confirmBox will be shown
        if (updateFormAction(paymentMethod.value)) {
            bookingForm.submit();
        }
    });

    // Price updating function
    window.updatePrice = function(from, to) {
        const pricePerSeatElement = document.getElementById('pricePerSeat');
        const totalPriceElement = document.getElementById('total-price');
        const totalPriceInput = document.getElementById('totalPriceInput');
        const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
        
        const pricePerSeat = parseFloat(pricePerSeatElement.textContent);
        const seatTotal = pricePerSeat * noOfSeats;
        const grandTotal = seatTotal + penaltyFee;
        
        totalPriceElement.textContent = grandTotal.toFixed(2);
        totalPriceInput.value = grandTotal.toFixed(2);
    };

    // Initialize price update if from and to are already selected
    const from = fromSelect.value;
    const to = toSelect.value;
    if (from && to) {
        updatePrice(from, to);
    }
});

