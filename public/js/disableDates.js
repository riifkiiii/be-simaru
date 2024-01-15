// public/js/disableDates.js

document.addEventListener('DOMContentLoaded', function () {
    // Fetch booked dates from the server
    fetch('/booked-dates')
        .then(response => response.json())
        .then(bookedDates => {
            // Disable booked dates in the date picker
            const dateInput = document.getElementById('start_book');
            dateInput.addEventListener('input', function () {
                const selectedDate = new Date(this.value);
                const selectedDateString = selectedDate.toISOString().split('T')[0];
                if (bookedDates.includes(selectedDateString)) {
                    alert('Date already booked. Please choose another date.');
                    this.value = ''; // Clear the input field
                }
            });
        })
        .catch(error => console.error('Error fetching booked dates:', error));
});
