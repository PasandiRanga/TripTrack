document.addEventListener('DOMContentLoaded', function() {
    console.log('Payment portal script loaded');    
    // Get form elements
    const paymentForm = document.getElementById('paymentForm');
    const cardNameInput = document.getElementById('cardName');
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryInput = document.getElementById('expiry');
    const cvvInput = document.getElementById('cvv');
    const submitButton = document.querySelector('.btn-submit');
    
    // Get card icons
    const visaIcon = document.querySelector('.card-icon.visa');
    const mastercardIcon = document.querySelector('.card-icon.mastercard');
    const amexIcon = document.querySelector('.card-icon.amex');
    
    // Remove card type selection radio buttons completely
    const cardTypeGroup = document.getElementById('cardTypeGroup');
    if (cardTypeGroup) {
        cardTypeGroup.remove(); // Completely remove from DOM
    }
    
    // Initially set both Visa and Mastercard icons to semi-visible (half opacity)
    visaIcon.style.opacity = '0.5';
    mastercardIcon.style.opacity = '0.5';
    if (amexIcon) amexIcon.style.opacity = '0.5'; // Optional if you want to keep Amex
    
    // Format card number with spaces and detect card type
    cardNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '').substring(0, 16);
        this.value = value.replace(/(.{4})/g, '$1 ').trim();
        
        // Reset all card icons to half opacity
        visaIcon.style.opacity = '0.5';
        mastercardIcon.style.opacity = '0.5';
        if (amexIcon) amexIcon.style.opacity = '0.5';
        
        // Show the correct card icon based on first digit
        if (value.length > 0) {
            const firstDigit = value.charAt(0);
            if (firstDigit === '4') {
                visaIcon.style.opacity = '1';
            } else if (firstDigit === '5') {
                mastercardIcon.style.opacity = '1';
            }
        }
    });
    
    // Format expiry date with slash
    expiryInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '').substring(0, 4);
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        this.value = value;
    });
    
    // Limit CVV to 3 digits
    cvvInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 3);
    });
    
    // Handle form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Remove all existing error messages
        const existingErrors = document.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());
        
        // Remove error classes
        document.querySelectorAll('.input-error').forEach(input => {
            input.classList.remove('input-error');
        });

        console.log('Name validation:', validateCardName());
        console.log('Card number validation:', validateCardNumber());
        console.log('Expiry validation:', validateExpiry());
        console.log('CVV validation:', validateCVV());
        
       // Validate all fields
        const isNameValid = validateCardName();
        const isCardNumberValid = validateCardNumber();
        const isExpiryValid = validateExpiry();
        const isCvvValid = validateCVV();

        // If valid, submit the form
        if (isNameValid && isCardNumberValid && isExpiryValid && isCvvValid) {
            // Show loading state
            submitButton.innerHTML = 'Processing... <span class="spinner"></span>';
            submitButton.disabled = true;
            
            // Actually submit the form after validation has passed
            setTimeout(function() {
                paymentForm.submit();
            }, 1500);
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            // Make sure submit button is enabled
            submitButton.disabled = false;
            submitButton.innerHTML = 'Confirm Payment';
        }
    });
    
    // Validation functions
    function validateCardName() {
        const value = cardNameInput.value.trim();
        
        if (value === '') {
            showError(cardNameInput, 'Card name is required');
            return false;
        }
        
        if (value.length < 3) {
            showError(cardNameInput, 'Name must be at least 3 characters');
            return false;
        }
        
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            showError(cardNameInput, 'Name can only contain letters and spaces');
            return false;
        }
        
        return true;
    }
    
    function validateCardNumber() {
        const value = cardNumberInput.value.replace(/\s/g, '');
        
        if (value === '') {
            showError(cardNumberInput, 'Card number is required');
            return false;
        }
        
        if (value.length !== 16) {
            showError(cardNumberInput, 'Card number must be exactly 16 digits');
            return false;
        }
        
        if (!/^\d+$/.test(value)) {
            showError(cardNumberInput, 'Card number can only contain digits');
            return false;
        }
        
        // Check if it's a valid card type (starting with 4 or 5)
        const firstDigit = value.charAt(0);
        if (firstDigit !== '4' && firstDigit !== '5') {
            showError(cardNumberInput, 'Only Visa (starts with 4) or Mastercard (starts with 5) accepted');
            return false;
        }
        
        return true;
    }
    
    function validateExpiry() {
        const value = expiryInput.value;
        
        if (value === '') {
            showError(expiryInput, 'Expiry date is required');
            return false;
        }
        
        if (!/^\d{2}\/\d{2}$/.test(value)) {
            showError(expiryInput, 'Expiry date must be in MM/YY format');
            return false;
        }
        
        const [monthStr, yearStr] = value.split('/');
        const expiryMonth = parseInt(monthStr, 10);
        const expiryYear = 2000 + parseInt(yearStr, 10);
        
        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();
        
        if (expiryMonth < 1 || expiryMonth > 12) {
            showError(expiryInput, 'Month must be between 01-12');
            return false;
        }
        
        if (expiryYear < currentYear || (expiryYear === currentYear && expiryMonth < currentMonth)) {
            showError(expiryInput, 'Card has expired');
            return false;
        }
        
        return true;
    }
    
    function validateCVV() {
        const value = cvvInput.value;
        
        if (value === '') {
            showError(cvvInput, 'CVV is required');
            return false;
        }
        
        if (value.length !== 3) {
            showError(cvvInput, 'CVV must be 3 digits');
            return false;
        }
        
        if (!/^\d+$/.test(value)) {
            showError(cvvInput, 'CVV can only contain digits');
            return false;
        }
        
        return true;
    }
    
    // Helper function to show error messages
    function showError(input, message) {
        const formGroup = input.parentElement;
        input.classList.add('input-error');
        
        const errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.textContent = message;
        formGroup.appendChild(errorMessage);
    }
});