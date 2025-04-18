document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const paymentForm = document.getElementById('paymentForm');
    const cardNameInput = document.getElementById('cardName');
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryInput = document.getElementById('expiry');
    const cvvInput = document.getElementById('cvv');
    const submitButton = document.querySelector('.btn-submit');
    
    // Format card number with spaces
    cardNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '').substring(0, 16);
        this.value = value.replace(/(.{4})/g, '$1 ').trim();
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
        
        let isValid = true;
        
        // Validate card name
        if (!validateCardName()) {
            isValid = false;
        }
        
        // Validate card number
        if (!validateCardNumber()) {
            isValid = false;
        }
        
        // Validate expiry date
        if (!validateExpiry()) {
            isValid = false;
        }
        
        // Validate CVV
        if (!validateCVV()) {
            isValid = false;
        }

        if (!validateCardType()) {
            isValid = false;
        }

        
        // If valid, submit the form
        if (isValid) {
            // Show loading state
            submitButton.innerHTML = 'Processing...';
            submitButton.disabled = true;
            
            // Normally you would submit the form here
            // For now, let's just simulate a successful submission
            setTimeout(function() {
                alert('Payment successful!');
                submitButton.innerHTML = 'Confirm Payment';
                submitButton.disabled = false;
                // Uncomment to actually submit:
                // paymentForm.submit();
            }, 1500);
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
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

function validateCardType() {
    const selectedType = document.querySelector('input[name="cardType"]:checked');
    if (!selectedType) {
        const cardTypeGroup = document.getElementById('cardTypeGroup');
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = 'Please select your card type';
        cardTypeGroup.appendChild(error);
        return false;
    }
    return true;
}
