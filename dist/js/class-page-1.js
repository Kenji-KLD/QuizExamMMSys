jQuery(function () {
    $.ajax({
        url: "/php/class-page-1_loading.php",
        method: "POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            const data = JSON.parse(response);

            // Get the table body element by id
            const tableBody = $('#studentTable');
            let studentCount = 1;

            // Loop through the data array and create rows for each student
            data.forEach(student => {
                const row = $("<tr></tr>");
                row.html(`
                    <td class="text-center"><input type="checkbox" id="student${studentCount}"></td>
                    <td>${student.fullName}</td>
                    <td>${student.age}</td>
                    <td>${student.sex}</td>
                    <td>${student.email}</td>
                    <td>${student.address}</td>
                `);
                tableBody.append(row);
                studentCount++;
            });
        },
        error: function(error){
            console.error(error);
        }
    });
});