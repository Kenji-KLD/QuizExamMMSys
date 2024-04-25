jQuery(function () {
    $.ajax({
        url: "/php/home-page_loading.php",
        method: "POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function (response) {
            console.log(response);
            data = JSON.parse(response);
            
            // Get the parent element
            const subjectHandle = document.getElementById("subjectHandle");

            // Loop through the data and create the div elements
            data.forEach(item => {
                const card = document.createElement("div");
                card.classList.add("card", "w-auto", "rounded-3xl");

                const link = document.createElement("a");
                link.href = "class-page-1.html";

                const h1 = document.createElement("h1");
                h1.classList.add("font-bold", "text-xl");
                h1.textContent = item.subjectName;

                const h5 = document.createElement("h5");
                h5.classList.add("font-base", "text-sm");
                h5.textContent = `${item.section_ID}`;

                link.appendChild(h1);
                link.appendChild(h5);
                card.appendChild(link);
                subjectHandle.appendChild(card);
            });
        },
        error: function (error) {
            console.error(error);
        }
    });
});