// Function to restrict special characters based on input field
function restrictSpecialChars(input) {
    var fieldName = input.id;
    var regex;

    // Define regex based on field name
    switch (fieldName) {
        case 'fName':
        case 'mName':
        case 'lName':
        case 'username':
            regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            break;
        case 'password':
            regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            break;
        case 'address':
            regex = /[!$%^&*()_+\=\[\]{};:"\\|<>?]+/;
            break;
        case 'email':
            regex = /[!#$%^&*()_+\-=\[\]{};':"\\|<>\/?]+/;
            break;
        case 'birthdate':
            // No need to restrict special characters for birthdate
            return; // Exit early for birthdate field
        default:
            regex = /[!@#$%^&*()_+\=\[\]{};':"\\|<>\/?]+/;
            break;
    }

    if (regex.test(input.value)) {
        input.value = input.value.replace(regex, '');
    }
}

// Add event listeners for input fields to restrict special characters
document.addEventListener('DOMContentLoaded', function() {
    var inputFields = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]');
    inputFields.forEach(function(input) {
        input.addEventListener('input', function() {
            restrictSpecialChars(this);
        });
    });

    // Add event listener for birthdate input to validate format
    document.getElementById('birthdate').addEventListener('input', function() {
        var input = this.value;
        var validDate = /^\d{4}\/\d{2}\/\d{2}$/.test(input);
        if (!validDate) {
            document.getElementById('birthdate-error').textContent = 'Invalid date format. Please use YYYY/MM/DD.';
        } else {
            document.getElementById('birthdate-error').textContent = '';
        }
    });
});

// Function to validate the birthdate format
function validateDate(input) {
    var dateString = input.value.trim();
    var validDate = /^\d{2}\/\d{2}\/\d{4}$/.test(dateString); // Updated regex for DD/MM/YYYY format

    if (!validDate) {
        document.getElementById('birthdate-error').textContent = 'Invalid date format. Please use DD/MM/YYYY.';
        return false;
    } else {
        document.getElementById('birthdate-error').textContent = '';
        return true;
    }
}


// Event listener to validate birthdate format as user types
document.getElementById('birthdate').addEventListener('input', function() {
    validateDate(this);
});

// Function to handle overall form validation before submission
function validateForm() {
    var birthdateInput = document.getElementById('birthdate');
    if (!validateDate(birthdateInput)) {
        return false;
    }

    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;

    // Check password criteria
    if (password.length < 8 || /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password) || /\s/.test(password)) {
        alert('Password must be at least 8 characters long and must not contain spaces or special characters.');
        return false; // Prevent form submission
    }

    return confirm("Are you sure you want to submit this form?");
}
