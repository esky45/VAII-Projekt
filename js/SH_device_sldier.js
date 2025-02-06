// resources/js/SH_device_sldier.js

document.addEventListener('DOMContentLoaded', function () {
    // Update the brightness value dynamically
    document.querySelectorAll('.brightness-slider').forEach(function(slider) {
        slider.addEventListener('input', function() {
            const sliderId = this.id.split('-')[1]; // Get the device ID from the slider ID
            document.getElementById('brightness-value-' + sliderId).textContent = this.value + '%';
        });
    });

    // Update the threshold value dynamically
    document.querySelectorAll('.threshold-slider').forEach(function(slider) {
        slider.addEventListener('input', function() {
            const sliderId = this.id.split('-')[1]; // Get the device ID from the slider ID
            document.getElementById('threshold-value-' + sliderId).textContent = this.value + '%';
        });
    });
});