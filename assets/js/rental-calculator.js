// Rental price calculator
function calculateTotal() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const pricePerDay = parseFloat(document.getElementById('pricePerDay').value);

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        // Calculate difference in days
        const timeDiff = end.getTime() - start.getTime();
        const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end days

        if (days > 0) {
            const total = days * pricePerDay;
            document.getElementById('days').innerText = days;
            document.getElementById('totalPrice').innerText = total.toFixed(2);
        } else {
            alert('End date must be after start date!');
            document.getElementById('endDate').value = '';
            document.getElementById('days').innerText = '0';
            document.getElementById('totalPrice').innerText = '0.00';
        }
    }
}

// Set minimum end date based on start date
document.getElementById('startDate')?.addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('endDate');
    endDateInput.min = startDate;
    
    // Reset end date if it's before new start date
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = '';
    }
});
