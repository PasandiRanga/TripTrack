// Function to select a row in the table
function selectRow(row) {
    // Deselect any previously selected row
    const selectedRow = document.querySelector(".schedule-table tr.selected");
    if (selectedRow) {
        selectedRow.classList.remove("selected");
    }
    row.classList.add("selected");
}

// Function to handle adding a schedule
function addSchedule() {
    window.location.href = "Schedule/Add_schedule.php";
}

// Function to handle updating a selected schedule
function updateSchedule() {
    const selectedRow = document.querySelector(".schedule-table tr.selected");
    if (selectedRow) {
        const scheduleId = selectedRow.cells[0].textContent;
        window.location.href = `update_schedule.php?scheduleId=${scheduleId}`;
    } else {
        alert("Please select a schedule to update.");
    }
}

// Function to handle deleting a selected schedule
function deleteSchedule() {
    const selectedRow = document.querySelector(".schedule-table tr.selected");
    if (selectedRow) {
        const scheduleId = selectedRow.cells[0].textContent;
        if (confirm(`Are you sure you want to delete schedule ID ${scheduleId}?`)) {
            // Add AJAX request or redirection to delete page
            alert(`Schedule ID ${scheduleId} has been deleted.`);
        }
    } else {
        alert("Please select a schedule to delete.");
    }
}
