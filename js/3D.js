const rooms = [
  { name: "Living Room", color: 0x4A90E2, size: { x: 8, y: 5, z: 6 }, position: { x: 0, y: 0, z: 0 } },
  { name: "Kitchen", color: 0x50E3C2, size: { x: 6, y: 5, z: 4 }, position: { x: 10, y: 0, z: 0 } },
  { name: "Bedroom", color: 0xE3507A, size: { x: 8, y: 5, z: 6 }, position: { x: 20, y: 0, z: 0 } },
  { name: "Hall", color: 0x000000, size: { x: 30, y: 5, z: 6 }, position: { x: 5, y: 0, z: 5 } }
];












// Send room details to a server via a POST request
fetch("http://localhost/dashboard/laraveltest/public/api/store-rooms", {
  method: "POST",
  headers: {
      "Content-Type": "application/json" // This tells the server we're sending JSON data
  },
  body: JSON.stringify({ rooms: rooms }) // Send rooms inside JSON
})
.then(response => response.json())  // If the server responds, parse the JSON data
.then(data => console.log(data))  // Log the data to the console
.catch(error => console.error("Error:", error));  // If there's an error, log it


// THREE.js Setup: Creating the 3D environment
const scene = new THREE.Scene();  // This is where all the 3D objects will live
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);  // Camera settings
const renderer = new THREE.WebGLRenderer({ antialias: true });  // Renderer with anti-aliasing for smoother images
renderer.setSize(window.innerWidth, window.innerHeight);  // Set the size of the renderer to fill the screen
renderer.setClearColor(0x1a1a1a);  // Set the background color to dark grey
document.body.appendChild(renderer.domElement);  // Add the 3D canvas to the webpage

    // OrbitControls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    camera.position.set(20, 15, 20);
    controls.update();

// Lighting: Add ambient and directional lights to the scene
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(10, 20, 10);
    scene.add(directionalLight);
// Smart Home setup: managing rooms and devices (lights, sensors, etc.)
window.setDeviceType = function(type) {
  smartHome.setDeviceType(type);
};
// Main SmartHome class
    class SmartHome {
      constructor() {
        this.rooms = [];  // Store all rooms
        this.devices = [];  // Store all devices like lights or sensors
        this.selectedDevice = null;  // Keep track of the selected device
        this.devicePlacementActive = false;  // Flag to determine if we're placing devices
        this.deviceType = 'light';  // Default device type is 'light'
        this.initHome();  // Initialize home setup
      }
      initHome() {
        rooms.forEach((roomConfig) => {
          const room = this.createRoom(roomConfig);  // Create each room
          room.userData.label = this.addRoomLabel(roomConfig);  // Add labels to rooms
          this.rooms.push(room);  // Add room to the list of rooms
        });
      }


      
      // Create a room with local coordinates for its children.
      createRoom(config) {
        const roomGroup = new THREE.Group();
        // Set the roomGroup's position to the room's base position.
        roomGroup.position.set(config.position.x, 0, config.position.z);
        // Floor: created at local origin.
        const floor = new THREE.Mesh(
          new THREE.BoxGeometry(config.size.x, 0.1, config.size.z),
          new THREE.MeshPhongMaterial({ color: config.color, opacity: 0.8, transparent: true })
        );
        floor.position.set(0, 0, 0);  // Set floor position
        floor.userData.isFloor = true;  // Mark it as a floor
        roomGroup.add(floor);  // Add floor to the room group
        // Walls: position them relative to the group.
        const wallMaterial = new THREE.MeshPhongMaterial({
          color: 0xffffff,
          opacity: 0.3,
          transparent: true
        });
        // Front and back walls.
        ['front', 'back'].forEach((side) => {
          const geometry = new THREE.BoxGeometry(config.size.x, config.size.y, 0.1);
          const zOffset = side === 'front' ? -config.size.z/2 : config.size.z/2;
          const wall = new THREE.Mesh(geometry, wallMaterial);
          wall.position.set(0, config.size.y/2, zOffset);
          roomGroup.add(wall);
        });
        // Left and right walls.
        ['left', 'right'].forEach((side) => {
          const geometry = new THREE.BoxGeometry(0.1, config.size.y, config.size.z);
          const xOffset = side === 'left' ? -config.size.x/2 : config.size.x/2;
          const wall = new THREE.Mesh(geometry, wallMaterial);
          wall.position.set(xOffset, config.size.y/2, 0);
          roomGroup.add(wall);
        });
        scene.add(roomGroup);
        return roomGroup;
      }
      addRoomLabel(config) {
        const label = document.createElement('div');
        label.className = 'room-label draggable';
        label.textContent = config.name;
        // Initial update using the base position.
        updateLabelPosition(label, new THREE.Vector3(config.position.x, 0, config.position.z));
        document.body.appendChild(label);
        return label;
      }
      // Update room label position using the roomGroup's world position.
      updateRoomLabel(room) {
        if (room.userData.label) {
          const worldPos = new THREE.Vector3();
          room.getWorldPosition(worldPos); 
          worldPos.project(camera);
          const x = (worldPos.x + 1) / 2 * window.innerWidth;
          const y = (-worldPos.y + 1) / 2 * window.innerHeight;
          room.userData.label.style.left = `${x - room.userData.label.offsetWidth/2}px`;
          room.userData.label.style.top = `${y}px`;
        }
      }
      addDevice(type, position, roomIndex) {
        const device = new THREE.Mesh(
          new THREE.SphereGeometry(0.3),
          new THREE.MeshPhongMaterial({
            color: type==='light' ? 0xFFFF00 : 0x00FF00,
            emissive: type==='light' ? 0x444444 : 0x000000,
            emissiveIntensity: 0.5
          })
        );
        device.position.copy(position);
        device.userData = {
          type: type,
          room: rooms[roomIndex].name,
          status: 'off',
          toggle: () => {
            device.userData.status = device.userData.status==='off' ? 'on' : 'off';
            device.material.emissiveIntensity = device.userData.status==='on' ? 2 : 0.5;
            if (type==='light') {
              if (device.userData.status==='on') {
                device.material.color.setHex(0xFF0000);
                toggleLightSphere(device, false);
              } else {
                device.material.color.setHex(0xFFFF00);
                toggleLightSphere(device, true);
              }
            } else if (type==='sensor') {
              if (device.userData.status==='on') {
                device.material.color.setHex(0xFF0000);
                toggleSensorExclamation(device, true);
              } else {
                device.material.color.setHex(0x00FF00);
                toggleSensorExclamation(device, false);
              }
            }
            this.updateDeviceControls();
          }
        };
        this.devices.push(device);
        scene.add(device);
        return device;
      }
      updateDeviceControls() {
        const controlsDiv = document.getElementById('device-controls');
        controlsDiv.innerHTML = this.selectedDevice
          ? `<h3>${this.selectedDevice.userData.type.toUpperCase()}</h3>
             <p>Room: ${this.selectedDevice.userData.room}</p>
             <p>Status: ${this.selectedDevice.userData.status}</p>
             <button onclick="smartHome.selectedDevice.userData.toggle()">
               Toggle ${this.selectedDevice.userData.type}
             </button>`
          : `<p>Select a device to control</p>`;
      }
      
      toggleDevicePlacement() {
        this.devicePlacementActive = !this.devicePlacementActive;
        const toggleButton = document.getElementById('device-placement-toggle');
        toggleButton.classList.toggle('active', this.devicePlacementActive);
        toggleButton.textContent = this.devicePlacementActive
          ? `Placing ${this.deviceType.charAt(0).toUpperCase() + this.deviceType.slice(1)}`
          : 'Toggle Device Placement';
      }

      
      setDeviceType(type) {
        this.deviceType = type;
        if (this.devicePlacementActive) {
          const toggleButton = document.getElementById('device-placement-toggle');
          toggleButton.textContent = `Placing ${type.charAt(0).toUpperCase() + type.slice(1)}`;
        }
      }
    }

    // Helper for light devices: add or remove a light sphere.
    function toggleLightSphere(device, show) {
      if (show) {
        const sphere = new THREE.Mesh(
          new THREE.SphereGeometry(1.5, 32, 32),
          new THREE.MeshBasicMaterial({ color: 0xFFFF00, transparent: true, opacity: 0.3 })
        );
        sphere.position.copy(device.position);
        device.userData.lightSphere = sphere;
        scene.add(sphere);
      } else {
        if (device.userData.lightSphere) {
          scene.remove(device.userData.lightSphere);
          device.userData.lightSphere = null;
        }
      }
    }

    // Helper for sensor devices: add or remove an exclamation mark sprite.
    function toggleSensorExclamation(device, show) {
      if (show) {
        const canvas = document.createElement('canvas');
        canvas.width = 64;
        canvas.height = 64;
        const ctx = canvas.getContext('2d');
        ctx.font = "48px Arial";
        ctx.fillStyle = "red";
        ctx.textAlign = "center";
        ctx.fillText("!", 32, 48);
        const texture = new THREE.CanvasTexture(canvas);
        const spriteMaterial = new THREE.SpriteMaterial({ map: texture, transparent: true });
        const sprite = new THREE.Sprite(spriteMaterial);
        sprite.scale.set(2,2,1);
        sprite.position.set(device.position.x, device.position.y+2, device.position.z);
        device.userData.exclamation = sprite;
        scene.add(sprite);
      } else {
        if (device.userData.exclamation) {
          scene.remove(device.userData.exclamation);
          device.userData.exclamation = null;
        }
      }
    }

    // Utility: update an HTML label's position by projecting a world vector.
    function updateLabelPosition(label, worldPos) {
      const pos = worldPos.clone();
      pos.project(camera);
      const x = (pos.x + 1) / 2 * window.innerWidth;
      const y = (-pos.y + 1) / 2 * window.innerHeight;
      label.style.left = `${x - label.offsetWidth/2}px`;
      label.style.top = `${y}px`;
    }

    // Initialize smart home instance.
    const smartHome = new SmartHome();
    
    // Device placement toggle.
    document.getElementById('device-placement-toggle').addEventListener('click', () => {
      smartHome.toggleDevicePlacement();
    });
    function setDeviceType(type) {
      smartHome.setDeviceType(type);
    }

    // Raycaster and mouse vector.
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();

    // Variables for room dragging.
    let selectedRoom = null;
    let roomOffset = new THREE.Vector3();

    // Global click handler: in placement mode, add devices; otherwise, toggle device state.
    window.addEventListener('click', (event) => {
      mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
      mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
      raycaster.setFromCamera(mouse, camera);
      if (smartHome.devicePlacementActive) {
        const intersects = raycaster.intersectObjects(scene.children, true);
        if (intersects.length > 0) {
          const point = intersects[0].point;
          const roomIndex = Math.floor(point.x / 10);
          smartHome.addDevice(smartHome.deviceType, point, roomIndex);
        }
      } else {
        const intersects = raycaster.intersectObjects(smartHome.devices, true);
        if (intersects.length > 0) {
          const device = intersects[0].object;
          device.userData.toggle();
        }
      }
    });

    // Room dragging: disable orbit controls while dragging.
    renderer.domElement.addEventListener('pointerdown', onPointerDown);
    renderer.domElement.addEventListener('pointermove', onPointerMove);
    renderer.domElement.addEventListener('pointerup', onPointerUp);
    window.addEventListener('pointerup', onPointerUp);

    function onPointerDown(event) {
      mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
      mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
      raycaster.setFromCamera(mouse, camera);
      const floors = smartHome.rooms.map(room => room.children[0]);
      const intersects = raycaster.intersectObjects(floors);
      if (intersects.length > 0) {
        selectedRoom = intersects[0].object.parent;
        roomOffset.copy(selectedRoom.position).sub(intersects[0].point);
        controls.enabled = false;
      }
    }
    function onPointerMove(event) {
      if (selectedRoom) {
        mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
        mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
        raycaster.setFromCamera(mouse, camera);
        const plane = new THREE.Plane(new THREE.Vector3(0,1,0), 0);
        const newPos = new THREE.Vector3();
        raycaster.ray.intersectPlane(plane, newPos);
        if (newPos) {
          selectedRoom.position.copy(newPos.add(roomOffset));
          smartHome.updateRoomLabel(selectedRoom);
        }
      }
    }
    function onPointerUp(event) {
      selectedRoom = null;
      controls.enabled = true;
    }

    // Animation loop.
    function animate() {
      requestAnimationFrame(animate);
      // Update all room labels in case of camera movement.
      smartHome.rooms.forEach(room => smartHome.updateRoomLabel(room));
      controls.update();
      renderer.render(scene, camera);
    }
    animate();

    // Responsive handling.
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });