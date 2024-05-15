import { getParameterByName } from '/dist/js/function.js';

var originalStyle = {
    removeButton: document.getElementById('removeButton').style.display,
    selectColumn: document.getElementById('selectColumn').style.display,
};

window.toggleRemove = function () {
    var removeButton = document.getElementById('removeButton');
    var selectColumn = document.getElementById('selectColumn');
    var checkboxes = document.getElementsByClassName('tableSelect');
    
    removeButton.style.display = (removeButton.style.display !== 'none') ? 'none' : originalStyle['removeButton'];
    selectColumn.style.display = (selectColumn.style.display !== 'none') ? 'none' : originalStyle['selectColumn'];
    
    Array.from(checkboxes).forEach(checkbox => {
        checkbox.style.display = (checkbox.style.display !== 'none') ? 'none' : '';
        checkbox.checked = false;
    });
}

window.updateClass = function () {
    $.ajax({
        url: "/php/class-page-1_controller.php",
        method: "POST",
        data: {
            secHandle_ID: getParameterByName('secHandle_ID')
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){

        },
        error: function(error){
            console.error(error);
        }
    });
}

jQuery(function () {
    // Changing href redirects to contain GET variable
    const assessmentsLink = document.getElementById('assessmentsLink');
    assessmentsLink.setAttribute('href', assessmentsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const makeLink = document.getElementById('makeLink');
    makeLink.setAttribute('href', makeLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const holdLink = document.getElementById('holdLink');
    holdLink.setAttribute('href', holdLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/class-page-1_loading.php",
        method: "POST",
        data: {
            secHandle_ID: getParameterByName('secHandle_ID')
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
                    <td class="text-center tableSelect"><input type="checkbox" id="student${studentCount}"></td>
                    <td>${student.fullName}</td>
                    <td>${student.age}</td>
                    <td>${student.sex}</td>
                    <td>${student.email}</td>
                    <td>${student.address}</td>
                `);
                tableBody.append(row);
                studentCount++;
            });

            toggleRemove();
        },
        error: function(error){
            console.error(error);
        }
    });
});