document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const userRole = document.getElementById('bookingForm').getAttribute('data-user-role');
    const urlRoot = document.getElementById('bookingForm').getAttribute('data-urlroot');
    let selectedPaymentMethod = ''; 
    let busStopsInOrder = [];
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

    const nameError = document.getElementById('NameError');
    const emailError = document.getElementById('EmailError');
    const contactError = document.getElementById('ContactError');
    const nicError = document.getElementById('nicError');
    const fromError = document.getElementById('fromError');
    const toError = document.getElementById('toError');
    const noOfSeatsError = document.getElementById('noOfseatsError');
    const selectedSeatsError = document.getElementById('selectedSeatsError');
    const paymentMethodError = document.getElementById('paymentMethodError');
    
    document.querySelectorAll('.error-message').forEach(error => {
        error.style.display = 'none';
    });

    initializeSeatSelection();

    const pricePerSeatElement = document.getElementById('pricePerSeat');
    const totalPriceElement = document.getElementById('total-price');
    const totalPriceInput = document.getElementById('totalPriceInput');
    
    if (!pricePerSeatElement.textContent || pricePerSeatElement.textContent === '0') {
        pricePerSeatElement.textContent = '0.00';
    }
    if (!totalPriceElement.textContent || totalPriceElement.textContent === '0') {
        totalPriceElement.textContent = '0.00';
    }
    if (!totalPriceInput.value || totalPriceInput.value === '0') {
        totalPriceInput.value = '0.00';
    }

    function initializeBusStops() {
        const options = Array.from(fromSelect.options).map(option => option.value);
        busStopsInOrder = options.filter(option => option !== '');
    }

    initializeBusStops();

    function updateToOptions() {
        const fromValue = fromSelect.value;
        
        if (!fromValue) return;
        
        const fromIndex = busStopsInOrder.indexOf(fromValue);
        
        if (fromIndex === -1) return;
        
        const currentToValue = toSelect.value;
        
        while (toSelect.options.length > 0) {
            toSelect.remove(0);
        }
        
        for (let i = fromIndex + 1; i < busStopsInOrder.length; i++) {
            const option = document.createElement('option');
            option.value = busStopsInOrder[i];
            option.textContent = busStopsInOrder[i];
            toSelect.appendChild(option);
        }
        
        if (toSelect.options.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No stops available';
            toSelect.appendChild(option);
        }
        
        if (currentToValue) {
            const stillValid = Array.from(toSelect.options).some(opt => opt.value === currentToValue);
            if (stillValid) {
                toSelect.value = currentToValue;
            }
        }
        
        validateLocations();
        
        if (fromSelect.value && toSelect.value) {
            updatePrice(fromSelect.value, toSelect.value);
        }
    }
    
    fromSelect.addEventListener('change', function() {
        updateToOptions();
    });

    updateToOptions();
    
    toSelect.addEventListener('change', function() {
        validateLocations();
        if (fromSelect.value && toSelect.value) {
            updatePrice(fromSelect.value, toSelect.value);
        }
    });

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
            fromSelect.classList.add('input-error');
            fromError.textContent = 'Please select a departure location';
            fromError.style.display = 'block';
            return false;
        } else {
            fromSelect.classList.remove('input-error');
            fromError.style.display = 'none';
        }
        
        if (to === '') {
            toSelect.classList.add('input-error');
            toError.textContent = 'Please select an arrival location';
            toError.style.display = 'block';
            return false;
        } else {
            toSelect.classList.remove('input-error');
            toError.style.display = 'none';
        }
        
        const fromIndex = busStopsInOrder.indexOf(from);
        const toIndex = busStopsInOrder.indexOf(to);
        
        if (fromIndex >= toIndex && from !== '' && to !== '') {
            toSelect.classList.add('input-error');
            toError.textContent = 'Arrival location must be after departure location';
            toError.style.display = 'block';
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

    contactInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 10);
        validateContact();
    });

    nicInput.addEventListener('input', function() {
        let value = this.value.replace(/[^0-9vV]/g, '');
        
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
    
    function updateFormAction(paymentMethod) {
        if (paymentMethod === 'Cash') {
            if (userRole === 'RegisteredUser') {
                bookingForm.action = urlRoot + '/RegisteredPages/RegisteredReceipt';
                return true;
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

    window.validateLocations = validateLocations;

    const showPopup = bookingForm.getAttribute('data-show-popup') === 'true';
    if (showPopup && document.getElementById('signInBox')) {
        document.getElementById('signInBox').classList.remove('hidden');
    }

    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (validateForm()) {
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
            if(updateFormAction(paymentMethod.value)) {
                bookingForm.submit();
            }
        }
    });

    function initializeSeatSelection() {
        const numberButtons = document.querySelectorAll('.number-button:not(.booked)');
        let selectedSeats = [];

        if (selectedSeatsInput.value) {
            selectedSeats = selectedSeatsInput.value.split(', ');
            
            selectedSeats.forEach(seatNumber => {
                const seatButton = Array.from(numberButtons).find(button => button.textContent === seatNumber);
                if (seatButton) {
                    seatButton.classList.add('selected');
                }
            });
        }

        numberButtons.forEach(button => {
            button.addEventListener('click', function() {
                const seatNumber = this.textContent;
                
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                } else {
                    this.classList.add('selected');
                    selectedSeats.push(seatNumber);
                }

                selectedSeatsInput.value = selectedSeats.join(', ');
                noOfSeatsInput.value = selectedSeats.length;

                const from = fromSelect.value;
                const to = toSelect.value;
                
                if (from && to) {
                    updatePrice(from, to);
                }
            });
        });
    }

    function updatePrice(from, to) {
        if (!from || !to) return;
        
        if (typeof selectedBus === 'undefined' || typeof distanceData === 'undefined' || typeof leastPrice === 'undefined') {
            console.error('Required global variables are not defined.');
            return;
        }
        
        const startLocation = selectedBus.start_location;
        const destination = selectedBus.destination;
        const price = selectedBus.price;
        let pricePerSeat = 0;

        // From middle to destination
        if (destination === to.trim() && startLocation !== from.trim()) {
            let totalDistance = 0;
            let boardingDistance = 0;
            
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === destination) {
                    totalDistance = parseFloat(route.distance);
                    break;
                }
            }
            
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
            for (const route of distanceData) {
                if (route.start === startLocation && route.location.trim() === to.trim()) {
                    const finalDistance = parseFloat(route.distance);
                    pricePerSeat = leastPrice * finalDistance;
                    break;
                }
            }
        }

        pricePerSeat = Math.max(0, pricePerSeat);
        
        const pricePerSeatElement = document.getElementById('pricePerSeat');
        const totalPriceElement = document.getElementById('total-price');
        const noOfSeats = parseInt(document.getElementById('noOfseats').value) || 0;
        
        pricePerSeatElement.textContent = pricePerSeat.toFixed(2);
        
        const seatTotal = pricePerSeat * noOfSeats;
        const grandTotal = seatTotal + penaltyFee;
        
        totalPriceElement.textContent = grandTotal.toFixed(2);
        totalPriceInput.value = grandTotal.toFixed(2);
    }

    window.updatePrice = updatePrice;

    const from = fromSelect.value;
    const to = toSelect.value;
    if (from && to) {
        updatePrice(from, to);
    }

});