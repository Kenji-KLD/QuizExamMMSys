const menuItems = document.querySelectorAll('.menu-hover');
let selectedItem = document.querySelector('.menu-hover.selected');

    menuItems.forEach(item => {
      item.addEventListener('click', () => {
        if (selectedItem && selectedItem !== item) {
          selectedItem.classList.remove('selected');
        }
        item.classList.add('selected');
        selectedItem = item;
      });
    });

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