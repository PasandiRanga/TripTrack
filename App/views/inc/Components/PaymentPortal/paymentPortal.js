document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const paymentForm = document.getElementById('paymentForm');
    const cardNameInput = document.getElementById('cardName');
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryInput = document.getElementById('expiry');
    const cvvInput = document.getElementById('cvv');
    const submitButton = document.querySelector('.btn-submit');

    // Create payment status element if it doesn't exist
    const paymentStatus = document.getElementById('paymentStatus') || createPaymentStatus();

    // ===== CARD NUMBER FORMATTING AND VALIDATION =====
    
    // Format and validate card number as user types
    cardNumberInput.addEventListener('input', function() {
        // Remove any non-digit characters
        let value = this.value.replace(/\D/g, '');
        
        // Add spaces after every 4 digits
        value = value.replace(/(.{4})/g, '$1 ').trim();
        
        // Update input value
        this.value = value;
        
        // Update card icon
        updateCardIcon(value);
    });
    
    cardNumberInput.addEventListener('blur', function() {
        validateCardNumber();
    });

    // ===== EXPIRY DATE FORMATTING AND VALIDATION =====
    
    // Format expiry date as user types
    expiryInput.addEventListener('input', function() {
        // Remove any non-digit characters
        let value = this.value.replace(/\D/g, '');
        
        // Add slash after the month
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        // Update input value
        this.value = value;
    });
    
    expiryInput.addEventListener('blur', function() {
        validateExpiry();
    });

    // ===== CVV VALIDATION =====
    
    // Only allow numbers for CVV
    cvvInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
    
    cvvInput.addEventListener('blur', function() {
        validateCVV();
    });

    // ===== CARD NAME VALIDATION =====
    
    cardNameInput.addEventListener('blur', function() {
        validateCardName();
    });

    // ===== FORM SUBMISSION =====
    
    paymentForm.addEventListener('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();
        
        // Validate all fields
        const isCardNameValid = validateCardName();
        const isCardNumberValid = validateCardNumber();
        const isExpiryValid = validateExpiry();
        const isCVVValid = validateCVV();
        
        console.log({
            isCardNameValid,
            isCardNumberValid,
            isExpiryValid,
            isCVVValid
        });
        
        // If all validations pass, submit the form
        if (isCardNameValid && isCardNumberValid && isExpiryValid && isCVVValid) {
            // Show loading state
            submitButton.innerHTML = 'Processing... <span class="spinner"></span>';
            submitButton.disabled = true;
            
            // For testing, simulate a successful submission (normally you'd use fetch)
            setTimeout(function() {
                paymentStatus.textContent = 'Payment Successful!';
                paymentStatus.className = 'payment-status success';
                paymentForm.reset();
                resetCardIcons();
                
                // Reset all validations
                const validFields = document.querySelectorAll('.form-group.valid');
                validFields.forEach(field => field.classList.remove('valid'));
                
                // Reset button
                submitButton.innerHTML = 'Confirm Payment';
                submitButton.disabled = false;
            }, 2000);
            
            /* Uncomment for actual form submission
            const formData = new FormData(paymentForm);
            
            fetch('process_payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                submitButton.innerHTML = 'Confirm Payment';
                submitButton.disabled = false;
                
                if (data.status === 'success') {
                    paymentStatus.textContent = 'Payment Successful!';
                    paymentStatus.className = 'payment-status success';
                    paymentForm.reset();
                    resetCardIcons();
                    // Reset validation marks
                    const validFields = document.querySelectorAll('.form-group.valid');
                    validFields.forEach(field => field.classList.remove('valid'));
                } else {
                    paymentStatus.textContent = data.message || 'Payment Failed';
                    paymentStatus.className = 'payment-status error';
                }
            })
            .catch(error => {
                // Reset button state
                submitButton.innerHTML = 'Confirm Payment';
                submitButton.disabled = false;
                
                paymentStatus.textContent = 'An error occurred';
                paymentStatus.className = 'payment-status error';
                console.error('Error:', error);
            });
            */
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // ===== HELPER FUNCTIONS =====
    
    // Create payment status element
    function createPaymentStatus() {
        const statusElement = document.createElement('div');
        statusElement.id = 'paymentStatus';
        statusElement.className = 'payment-status';
        paymentForm.after(statusElement);
        return statusElement;
    }
    
    // Show error message for an input
    function showError(input, message) {
        const formGroup = input.parentElement;
        let errorElement = formGroup.querySelector('.error-message');
        
        // Remove valid class if present
        formGroup.classList.remove('valid');
        
        // Create error message element if it doesn't exist
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            formGroup.appendChild(errorElement);
        }
        
        // Set error message and add error class to input
        errorElement.textContent = message;
        input.classList.add('input-error');
        
        return false;
    }
    
    // Remove error message from an input
    function removeError(input) {
        const formGroup = input.parentElement;
        const errorElement = formGroup.querySelector('.error-message');
        
        // Remove error message element if it exists
        if (errorElement) {
            formGroup.removeChild(errorElement);
        }
        
        // Remove error class from input
        input.classList.remove('input-error');
        
        return true;
    }
    
    // Mark input as valid
    function markValid(input) {
        input.parentElement.classList.add('valid');
    }
    
    // Setup card icons
    function setupCardIcons() {
        // Check if card icons already exist
        if (document.querySelector('.card-icons')) {
            return;
        }
        
        const cardNumberParent = cardNumberInput.parentElement;
        
        // Add container class if needed
        if (!cardNumberParent.classList.contains('card-input-container')) {
            cardNumberParent.classList.add('card-input-container');
        }
        
        // Create and append card icons
        const iconsDiv = document.createElement('div');
        iconsDiv.className = 'card-icons';
        
        const visaIcon = document.createElement('div');
        visaIcon.className = 'card-icon visa';
        
        const mastercardIcon = document.createElement('div');
        mastercardIcon.className = 'card-icon mastercard';
        
        const amexIcon = document.createElement('div');
        amexIcon.className = 'card-icon amex';
        
        iconsDiv.appendChild(visaIcon);
        iconsDiv.appendChild(mastercardIcon);
        iconsDiv.appendChild(amexIcon);
        
        cardNumberParent.appendChild(iconsDiv);
    }
    
    // Update active card icon based on card number
    function updateCardIcon(cardNumber) {
        const visaIcon = document.querySelector('.card-icon.visa');
        const mastercardIcon = document.querySelector('.card-icon.mastercard');
        const amexIcon = document.querySelector('.card-icon.amex');
        
        if (!visaIcon || !mastercardIcon || !amexIcon) {
            setupCardIcons();
            return updateCardIcon(cardNumber);
        }
        
        // Reset all icons
        visaIcon.classList.remove('active');
        mastercardIcon.classList.remove('active');
        amexIcon.classList.remove('active');
        
        // Clean card number
        const cardNumberClean = cardNumber.replace(/\s/g, '');
        
        // Detect card type and show appropriate icon
        if (cardNumberClean.startsWith('4')) {
            visaIcon.classList.add('active');
        } else if (/^5[1-5]/.test(cardNumberClean)) {
            mastercardIcon.classList.add('active');
        } else if (/^3[47]/.test(cardNumberClean)) {
            amexIcon.classList.add('active');
        }
    }
    
    // Reset card icons
    function resetCardIcons() {
        const icons = document.querySelectorAll('.card-icon');
        icons.forEach(icon => icon.classList.remove('active'));
    }

    // ===== VALIDATION FUNCTIONS =====
    
    // Validate card name
    function validateCardName() {
        const value = cardNameInput.value.trim();
        
        // Check if name is empty
        if (value === '') {
            return showError(cardNameInput, 'Card name is required');
        }
        
        // Check if name is at least 3 characters
        if (value.length < 3) {
            return showError(cardNameInput, 'Name must be at least 3 characters');
        }
        
        // Check if name contains only letters and spaces
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            return showError(cardNameInput, 'Name can only contain letters and spaces');
        }
        
        // If all checks pass, mark as valid
        removeError(cardNameInput);
        markValid(cardNameInput);
        return true;
    }
    
    // Validate card number
    function validateCardNumber() {
        const value = cardNumberInput.value.replace(/\s/g, '');
        
        // Check if card number is empty
        if (value === '') {
            return showError(cardNumberInput, 'Card number is required');
        }
        
        // Check if card number has valid length
        if (value.length < 13 || value.length > 19) {
            return showError(cardNumberInput, 'Card number must be between 13-19 digits');
        }
        
        // Check if card number contains only digits
        if (!/^\d+$/.test(value)) {
            return showError(cardNumberInput, 'Card number can only contain digits');
        }
        
        // Check if card number passes Luhn algorithm
        if (!isValidLuhn(value)) {
            return showError(cardNumberInput, 'Invalid card number');
        }
        
        // If all checks pass, mark as valid
        removeError(cardNumberInput);
        markValid(cardNumberInput);
        return true;
    }
    
    // Validate expiry date
    function validateExpiry() {
        const value = expiryInput.value;
        
        // Check if expiry date is empty
        if (value === '') {
            return showError(expiryInput, 'Expiry date is required');
        }
        
        // Check if expiry date has valid format
        if (!/^\d{2}\/\d{2}$/.test(value)) {
            return showError(expiryInput, 'Expiry date must be in MM/YY format');
        }
        
        // Extract month and year from input
        const [monthStr, yearStr] = value.split('/');
        const expiryMonth = parseInt(monthStr, 10);
        const expiryYear = 2000 + parseInt(yearStr, 10); // Convert to 4-digit year
        
        // Get current date for comparison
        const now = new Date();
        const currentMonth = now.getMonth() + 1; // getMonth returns 0-11
        const currentYear = now.getFullYear();
        
        // Console log for debugging
        console.log('Expiry validation:', {
            input: value,
            expiryMonth,
            expiryYear,
            currentMonth,
            currentYear,
            isExpired: expiryYear < currentYear || (expiryYear === currentYear && expiryMonth < currentMonth)
        });
        
        // Check if month is valid
        if (expiryMonth < 1 || expiryMonth > 12) {
            return showError(expiryInput, 'Month must be between 01-12');
        }
        
        // Check if card is expired
        if (expiryYear < currentYear || (expiryYear === currentYear && expiryMonth < currentMonth)) {
            return showError(expiryInput, 'Card has expired');
        }
        
        // If all checks pass, mark as valid
        removeError(expiryInput);
        markValid(expiryInput);
        return true;
    }
    
    // Validate CVV
    function validateCVV() {
        const value = cvvInput.value;
        
        // Check if CVV is empty
        if (value === '') {
            return showError(cvvInput, 'CVV is required');
        }
        
        // Determine required CVV length based on card type
        let requiredLength = 3;
        const cardNumber = cardNumberInput.value.replace(/\s/g, '');
        
        // American Express cards use 4-digit CVV
        if (/^3[47]/.test(cardNumber)) {
            requiredLength = 4;
        }
        
        // Check if CVV has valid length
        if (value.length !== requiredLength) {
            return showError(cvvInput, `CVV must be ${requiredLength} digits`);
        }
        
        // Check if CVV contains only digits
        if (!/^\d+$/.test(value)) {
            return showError(cvvInput, 'CVV can only contain digits');
        }
        
        // If all checks pass, mark as valid
        removeError(cvvInput);
        markValid(cvvInput);
        return true;
    }
    
    // Luhn algorithm for validating card numbers
    function isValidLuhn(number) {
        let sum = 0;
        let shouldDouble = false;
        
        // Loop through values starting from the rightmost digit
        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number.charAt(i));
            
            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }
            
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        
        return (sum % 10) === 0;
    }
    
    // Initialize the form
    setupCardIcons();
});