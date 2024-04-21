function initiateLogin(){
    const jsonData = {
        userName: $('#Username').val(),
        password: $('#Password').val(),
        tac_checkbox: $('#TAC_Checkbox').is(':checked') ? true : false
    };

    $.ajax({
        url: "/php/login_controller.php",
        method: "POST",
        data: {jsonData: JSON.stringify(jsonData)},
        dataType: 'json',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            if(response.processed == true){
                window.location.replace('./index.html');
            }
            else{
                alert(response.error_message);
            }
        },
        error: function(error){
            console.error(error);
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('.flex.cursor-pointer').addEventListener('click', function() {
        initiateLogin();
    });
});