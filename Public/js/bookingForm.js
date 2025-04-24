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
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
    const fromSelect = document.getElementById('from');
    const toSelect = document.getElementById('to');
    const numofseats = document.getElementById('noOfseats');
    const penaltyFee = parseFloat(document.getElementById('bookingForm').getAttribute('data-penalty-fee') || 0);
    console.log(penaltyFee);

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

    // Initialize the seat selection functionality
    initializeSeatSelection();

    // Initialize price displays to zero
    const pricePerSeatElement = document.getElementById('pricePerSeat');
    const totalPriceElement = document.getElementById('total-price');
    const totalPriceInput = document.getElementById('totalPriceInput');
    
    // Set initial values if not already set
    if (!pricePerSeatElement.textContent || pricePerSeatElement.textContent === '0') {
        pricePerSeatElement.textContent = '0.00';
    }
    if (!totalPriceElement.textContent || totalPriceElement.textContent === '0') {
        totalPriceElement.textContent = '0.00';
    }
    if (!totalPriceInput.value || totalPriceInput.value === '0') {
        totalPriceInput.value = '0.00';
    }

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

    nameInput.addEventListener('input', validateName);

    emailInput.addEventListener('input', validateEmail);

    fromSelect.addEventListener('change', function() {
        validateLocations();
        updatePrice(fromSelect.value, toSelect.value);
    });

    toSelect.addEventListener('change', function() {
        validateLocations();
        updatePrice(fromSelect.value, toSelect.value);
    });

    numofseats.addEventListener('input', function() {
        validateSeats();
        updatePrice(fromSelect.value, toSelect.value);
    });

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            validatePaymentMethod();
        });
    });
    
    function validateForm() {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isContactValid = validateContact();
        const isNICValid = validateNIC();
        const areLocationsValid = validateLocations();
        const areSeatsValid = validateSeats();
        const isPaymentMethodValid = validatePaymentMethod();
        
        return isNameValid && isEmailValid && isContactValid && isNICValid && areLocationsValid && areSeatsValid && isPaymentMethodValid
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

    bookingForm.addEventListener('submit', function(event) {
        // Prevent form from submitting immediately
        event.preventDefault();
        
        // Run form validation
        if (validateForm()) {
            // If validation passes, submit the form properly
            // Use the native form submission instead of calling submit() directly
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
            if(updateFormAction(paymentMethod.value)) {
                bookingForm.submit();
            }
        }
    });

    // ADDED FROM BUSLAYOUT.PHP: Initialize seat selection functionality
    function initializeSeatSelection() {
        const numberButtons = document.querySelectorAll('.number-button:not(.booked)');
        let selectedSeats = [];

        // Load any pre-selected seats if available
        if (selectedSeatsInput.value) {
            selectedSeats = selectedSeatsInput.value.split(', ');
            
            // Highlight pre-selected seats
            selectedSeats.forEach(seatNumber => {
                const seatButton = Array.from(numberButtons).find(button => button.textContent === seatNumber);
                if (seatButton) {
                    seatButton.classList.add('selected');
                }
            });
        }

        // Add click event for seat selection
        numberButtons.forEach(button => {
            button.addEventListener('click', function() {
                const seatNumber = this.textContent;
                
                if (this.classList.contains('selected')) {
                    // Deselect seat
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                } else {
                    // Select seat
                    this.classList.add('selected');
                    selectedSeats.push(seatNumber);
                }

                // Update form inputs
                selectedSeatsInput.value = selectedSeats.join(', ');
                noOfSeatsInput.value = selectedSeats.length;

                // Get current 'from' and 'to' values and recalculate price
                const from = fromSelect.value;
                const to = toSelect.value;
                
                // Only update price if both from and to are selected
                if (from && to) {
                    updatePrice(from, to);
                }
            });
        });
    }

    // ADDED FROM BUSLAYOUT.PHP: Price calculation function
    function updatePrice(from, to) {
        if (!from || !to) return;
        
        // Check if selectedBus is available in the global scope
        if (typeof selectedBus === 'undefined' || typeof distanceData === 'undefined' || typeof leastPrice === 'undefined') {
            console.error('Required global variables are not defined.');
            return;
        }
        
        // Get the start location and destination from the selected bus
        const startLocation = selectedBus.start_location;
        const destination = selectedBus.destination;
        const price = selectedBus.price;
        let pricePerSeat = 0;

        // From middle to destination
        if (destination === to.trim() && startLocation !== from.trim()) {
            // Full journey minus distance from start to boarding point
            let totalDistance = 0;
            let boardingDistance = 0;
            
            // Find total route distance
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === destination) {
                    totalDistance = parseFloat(route.distance);
                    break;
                }
            }
            
            // Find boarding point distance
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === from.trim()) {
                    boardingDistance = parseFloat(route.distance);
                    break;
                }
            }
            
            const finalDistance = totalDistance - boardingDistance;
            pricePerSeat = leastPrice * finalDistance;
            
        // From start to destination
        } else if (destination === to.trim() && startLocation === from.trim()) {
            // Full journey price
            pricePerSeat = price;
        
        // From middle to middle 
        } else if (destination !== to.trim() && startLocation !== from.trim()) {
            // Partial journey between two intermediate stops
            let toDistance = 0;
            let fromDistance = 0;
            
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === to.trim()) {
                    toDistance = parseFloat(route.distance);
                }
                if (route.start === startLocation && route.location.trim() === from.trim()) {
                    fromDistance = parseFloat(route.distance);
                }
            }
            
            const finalDistance = toDistance - fromDistance;
            pricePerSeat = leastPrice * finalDistance;
        
        // From start to middle
        } else if (startLocation === from.trim() && destination !== to.trim()) {
            // Journey from start to intermediate stop
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === to.trim()) {
                    const finalDistance = parseFloat(route.distance);
                    pricePerSeat = leastPrice * finalDistance;
                    break;
                }
            }
        }

        // Ensure price is not negative
        pricePerSeat = Math.max(0, pricePerSeat);
        
        // Update display elements
        const pricePerSeatElement = document.getElementById('pricePerSeat');
        const totalPriceElement = document.getElementById('total-price');
        const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
        
        pricePerSeatElement.textContent = pricePerSeat.toFixed(2);
        
        // Add penalty fee if applicable
        const seatTotal = pricePerSeat * noOfSeats;
        const grandTotal = seatTotal + penaltyFee;
        
        totalPriceElement.textContent = grandTotal.toFixed(2);
        totalPriceInput.value = grandTotal.toFixed(2);
    }

    // Make updatePrice globally available
    window.updatePrice = updatePrice;

    // Initialize price update if from and to are already selected
    const from = fromSelect.value;
    const to = toSelect.value;
    if (from && to) {
        updatePrice(from, to);
    }
});