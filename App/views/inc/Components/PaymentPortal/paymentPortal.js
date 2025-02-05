document.addEventListener('DOMContentLoaded', () => {
    const paymentForm = document.getElementById('paymentForm');
    const paymentStatus = document.getElementById('paymentStatus');
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryInput = document.getElementById('expiry');
    const cvvInput = document.getElementById('cvv');

    // Format card number
    cardNumberInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(.{4})/g, '$1 ').trim();
        e.target.value = value;
    });

    // Format expiry date
    expiryInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });

    // CVV validation
    cvvInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    paymentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(paymentForm);

        fetch('process_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                paymentStatus.textContent = 'Payment Successful!';
                paymentStatus.className = 'payment-status success';
                paymentForm.reset();
            } else {
                paymentStatus.textContent = data.message || 'Payment Failed';
                paymentStatus.className = 'payment-status error';
            }
        })
        .catch(error => {
            paymentStatus.textContent = 'An error occurred';
            paymentStatus.className = 'payment-status error';
            console.error('Error:', error);
        });
    });
});