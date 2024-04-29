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