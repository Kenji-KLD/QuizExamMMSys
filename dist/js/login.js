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
                switch(response.accountType){
                    case "STUDENT":
                        window.location.replace('/dist/tac.html');
                        break;
                    case "FACULTY":
                        window.location.replace('/dist/profView/html/home-page.html');
                        break;
                    case "ADMIN":
                        window.location.replace('/Admin/adminhome.php');
                        break;
                    default:
                        alert(response.accountType);
                        window.location.replace('/dist/index.html');
                        break;
                }
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