import { getParameterByName } from '/dist/js/function.js';

var originalStyle = {
    QuestionAmmtField: document.getElementById('QuestionAmmtField').style.display
}

window.createAssessment = function () {
    function getSelectedAssessmentFormat() {
        const quizRadioButton = document.getElementById('Quiz');
        const examRadioButton = document.getElementById('Exam');

        if (quizRadioButton.checked) {
            return quizRadioButton.value;
        } 
        else if (examRadioButton.checked) {
            return examRadioButton.value;
        }
        else {
            return null;
        }
    }

    function getRandomizeValue() {
        const randomizeCheckbox = document.getElementById('isRandomized');

        if (randomizeCheckbox.checked){
            return document.getElementById('QuestionAmmt').value == "" ? 0 : parseInt(document.getElementById('QuestionAmmt').value);
        }
        else{
            return null;
        }
    }
    
    const questionSetData = JSON.stringify({
        questionSetTitle: document.getElementById('Title').value,
        questionSetType: getSelectedAssessmentFormat(),
        randomCount: getRandomizeValue(),
        deadline: document.getElementById('Deadline').value.replace('T', ' '),
        timeLimit: parseInt(document.getElementById('TimeDuration').value),
        acadYear: document.getElementById('AcademicYear').value,
        acadTerm: document.getElementById('AcademicTerm').value,
        acadSem: document.getElementById('AcademicSemester').value
    });

    // Get the selected CSV file
    let csvFile = document.getElementById('file-upload').files[0];

    // Create FormData object
    let formData = new FormData();
    formData.append('questionData', csvFile);  // Append the CSV file to FormData

    // Additional data to send
    let additionalData = {
        secHandle_ID: getParameterByName('secHandle_ID'),
        questionSetData: questionSetData
    };

    // Append additional data to FormData
    Object.keys(additionalData).forEach(key => {
        formData.append(key, additionalData[key]);
    });

    $.ajax({
        url: "/php/make-assessment_controller.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            console.log(response);
            let data = JSON.parse(response);

            if(data.processed == true){
                alert("Success!");
                location.reload();
            }
        },
        error: function(error) {
            console.error(error);
        }
    });
}

window.toggleQuestionAmmtField = function () {
    const QuestionAmmtField = document.getElementById('QuestionAmmtField');

    QuestionAmmtField.style.display = (QuestionAmmtField.style.display !== 'none') ? 'none' : originalStyle['QuestionAmmtField'];
}

jQuery(function() {
    // Changing href redirects to contain GET variable
    const manageLink = document.getElementById('manageLink');
    manageLink.setAttribute('href', manageLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const holdLink = document.getElementById('holdLink');
    holdLink.setAttribute('href', holdLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    // Hides Question Amount Field by Default
    document.getElementById('QuestionAmmtField').style.display = 'none';
});