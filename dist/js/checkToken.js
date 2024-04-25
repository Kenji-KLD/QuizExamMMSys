function checkToken(isLogin, gotoTokenRedirect) {
    $.ajax({
        url: "/php/checkToken_loading.php",
        method: "POST",
        data: {
            isLogin: isLogin
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            var data = JSON.parse(response);
            
            if(data.processed == true && gotoTokenRedirect == true){
                switch(data.accountType){
                    case "STUDENT":
                        window.location.replace('/dist/userView/html/home.html');
                        break;
                    case "FACULTY":
                        window.location.replace('/dist/profView/html/home-page.html');
                        break;
                    case "ADMIN":
                        window.location.replace('/Admin/Adminhome.php');
                        break;
                    default:
                        document.cookie = "session_token" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "userDetails" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        window.location.replace('/dist/index.html');
                        break;
                }
            }
            else if(data.processed == false && data.redirect_url != ''){
                window.location.replace(data.redirect_url);
            }
        },
        error: function(error){
            console.error(error);
        }
    });
}