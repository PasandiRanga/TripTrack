// Function to navigate back to the previous page
function goBack() {
    window.history.back();
}

// Function to select a row in the table
function selectRow(row) {
    // Deselect any previously selected row
    const previouslySelectedRow = document.querySelector(".booking-table tr.selected");
    if (previouslySelectedRow) {
        previouslySelectedRow.classList.remove("selected");
    }

    // Select the clicked row
    row.classList.add("selected");
}
