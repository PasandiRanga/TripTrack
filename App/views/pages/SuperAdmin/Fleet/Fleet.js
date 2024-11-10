let selectedRow = null;

// Go back to the previous page
function goBack() {
    window.history.back();
}

// Clear the search input
function clearSearch() {
    document.getElementById("search").value = "";
}

// Add a bus (Placeholder function)
function addBus() {
    window.location.href = "Fleet/Add_fleet.html";
    alert("Add Bus functionality to be implemented");
}

// Delete the selected bus
function deleteBus() {
    if (selectedRow) {
        selectedRow.remove();
        selectedRow = null;
        alert("Bus deleted successfully.");
    } else {
        alert("Please select a row to delete.");
    }
}

// Update the selected bus (Placeholder function)
function updateBus() {
    if (selectedRow) {
        alert("Update Bus functionality to be implemented.");
        // You can add a modal or form to edit the selected row details here
    } else {
        alert("Please select a row to update.");
    }
}

// Select a row
function selectRow(row) {
    // Clear previous selection
    if (selectedRow) {
        selectedRow.classList.remove("selected");
    }
    // Set the new selection
    selectedRow = row;
    selectedRow.classList.add("selected");
}
