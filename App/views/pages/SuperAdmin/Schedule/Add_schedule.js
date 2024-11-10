// Function to clear the form fields
function clearForm() {
    document.getElementById("schedule-form").reset();
}

// Form validation function with validation logic
function validateForm() {
    const scheduleId = document.getElementById("scheduleId").value.trim();
    const date = document.getElementById("date").value;
    const departureTime = document.getElementById("departureTime").value;
    const arrivalTime = document.getElementById("arrivalTime").value;
    const duration = document.getElementById("duration").value.trim();
    const price = document.getElementById("price").value.trim();
    const busId = document.getElementById("busId").value.trim();
    const busNumber = document.getElementById("busNumber").value.trim();
    const route = document.getElementById("route").value.trim();

    let errorMessage = "";

    // Schedule ID validation (must be numeric)
    if (scheduleId === "" || isNaN(scheduleId)) {
        errorMessage += "Schedule ID must be a valid number.\n";
    }

    // Date validation
    if (date === "") {
        errorMessage += "Date is required.\n";
    }

    // Departure and Arrival Time validation (must be in HH:MM format)
    if (departureTime === "") {
        errorMessage += "Departure time is required.\n";
    }
    if (arrivalTime === "") {
        errorMessage += "Arrival time is required.\n";
    }

    // Duration validation (simple format check, e.g., "9 hours 30 mins")
    const durationPattern = /^[0-9]+ hours [0-9]+ mins$/;
    if (!durationPattern.test(duration)) {
        errorMessage += "Duration must be in the format 'X hours Y mins'.\n";
    }

    // Price validation (must start with 'Rs.' followed by a number)
    const pricePattern = /^Rs\.?\s?\d+$/;
    if (!pricePattern.test(price)) {
        errorMessage += "Price must be in the format 'Rs. amount'.\n";
    }

    // Bus ID validation (must be numeric)
    if (busId === "" || isNaN(busId)) {
        errorMessage += "Bus ID must be a valid number.\n";
    }

    // Bus Number validation (non-empty, alphanumeric format)
    const busNumberPattern = /^[A-Z0-9\-]+$/;
    if (!busNumberPattern.test(busNumber)) {
        errorMessage += "Bus Number must be alphanumeric, e.g., 'NA-1234'.\n";
    }

    // Route validation (non-empty, must contain text)
    if (route === "") {
        errorMessage += "Route is required.\n";
    }

    // Display error message if any validation fails
    if (errorMessage !== "") {
        alert(errorMessage);
        return false;
    }

    // If no errors, proceed with form submission
    return true;
}
