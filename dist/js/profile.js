import { getCookie } from '/dist/js/function.js';

jQuery(function() {
    let userDetails = JSON.parse(decodeURIComponent(getCookie('userDetails')));
    let fullName = userDetails['lName'] + ', ' + userDetails['fName'];
        if (userDetails['mName']) {
            fullName += ' ' + userDetails['mName'].charAt(0) + '.';
    }

    $.ajax({
        url: "/php/profile_loading.php",
        method: "POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            let studentDetails = JSON.parse(response);

            document.getElementById('fullName').textContent = fullName;
            document.getElementById('student_ID').textContent = studentDetails['student_ID'];
            document.getElementById('email').textContent = studentDetails['email'];
        },
        error: function(error){
            console.error(error);
        }
    });
});