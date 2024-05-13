import { getParameterByName } from '/dist/js/function.js';

function createAssessmentCard(assessment) {
    return `
        <div class="card w-auto rounded-3xl relative">
            <a href="/dist/profView/html/attendance.html?questionSet_ID=${assessment.questionSet_ID}&secHandle_ID=${getParameterByName('secHandle_ID')}">
                <h1 class="font-bold text-xl">${assessment.questionSetTitle}</h1>
                <h5 class="font-base text-sm mb-8">${assessment.section_ID}</h5>
                <p class="font-base text-sm">${assessment.deadlineDate}</p>
                <p class="font-base text-sm">${assessment.deadlineTime}</p>
            </a>
        </div>
    `;
}

jQuery(function() {
    // Changing href redirects to contain GET variable
    const manageLink = document.getElementById('manageLink');
    manageLink.setAttribute('href', manageLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const makeLink = document.getElementById('makeLink');
    makeLink.setAttribute('href', makeLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/questionSetList_loading.php",
        method: "POST",
        data: {
            secHandle_ID: getParameterByName('secHandle_ID')
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            const data = JSON.parse(response);

            const assessmentListSection = document.getElementById("assessmentList");

            data.forEach((assessment) => {
              const assessmentCardHTML = createAssessmentCard(assessment);
              assessmentListSection.insertAdjacentHTML("beforeend", assessmentCardHTML);
            });
        },
        error: function(error){
          console.error(error);
        }
    });
});