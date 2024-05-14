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
            console.log(response);
            let data = JSON.parse(response);

            const container = document.getElementById('subjectList'); // Assuming subjectList is the container element

            // Clear existing content in the container
            container.innerHTML = '';

            // Loop through the JSON data
            data.forEach(item => {
                let fullName = item.lName + ', ' + item.fName;
                if (item.mName) {
                    fullName += ' ' + item.mName.charAt(0) + '.';
                }

                // Create elements for card
                const card = document.createElement('div');
                card.classList.add('card-user', 'w-auto', 'rounded-3xl');

                const link = document.createElement('a');
                link.href = `/dist/userView/html/subject.html?secHandle_ID=${item.secHandle_ID}`; // Link URL

                const heading = document.createElement('h1');
                heading.classList.add('font-bold', 'text-xl');
                heading.textContent = item.subjectName; // Set subject name from JSON data

                const professor = document.createElement('h5');
                professor.classList.add('font-base', 'text-sm');
                professor.textContent = `${fullName}`; // Set professor's name from JSON data

                // Append elements
                link.appendChild(heading);
                link.appendChild(professor);
                card.appendChild(link);

                // Append card to container
                container.appendChild(card);
            });
        },
        error: function(error){
            console.error(error);
        }
    });
});