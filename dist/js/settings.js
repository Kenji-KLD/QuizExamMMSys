function getCookie(cookieName) {
    var cookies = document.cookie.split(';');

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.startsWith(cookieName + '=')) {
            return cookie.substring(cookieName.length + 1);
        }
    }

    return null;
}

function changePassword(){
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
                console.log(response);
                data = JSON.parse(response);

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

jQuery(function () {
    userDetails = JSON.parse(decodeURIComponent(getCookie('userDetails')));

    document.getElementById('fullName').textContent = userDetails['fullName'];
    document.getElementById('email').textContent = userDetails['email'];
    document.getElementById('sex').textContent = userDetails['sex'];
    document.getElementById('age').textContent = userDetails['age'];
});