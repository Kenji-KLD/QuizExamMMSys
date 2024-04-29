function restrictSpecialChars(input) {
    var fieldName = input.id;
    var regex;
    
    // Define regex based on field name
    switch (fieldName) {
        case 'fName':
        case 'mName':
        case 'lName':
            regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|.<>\/?]+/;
            break;
        case 'password':
        case 'username':
            regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            break;
        case 'address':
            regex = /[!$%^&*()_+\=\[\]{};:"\\|<>\?]+/;
            break;
        case 'email':
            regex = /[!#$%^&*()_+\-=\[\]{};':"\\|<>\/?]+/;
            break;
        case 'student_ID':
            regex = /[!@#$%^&*()_+\=\[\]{};,':"\\|.<>\/?]+/;
            break;
        default:
            regex = /[!@#$%^&*()_+\=\[\]{};':"\\|<>\/?]+/;
            break;
    }
    
    if (regex.test(input.value)) {
        input.value = input.value.replace(regex, '');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var inputFields = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]');
    inputFields.forEach(function(input) {
        input.addEventListener('input', function() {
            restrictSpecialChars(this);
        });
    });
});
function validatePassword() {
    var passwordInput = document.getElementById('password');
    var password = passwordInput.value;

    // Check if the password meets the criteria
    if (password.length < 8 || /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password) || /\s/.test(password)) {
        alert('Password must be at least 8 characters long and must not contain any spaces.');
        return false; // Prevent form submission
    }
    return confirm("Are you sure you want to ADD this Student?");
}
