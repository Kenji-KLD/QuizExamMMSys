import { getParameterByName } from '/dist/js/function.js';

jQuery(function () {
    let secHandle_ID = getParameterByName('secHandle_ID');

    $.ajax({
        url: "/php/class-page-1_loading.php",
        method: "POST",
        data: {
            secHandle_ID: secHandle_ID
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            const data = JSON.parse(response);

            // Display subHandle_ID's Handled Subject and Section
            document.getElementById('subjectName').textContent = data.subjectName;
            document.getElementById('section_ID').textContent = data.section_ID;

            // Get the table body element by id
            const tableBody = $('#studentTable');
            let studentCount = 1;

            // Loop through the data array and create rows for each student
            data.studentList.forEach(student => {
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