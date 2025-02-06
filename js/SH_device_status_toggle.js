document.addEventListener('DOMContentLoaded', function () {
    // Toggle device status dynamically
    document.querySelectorAll('.toggle-status-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const deviceId = this.dataset.deviceId; // Get the device ID from the button's data attribute

            // Send a PATCH request to update the device status
            fetch(`/devices/${deviceId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'toggle' 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status UI
                    const statusLight = document.getElementById('status-light-' + deviceId);
                    const statusText = document.getElementById('status-text-' + deviceId);

                    // Toggle the status classes and text
                    if (data.status === 'on') {
                        statusLight.classList.remove('off');
                        statusLight.classList.add('on');
                        statusText.textContent = 'On';
                    } else {
                        statusLight.classList.remove('on');
                        statusLight.classList.add('off');
                        statusText.textContent = 'Off';
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling status:', error);
            });
        });
    });
});
