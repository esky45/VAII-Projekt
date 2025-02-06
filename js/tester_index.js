fetch("{{ route('tester.terminal.execute') }}", {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ command: command })
})
.then(response => response.json())
.then(data => {
    if (data.error) {
        document.getElementById('terminal-output').textContent = 'Error: ' + data.error;
    } else {
        document.getElementById('terminal-output').textContent = data.output;
    }
})
.catch(error => {
    document.getElementById('terminal-output').textContent = 'Error: ' + error;
});
