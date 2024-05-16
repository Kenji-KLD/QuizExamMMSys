import { getCookie } from '/dist/js/function.js';

window.changePassword = function () {
    var oldPass = document.getElementById('oldPass').value;
    var newPass = document.getElementById('newPass').value;
    var confNewPass = document.getElementById('confNewPass').value;

    if(confirm("Are you sure you want to change your password") != true){
    }
    else if(newPass != confNewPass){
        alert('Passwords do not match');
    }
    else{
        $.ajax({
            url: "/php/settings_controller.php",
            method: "POST",
            data: {
                oldPass: oldPass,
                newPass: newPass
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response){
                let data = JSON.parse(response);

                if(data.processed == true){
                    alert("Success");
                    location.reload();
                }
                else{
                    alert(data.error_message);
                }
            },
            error: function(error){
                console.error(error);
            }
        });
    }
}

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