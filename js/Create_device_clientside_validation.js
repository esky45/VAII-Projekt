// Client-side Validation
document.getElementById('deviceForm').addEventListener('submit', function (event) {
    const nameField = document.getElementById('name');
    const brightness = document.getElementById('brightness');
    const threshold = document.getElementById('threshold');

    if (!nameField.value.match(/^[a-zA-Z0-9 ]+$/)) {
        alert('Device name must contain only alphanumeric characters and spaces.');
        event.preventDefault(); // Stop form submission
    }

    if (brightness.value && (brightness.value < 0 || brightness.value > 100)) {
        alert('Brightness must be a number between 0 and 100.');
        event.preventDefault(); // Stop form submission
    }

    if (threshold.value && (threshold.value < 0 || threshold.value > 100)) {
        alert('Threshold must be a number between 0 and 100.');
        event.preventDefault(); // Stop form submission
    }
});
