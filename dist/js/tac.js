function tacAccepted(){
    localStorage.setItem('tacRead', true);

    window.location.replace('/dist/login.html');
}

jQuery(function() {
    if(localStorage.getItem('tacRead') == 'true'){
        window.location.replace('/dist/login.html');
    }
})