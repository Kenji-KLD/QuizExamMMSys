jquery(function() {
   $.ajax({
        url: "/php/ar-class_loading.php",
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