@extends('layouts.app')

@section('title', 'Orders')

@section('content')
   <link rel="stylesheet" href="{{ asset('css/style.css') }}">
   @vite(['resources/js/app.js'])
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" data-bs-target="#loginModal" data-bs-toggle="modal" href="#">Login</a>
        </li>
        <li class="nav-item"><a class="nav-link" data-bs-target="#signupModal" data-bs-toggle="modal" href="#">Sign
            Up</a></li>
    </ul>

    <script src="signup.js"></script>
    <script src="exampleOrders.js"></script>
</header>

<div class="container mt-5" id="mainContainer">
    <h2>Online Orders</h2> <!-- Add a button to open the example services modal -->
    <button class="btn btn-info" onclick="openExampleServicesModal()" type="button">Choose Example Service</button>

    <!-- Example Services Modal -->
    <div aria-hidden="true" aria-labelledby="exampleServicesModalLabel" class="modal fade" id="exampleServicesModal"
         tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleServicesModalLabel">Example Services</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body" id="exampleServicesModalBody">
                    <!-- Example services will be dynamically added here -->


                </div>
            </div>
        </div>
    </div>


    <!-- Authentication status -->

    <div id="authStatus" style="display: none;">
        <p>Welcome, <span id="loggedInUser"></span>! (<a href="#" onclick="logout()">Logout</a>)</p>
        <p>Please fill out the order form to make a order</p>
    </div>

    <div aria-hidden="true" aria-labelledby="loginModalLabel" class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="loginUsernameModal">Username:</label>
                        <input class="form-control" id="loginUsernameModal" required type="text">
                    </div>
                    <div class="form-group">
                        <label for="loginPasswordModal">Password:</label>
                        <input class="form-control" id="loginPasswordModal" required type="password">
                    </div>
                    <button class="btn btn-primary" onclick="loginModal()" type="button">Login</button>
                </div>
            </div>
        </div>
    </div>
    <div>

    </div>
    <!-- Sign-up Modal -->
    <div aria-hidden="true" aria-labelledby="signupModalLabel" class="modal fade" id="signupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="signupUsernameModal">Username:</label>
                        <input class="form-control" id="signupUsernameModal" required type="text">
                    </div>
                    <div class="form-group">
                        <label for="signupPasswordModal">Password:</label>
                        <input class="form-control" id="signupPasswordModal" oninput="checkPasswordStrength()"
                               required type="password">
                        <div class="text-muted" id="passwordStrengthMessage"></div>
                    </div>
                    <button class="btn btn-success" onclick="signupModal()" type="button">Sign Up</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Sign-up and Login forms -->
    <div id="authForms">
        <h1>Please Log/Sign in before ordering</h1>
        <!-- Sign-up form -->
        <div id="signupForm">
            <h3>Sign Up</h3>
            <div class="form-group">
                <label for="signupUsername">Username:</label>
                <input class="form-control" id="signupUsername" required type="text">
            </div>
            <div class="form-group">
                <label for="signupPassword">Password:</label>
                <input class="form-control" id="signupPassword" required type="password">
            </div>
            <button class="btn btn-success" onclick="submitOrder()" type="button">check</button>
            <button class="btn btn-success" onclick="signup()" type="button">Sign Up</button>

        </div>

        <hr>

        <!-- Sign-in form -->
        <div id="loginForm">
            <h3>Login</h3>
            <div class="foarm-group">
                <label for="loginUsername">Username:</label>
                <input class="form-control" id="loginUsername" required type="text">
            </div>
            <div class="form-group">
                <label for="loginPassword">Password:</label>
                <input class="form-control" id="loginPassword" required type="password">
            </div>
            <button class="btn btn-primary" onclick="login()" type="button">Login</button>
        </div>
    </div>


    <!-- CRUD operations form (visible only when logged in) -->
    <div id="crudForm" style="display: none;">
        <form id="orderForm">
            <div class="form-group">
                <label for="productName">Order name:</label>
                <input class="form-control" id="productName" required type="text">
            </div>

            <div class="form-group">
                <label for="details">Order Details:</label>
                <textarea class="form-control" id="details" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="attachment">Attachment (Atach a order file (img/pdf/etc)):</label>
                <input class="form-control" id="attachment" type="file">
            </div>
            <button class="btn btn-primary" onclick="addOrder()" type="button">Add Order</button>
        </form>

        <hr>

        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Order Details</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="orderTableBody">
            <!-- Orders will be dynamically added here -->
            </tbody>
        </table>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="editOrderModalLabel" class="modal fade" id="editOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <form id="editOrderForm">
                    <!-- Add the following line for editingIndex -->
                    <input id="editingIndex" type="hidden">
                    <div class="mb-3">
                        <label class="form-label" for="editProductName">Product Name:</label>
                        <input class="form-control" id="editProductName" required type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="editDetails">Details:</label>
                        <textarea class="form-control" id="editDetails"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="editAttachment">Attachment:</label>
                        <input class="form-control" id="editAttachment" onchange="handleFileChange(this)" type="file">
                    </div>
                    <button class="btn btn-primary" onclick="updateOrder()" type="button">Update Order</button>
                </form>
            </div>
        </div>
    </div>
</div>












































































































































































































































































































































<script>


function submitOrder() {
    // Get form data
    const username = document.getElementById('signupUsername').value;
    const password = document.getElementById('signupPassword').value;

    // Prepare data for the POST request
    const formData = {username, password};

    // Send a POST request to the server
    fetch('http://localhost:3000', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            if (data.success) {
                // Server-side validation successful
                // You can redirect or perform other actions here
                alert('Order submitted successfully!');
            } else {
                // Server-side validation failed
                alert('Order submission failed. Please check your inputs.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
}

// Function to sign up a new user
function signupModal() {
    const signupUsername = document.getElementById('signupUsernameModal').value;
    const signupPassword = document.getElementById('signupPasswordModal').value;

    // Simulate password hashing (replace with secure server-side hashing)
    const hashedPassword = CryptoJS.SHA256(signupPassword).toString();

    // Check if the username is already taken
    if (!users.some(user => user.username === signupUsername)) {
        // Add the new user to the simulated database
        users.push({
            username: signupUsername,
            password: hashedPassword
        });

        // Save the users array to local storage
        localStorage.setItem('users', JSON.stringify(users));

        // Clear the sign-up form in the modal
        document.getElementById('signupUsernameModal').value = '';
        document.getElementById('signupPasswordModal').value = '';

        alert('Sign up successful! Please log in.');
    } else {
        alert('Username already taken. Please choose a different username.');
    }
}

// Function to simulate user login
function loginModal() {
    const loginUsername = document.getElementById('loginUsernameModal').value;
    const loginPassword = document.getElementById('loginPasswordModal').value;

    // Simulate password hashing (replace with secure server-side hashing)
    const hashedPassword = CryptoJS.SHA256(loginPassword).toString();

    // Check if the provided credentials are valid
    const user = users.find(u => u.username === loginUsername && u.password === hashedPassword);

    if (user) {
        // Save the logged-in user to local storage
        localStorage.setItem('loggedInUser', user.username);

        // Display authentication status and hide login/sign-up forms
        document.getElementById('loggedInUser').textContent = user.username;
        document.getElementById('authStatus').style.display = 'block';
        document.getElementById('authForms').style.display = 'none';
        document.getElementById('crudForm').style.display = 'block';

        // Load orders from local storage for the logged-in user
        orders = JSON.parse(localStorage.getItem('orders-' + user.username)) || [];

        // Update the table with the loaded orders
        updateOrderTable();
    } else {
        alert('Invalid credentials. Please try again.');
    }
}


// Check if a user is already logged in
const loggedInUser = localStorage.getItem('loggedInUser');
if (loggedInUser) {
    document.getElementById('loggedInUser').textContent = loggedInUser;
    document.getElementById('authStatus').style.display = 'block';
    document.getElementById('authForms').style.display = 'none';
    document.getElementById('crudForm').style.display = 'block';

    // Load orders from local storage for the logged-in user
    orders = JSON.parse(localStorage.getItem('orders-' + loggedInUser)) || [];

    // Update the table with the loaded orders
    updateOrderTable();
}

// Simulated user database
const users = JSON.parse(localStorage.getItem('users')) || [];

// Function to sign up a new user
function signup() {
    const signupUsername = document.getElementById('signupUsername').value;
    const signupPassword = document.getElementById('signupPassword').value;

    // Simulate password hashing (replace with secure server-side hashing)
    const hashedPassword = CryptoJS.SHA256(signupPassword).toString();

    // Check if the username is already taken
    if (!users.some(user => user.username === signupUsername)) {
        // Add the new user to the simulated database
        users.push({
            username: signupUsername,
            password: hashedPassword
        });

        // Save the users array to local storage
        localStorage.setItem('users', JSON.stringify(users));

        // Clear the sign-up form
        document.getElementById('signupUsername').value = '';
        document.getElementById('signupPassword').value = '';

        alert('Sign up successful! Please log in.');
    } else {
        alert('Username already taken. Please choose a different username.');
    }
}

// Function to simulate user login
function login() {
    const loginUsername = document.getElementById('loginUsername').value;
    const loginPassword = document.getElementById('loginPassword').value;
    if ((loginUsername || loginPassword) != "") {

        // Simulate password hashing (replace with secure server-side hashing)
        const hashedPassword = CryptoJS.SHA256(loginPassword).toString();

        // Check if the provided credentials are valid
        const user = users.find(u => u.username === loginUsername && u.password === hashedPassword);

        if (user) {
            // Save the logged-in user to local storage
            localStorage.setItem('loggedInUser', user.username);

            // Display authentication status and hide login/sign-up forms
            document.getElementById('loggedInUser').textContent = user.username;
            document.getElementById('authStatus').style.display = 'block';
            document.getElementById('authForms').style.display = 'none';
            document.getElementById('crudForm').style.display = 'block';
            document.getElementById('applyServiceContainer').style.display = 'block';
            // Load orders from local storage for the logged-in user
            orders = JSON.parse(localStorage.getItem('orders-' + user.username)) || [];

            // Update the table with the loaded orders
            updateOrderTable();
        } else {
            alert('Invalid credentials. Please try again.');
        }
    }
}

// Function to log out the user
function logout() {
    // Remove the logged-in user from local storage
    localStorage.removeItem('loggedInUser');

    // Reset the forms and display the login/sign-up forms
    document.getElementById('signupUsername').value = '';
    document.getElementById('signupPassword').value = '';
    document.getElementById('loginUsername').value = '';
    document.getElementById('loginPassword').value = '';

    // Clear the orders array
    orders = [];
    // Update local storage with the modified orders array
    localStorage.setItem('orders', JSON.stringify(orders));

    // Update the table with the modified orders array
    updateOrderTable();

    // Display the login/sign-up forms and hide the authentication status and CRUD form
    document.getElementById('authForms').style.display = 'block';
    document.getElementById('authStatus').style.display = 'none';
    document.getElementById('crudForm').style.display = 'none';
    document.getElementById('applyServiceContainer').style.display = 'none';


}

// Function to add a new order
function addOrder() {
    const productName = document.getElementById('productName').value;
    const details = document.getElementById('details').value;
    const attachmentInput = document.getElementById('attachment');

    if (productName) {
        // Check if a file is selected
        if (attachmentInput.files.length > 0) {
            const file = attachmentInput.files[0];
            const reader = new FileReader();

            // Read the contents of the file
            reader.onload = function (e) {
                const attachment = e.target.result;

                // Create a new order object
                const order = {
                    productName: productName,
                    details: details,
                    attachment: attachment
                };

                // Add the order to the orders array
                orders.push(order);

                // Update local storage with the modified orders array
                localStorage.setItem('orders-' + localStorage.getItem('loggedInUser'), JSON.stringify(orders));

                // Clear the form inputs
                document.getElementById('productName').value = '';
                document.getElementById('details').value = '';
                document.getElementById('attachment').value = '';

                // Update the table with the new order
                updateOrderTable();
            };

            // Read the file as a data URL
            reader.readAsDataURL(file);
        } else {
            // No file selected
            alert('Please select a file for attachment.');
        }
    } else {
        alert('Please fill in all required fields');
    }
}

// Function to update the table with orders
function updateOrderTable() {
    const tableBody = document.getElementById('orderTableBody');
    tableBody.innerHTML = '';

    // Iterate through the orders array and add rows to the table
    for (let i = 0; i < orders.length; i++) {
        const order = orders[i];

        // Get the file extension from the attachment
        const fileExtension = order.attachment.split(';')[0].split('/')[1];

        // Display only the first 20 characters of the product name
        const displayName = order.productName.length > 20 ? order.productName.substring(0, 20) + '...' : order.productName;

        const row = `<tr>
        <td>
            <span title="${order.productName}" data-bs-toggle="tooltip" data-bs-placement="top">
                ${displayName}
            </span>
        </td>
        <td>${order.details}</td>
        <td>
            <button class="btn btn-link" onclick="showAttachment('${order.attachment}', '${order.productName}')">View Attachment</button>
            <div>File Extension: ${fileExtension}</div>
        </td>
        <td>
            <button class="btn btn-warning" onclick="editOrder(${i})">Edit</button>
            <button class="btn btn-danger" onclick="deleteOrder(${i})">Delete</button>
        </td>
    </tr>`;
        tableBody.innerHTML += row;
    }

    // Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Function to show the attachment (image or other file types)

function showAttachment(attachment, productName) {
    if (attachment) {
        // Create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Open the file as 'blob'
        xhr.open("GET", attachment, true);

        // Set the responseType to 'blob'
        xhr.responseType = "blob";

        // When the request completes
        xhr.onload = function () {
            // Create a blob from the response
            var blob = new Blob([xhr.response], {type: xhr.getResponseHeader("Content-Type")});

            // Create a URL for the blob
            var objectURL = URL.createObjectURL(blob);

            // Open the file in a new window or tab
            window.open(objectURL, '_blank');
        };

        // Send the request
        xhr.send();
    }
}

// Function to open the image in a modal
function openImageModal(imageUrl, productName) {
    const modalContent = `<div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${productName}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="${imageUrl}" alt="${productName}" style="max-width: 100%;">
            </div>
        </div>
    </div>`;

    // Dynamically create a modal and add it to the body
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalContent;
    document.body.appendChild(modalContainer);

    // Initialize the Bootstrap modal
    const imageModal = new bootstrap.Modal(modalContainer.firstChild);
    imageModal.show();
}

// Function to open the PDF in a new window or tab
function openPdf(pdfUrl) {
    // Open the PDF in a new window or tab
    window.open(pdfUrl, '_blank');
}

// Function to edit an order

function editOrder(index) {
    const order = orders[index];
    const editProductNameInput = document.getElementById('editProductName');
    const editDetailsInput = document.getElementById('editDetails');
    const editAttachmentInput = document.getElementById('editAttachment');
    const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal'));

    // Check if all required elements are present
    if (editProductNameInput && editDetailsInput && editAttachmentInput) {
        // Update form fields with order details
        editProductNameInput.value = order.productName;
        editDetailsInput.value = order.details;

        // Create a new file input
        const newFileInput = document.createElement('input');
        newFileInput.type = 'file';
        newFileInput.className = 'form-control';
        newFileInput.id = 'editAttachment';
        newFileInput.accept = 'image/*, application/pdf';

        // Replace the existing file input with the new one
        editAttachmentInput.parentNode.replaceChild(newFileInput, editAttachmentInput);

        // Show the edit modal using JavaScript
        editOrderModal.show();

        // Store the index of the editing order
        document.getElementById('editingIndex').value = index;

        // Listen for changes in the form fields and update the order in real-time
        editProductNameInput.addEventListener('input', () => order.productName = editProductNameInput.value);
        editDetailsInput.addEventListener('input', () => order.details = editDetailsInput.value);

        // Listen for changes in the file input
        newFileInput.addEventListener('change', () => handleFileChange(newFileInput));
    } else {
        console.error("Failed to find one or more edit form elements.");
    }
}


// Function to update an order
function updateOrder() {
    const index = document.getElementById('editingIndex').value;
    const order = orders[index];
    const editProductNameInput = document.getElementById('editProductName');
    const editDetailsInput = document.getElementById('editDetails');
    const editAttachmentInput = document.getElementById('editAttachment');

    if (editProductNameInput && editDetailsInput && editAttachmentInput) {
        const productName = editProductNameInput.value;
        const details = editDetailsInput.value;
        const attachment = editAttachmentInput.value;

        if (productName) {
            // Update the existing order
            order.productName = productName;
            order.details = details;
            order.attachment = attachment;

            // Update local storage with the modified orders array
            localStorage.setItem('orders-' + localStorage.getItem('loggedInUser'), JSON.stringify(orders));

            // Clear the edit form inputs
            editProductNameInput.value = '';
            editDetailsInput.value = '';
            editAttachmentInput.value = '';


            // Update the table with the modified orders array
            updateOrderTable();
        } else {
            alert('Please fill in all required fields');
        }
    } else {
        console.error("Failed to find one or more edit form elements.");
    }
}

// Function to handle file change in the edit form
function handleFileChange(input) {
    const file = input.files[0];
    const editAttachmentInput = document.getElementById('editAttachment');

    if (file) {
        const reader = new FileReader();

        // Read the contents of the file
        reader.onload = function (e) {
            const attachment = e.target.result;

            // Create a new file input element
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.className = 'form-control';
            newInput.id = 'editAttachment';

            // Replace the existing input with the new one
            editAttachmentInput.parentNode.replaceChild(newInput, editAttachmentInput);

            // Set the value of the new file input
            newInput.value = attachment;

            // Listen for changes in the new file input
            newInput.addEventListener('change', function () {
                handleFileChange(newInput);
            });
        };

        // Read the file as a data URL
        reader.readAsDataURL(file);

    } else {
        // Clear the attachment input value
        editAttachmentInput.value = '';
    }
}

// Function to delete an order
function deleteOrder(index) {
    // Remove the order from the array
    orders.splice(index, 1);

    // Update local storage with the modified orders array
    localStorage.setItem('orders-' + localStorage.getItem('loggedInUser'), JSON.stringify(orders));

    // Update the table with the modified orders array
    updateOrderTable();
}

</script>
















<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>



</body>
</html>
