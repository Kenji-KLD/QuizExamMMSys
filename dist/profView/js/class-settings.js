function addCard() {
    // Get the form inputs
    var subject = document.getElementById('Subject').value;
    var section = document.getElementById('Section').value;
  
    // Create a new card element
    var card = document.createElement('div');
    card.classList.add('card', 'w-auto', 'rounded-3xl');
  
    // Create a link element
    var link = document.createElement('a');
    link.href = 'class-page.html';
  
    // Create a heading element
    var heading = document.createElement('h1');
    heading.classList.add('font-bold', 'text-xl');
    heading.textContent = subject;
  
    // Create a subheading element
    var subheading = document.createElement('h5');
    subheading.classList.add('font-base', 'text-sm');
    subheading.textContent = section;
  
    // Append the heading and subheading to the link
    link.appendChild(heading);
    link.appendChild(subheading);
  
    // Append the link to the card
    card.appendChild(link);
  
    // Append the card to the parent container
    var parentContainer = document.getElementById('parent-container');
    parentContainer.appendChild(card);
  }