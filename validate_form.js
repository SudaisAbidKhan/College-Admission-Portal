// Function to validate the registration form
function validateRegistrationForm() {
    // Get form values
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    // Validate Name (Required)
    if (name === "") {
        alert("Please enter your name.");
        return false;
    }

    // Validate Email (Required and proper email format)
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (email === "" || !emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate Phone Number (Required and should be numeric)
    var phonePattern = /^[0-9]{10}$/;
    if (phone === "" || !phonePattern.test(phone)) {
        alert("Please enter a valid phone number (10 digits).");
        return false;
    }

    // Validate Password (Required, at least 6 characters)
    if (password === "" || password.length < 6) {
        alert("Please enter a password with at least 6 characters.");
        return false;
    }

    // Validate Confirm Password (Must match Password)
    if (confirmPassword === "" || confirmPassword !== password) {
        alert("Password and confirm password do not match.");
        return false;
    }

    // All validations passed
    return true;
}

// Function to validate the admission form (submit_application.php)
function validateAdmissionForm() {
    // Get form values
    var program = document.getElementById('program').value;
    var marks = document.getElementById('marks').value;
    var year = document.getElementById('year').value;
    var fileInput = document.getElementById('documents');

    // Validate Program (Required)
    if (program === "") {
        alert("Please select a program.");
        return false;
    }

    // Validate Marks (Required and numeric)
    if (marks === "" || isNaN(marks) || marks < 0) {
        alert("Please enter valid marks.");
        return false;
    }

    // Validate Year of Passing (Required and numeric)
    if (year === "" || isNaN(year) || year.length !== 4) {
        alert("Please enter a valid year of passing.");
        return false;
    }

    // Validate Document Upload (Required)
    if (fileInput.files.length === 0) {
        alert("Please upload the required documents.");
        return false;
    }

    // Check file type for uploaded document
    var file = fileInput.files[0];
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(file.name)) {
        alert("Invalid file type. Please upload only .jpg, .jpeg, .png, or .pdf files.");
        return false;
    }

    // All validations passed
    return true;
}

// Function to validate the login form (login.php)
function validateLoginForm() {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Validate Email (Required)
    if (email === "") {
        alert("Please enter your email address.");
        return false;
    }

    // Validate Password (Required)
    if (password === "") {
        alert("Please enter your password.");
        return false;
    }

    // All validations passed
    return true;
}

// Function to validate the profile form (student_dashboard.php)
function validateProfileForm() {
    var name = document.getElementById('name').value;
    var phone = document.getElementById('phone').value;

    // Validate Name (Required)
    if (name === "") {
        alert("Please enter your name.");
        return false;
    }

    // Validate Phone Number (Required and should be numeric)
    var phonePattern = /^[0-9]{10}$/;
    if (phone === "" || !phonePattern.test(phone)) {
        alert("Please enter a valid phone number (10 digits).");
        return false;
    }

    // All validations passed
    return true;
}

// Attach validation functions to form events (if needed)
document.getElementById("registrationForm").onsubmit = validateRegistrationForm;
document.getElementById("admissionForm").onsubmit = validateAdmissionForm;
document.getElementById("loginForm").onsubmit = validateLoginForm;
document.getElementById("profileForm").onsubmit = validateProfileForm;
