import { getParameterByName } from '/dist/js/function.js';

let presentCounter = 0; let absentCounter = 0;

function resetCounter() {
    presentCounter = 0; absentCounter = 0;
}

function displayCounter() {
    document.getElementById('presentCounter').textContent = presentCounter.toString();
    document.getElementById('absentCounter').textContent = absentCounter.toString();
}

window.updateCounter = function () {
    const checkboxes = document.querySelectorAll('#studentList input[type="checkbox"]');
    resetCounter();

    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            presentCounter++;
        } 
        else {
            absentCounter++;
        }
        displayCounter();
    });
}

window.holdAssessment = function () {
    if(confirm("Are you sure you want to hold this assessment?")){
        const studentList = document.getElementById('studentList');
        const rows = studentList.querySelectorAll('tr');
        const attendanceData = [];

        rows.forEach((row) => {
            const checkbox = row.querySelector('input[type="checkbox"]');

            if (checkbox) {
                const value = checkbox.value;
                const isChecked = checkbox.checked;

                attendanceData.push({
                    student_ID: value,
                    isDisallowed: !isChecked
                });
            }
        });

        $.ajax({
            url: "/php/attendance_controller.php",
            method: "POST",
            data: {
                attendanceData: JSON.stringify(attendanceData),
                questionSet_ID: getParameterByName('questionSet_ID')
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                let data = JSON.parse(response);

                if(data.processed == true){
                    alert("Success!");
                }
            },
            error: function(error){
              console.error(error);
            }
        });
    }
}

jQuery(function () {
    // Changing href redirects to contain GET variable
    const manageLink = document.getElementById('manageLink');
    manageLink.setAttribute('href', manageLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const makeLink = document.getElementById('makeLink');
    makeLink.setAttribute('href', makeLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const backLink = document.getElementById('backLink');
    backLink.setAttribute('href', backLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/attendance_loading.php",
        method: "POST",
        data: {
            questionSet_ID: getParameterByName('questionSet_ID'),
            secHandle_ID: getParameterByName('secHandle_ID')
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            let data = JSON.parse(response);

            document.getElementById('attendance').style.display = '';
            document.getElementById('questionSetTitle').textContent = data.assessmentData.questionSetTitle;
            document.getElementById('section_ID').textContent = data.assessmentData.section_ID;
            document.getElementById('deadlineDate').textContent = data.assessmentData.deadlineDate;
            document.getElementById('deadlineTime').textContent = data.assessmentData.deadlineTime;

            data.sectionList.forEach(student => {
                const tr = document.createElement('tr');
            
                // Create the checkbox cell
                const tdCheckbox = document.createElement('td');
                tdCheckbox.className = 'text-center';
            
                // Create the checkbox input element
                const checkbox = document.createElement('input');
                checkbox.onclick = updateCounter();
                checkbox.type = 'checkbox';
                checkbox.className = 'size-4';
                checkbox.value = student.student_ID;
            
                // Check the status of disallowance based on student_ID match
                const disallowedStatus = data.setDisallowData.find(status => status.student_ID === student.student_ID);
                if (disallowedStatus) {
                    checkbox.checked = disallowedStatus.isDisallowed !== 1; // Set checkbox unchecked if disallowed
                } else {
                    checkbox.checked = false; // Default to unchecked if no status found
                }
            
                // Add an onclick event listener to handle checkbox change
                checkbox.onclick = updateCounter;
            
                // Append the checkbox to the checkbox cell
                tdCheckbox.appendChild(checkbox);
            
                // Append the checkbox cell to the table row
                tr.appendChild(tdCheckbox);
            
                // Create and append the cell for student name
                const tdName = document.createElement('td');
                tdName.innerHTML = student.fullName;
                tr.appendChild(tdName);
            
                // Append the table row to the table body
                document.querySelector('tbody').appendChild(tr);
            });

            updateCounter();
        },
        error: function(error){
          console.error(error);
        }
    });
});