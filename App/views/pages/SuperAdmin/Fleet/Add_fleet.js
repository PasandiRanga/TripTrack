// Go back to the previous page
function goBack() {
    window.history.back();
}

// Handle form submission
function submitFleetForm(event) {
    event.preventDefault();

    const fleetData = {
        licence_id: document.getElementById("licence_id").value,
        driver_id: document.getElementById("driver_id").value,
        conductor_id: document.getElementById("conductor_id").value,
        no_of_seats: document.getElementById("no_of_seats").value,
        bus_route_no: document.getElementById("bus_route_no").value
    };

    console.log("Fleet added:", fleetData);
    alert("Fleet added successfully!");

    // Optionally, clear the form fields after submission
    clearForm();
}

// Clear form fields
function clearForm() {
    document.getElementById("fleet-form").reset();
}
