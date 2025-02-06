
// Toggle visibility of the create room form
document.getElementById('show-create-room-form').addEventListener('click', function() {
           const form = document.getElementById('create-room-form');
           form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
       });

       // Room creation logic 
    
       document.getElementById('create-room-form').addEventListener('submit', function(e) {
           e.preventDefault();
           const roomName = document.getElementById('room-name').value;

           //ajax fetch
           fetch(createRoomUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ name: roomName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const room = document.createElement('div');
                room.className = 'room';
                room.id = 'room-' + data.room.id;
                room.innerHTML = `<h2>${data.room.name}</h2>`;
        
                document.getElementById('house-layout').appendChild(room);
                document.getElementById('room-name').value = ''; // Clear input field
        
                // Enable Drag-and-Drop for the new room
                enableDragAndDropForRoom(room);
            } else {
                alert('Failed to create room');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
       });
       document.addEventListener('DOMContentLoaded', function() {
        // Handle room deletion
        document.getElementById('house-layout').addEventListener('click', async function(e) {
            if (e.target.classList.contains('delete-room-btn')) {
                const roomId = e.target.dataset.roomId;
                const roomElement = document.getElementById(`room-${roomId}`);
    
                try {
                    const response = await fetch(`/rooms/${roomId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json', // Explicitly request JSON
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
    
                    // Handle HTML responses
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        throw new Error(`Server returned HTML: ${text.slice(0, 100)}`);
                    }
    
                    const data = await response.json();
                    
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Failed to delete room');
                    }
    
                    // Remove room from UI
                    roomElement.remove();
                    
                } catch (error) {
                    console.error('Delete error:', error);
                    alert(error.message);
                }
            }
        });
    });



       class SmartHomeMap {
           constructor() {
               this.selectedDevice = null;
               this.initEventListeners();
           }

           initEventListeners() {
               // Device drag start
               document.querySelectorAll('.device').forEach(device => {
                   device.addEventListener('dragstart', (e) => {
                       this.selectedDevice = device;
                       e.dataTransfer.setData('text/plain', '');
                   });
               });
                
               
               // Room drop
               document.querySelectorAll('.room').forEach(room => {
                   room.addEventListener('dragover', (e) => e.preventDefault());

                   room.addEventListener('drop', (e) => {
                       e.preventDefault();
                       if (this.selectedDevice) {
                           const rect = room.getBoundingClientRect();
                           const x = e.clientX - rect.left - 30;
                           const y = e.clientY - rect.top - 30;
                           this.createDeviceInRoom(
                               this.selectedDevice.id,
                               Math.max(0, Math.min(x, rect.width - 60)),
                               Math.max(0, Math.min(y, rect.height - 60)),
                               room
                           );
                       }
                   });
               });
           }

           createDeviceInRoom(type, x, y, room) {
               const device = document.createElement('div');
               device.className = `device-in-room ${type}`;
               device.style.left = `${x}px`;
               device.style.top = `${y}px`;
               device.textContent = type === 'lightbulb' ? '💡' : '📟';

               const lightEffect = document.createElement('div');
               lightEffect.className = 'light-effect';

               device.addEventListener('click', () => {
                   device.classList.toggle('lightbulb-on');
                   room.classList.toggle('room-on');
                   lightEffect.style.opacity = device.classList.contains('lightbulb-on') ? 1 : 0;
               });

               room.appendChild(device);
               room.appendChild(lightEffect);

               // Make device draggable within the room
               device.addEventListener('mousedown', (e) => this.startDragging(device, room, e));
           }

           startDragging(device, room, e) {
               const rect = room.getBoundingClientRect();
               let shiftX = e.clientX - device.getBoundingClientRect().left;
               let shiftY = e.clientY - device.getBoundingClientRect().top;

               const moveAt = (clientX, clientY) => {
                   let x = clientX - rect.left - shiftX;
                   let y = clientY - rect.top - shiftY;

                   x = Math.max(0, Math.min(x, rect.width - device.offsetWidth));
                   y = Math.max(0, Math.min(y, rect.height - device.offsetHeight));

                   device.style.left = `${x}px`;
                   device.style.top = `${y}px`;
               };

               const onMouseMove = (e) => moveAt(e.clientX, e.clientY);
               const onMouseUp = () => {
                   document.removeEventListener('mousemove', onMouseMove);
                   document.removeEventListener('mouseup', onMouseUp);
               };

               document.addEventListener('mousemove', onMouseMove);
               document.addEventListener('mouseup', onMouseUp);
           }
       }
       const smartHomeMap = new SmartHomeMap();
       // Initialize the SmartHomeMap
       new SmartHomeMap();
