function logout(){
    $.ajax({
      url: "/php/logout_controller.php",
      method: "POST",
      beforeSend: function(xhr) {
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      },
      success: function(){
        window.location.replace('/dist/index.html');
      },
      error: function(error){
        console.error(error);
      }
    });
  }