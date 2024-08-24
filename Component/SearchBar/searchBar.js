document.querySelector('.search-button').addEventListener('click', () => {
    const from = document.querySelector('input[placeholder="From"]').value;
    const to = document.querySelector('input[placeholder="To"]').value;
    const travelDate = document.querySelector('input[type="date"]').value;
    const time = document.querySelector('input[type="time"]').value;
    
    console.log("From:", from);
    console.log("To:", to);
    console.log("Travel Date:", travelDate);
    console.log("Time:", time);

    // You can now send this data to your server or use it as needed
});
