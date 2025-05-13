// Wait until the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {

    // Function to show alert messages
    function showAlert(type, message) {
        // Create alert div
        var alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
        alertDiv.role = 'alert';
        alertDiv.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        // Append alert to the body or a specific container
        document.body.appendChild(alertDiv);

        // Auto-hide alert after 5 seconds
        setTimeout(function () {
            alertDiv.classList.remove('show');
        }, 5000);
    }

    // Example usage of the showAlert function
    // showAlert('success', 'Registration successful!');
    // showAlert('danger', 'Invalid email address!');

    // Function to open modal by id
    function openModal(modalId) {
        var modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    // Function to close modal by id
    function closeModal(modalId) {
        var modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        modal.hide();
    }

    // Example usage of open and close modal
    // openModal('myModal'); // Open modal with id 'myModal'
    // closeModal('myModal'); // Close modal with id 'myModal'

    // Toggle password visibility for login/registration forms
    var passwordToggle = document.querySelectorAll('.password-toggle');
    passwordToggle.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            var passwordField = document.getElementById(toggle.getAttribute('data-target'));
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggle.classList.add('bi-eye'); // Bootstrap icon to show eye
                toggle.classList.remove('bi-eye-slash'); // Hide eye
            } else {
                passwordField.type = 'password';
                toggle.classList.remove('bi-eye'); // Bootstrap icon to hide eye
                toggle.classList.add('bi-eye-slash'); // Show eye slash
            }
        });
    });

    // Scroll to top button functionality
    var scrollToTopBtn = document.getElementById('scrollToTopBtn');
    if (scrollToTopBtn) {
        window.onscroll = function () {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        scrollToTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Auto-close alert after 5 seconds (Optional if you want auto-close alerts)
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.classList.remove('show');
        }, 5000); // Auto-hide after 5 seconds
    });

    // Confirm dialog for delete actions
    function confirmDelete(message, callback) {
        var confirmation = confirm(message);
        if (confirmation) {
            callback(); // Execute the callback if user confirms
        }
    }

    // Example usage of confirmDelete
    // confirmDelete('Are you sure you want to delete this record?', function() {
    //     // Delete action here, for example:
    //     console.log('Record deleted!');
    // });

});
