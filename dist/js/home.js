import { getCookie } from '/dist/js/function.js';

jQuery(function() {
    let userDetails = JSON.parse(decodeURIComponent(getCookie('userDetails')));
    document.getElementById('fName').textContent = userDetails['fName'];

    $.ajax({
        url: "/php/home_loading.php",
        method: "POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            
        },
        error: function(error){
            console.error(error);
        }
    });
});