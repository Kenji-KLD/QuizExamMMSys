import { getParameterByName } from '/dist/js/function.js';

function createAssessmentCard(assessment) {
    return `
        <div class="card w-auto rounded-3xl relative">
          <a href="/dist/profView/html/assessment-page.html?questionSet_ID=${assessment.questionSet_ID}">
            <h1 class="font-bold text-xl">${assessment.questionSetTitle}</h1>
            <h5 class="font-base text-sm mb-8">${assessment.section_ID}</h5>
            <p class="font-base text-sm">${assessment.deadlineDate}</p>
            <p class="font-base text-sm">${assessment.deadlineTime}</p>
          </a>
          <span class="absolute top-4 right-4">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.25"
              stroke="currentColor"
              class="w-7 h-7 dots"
            >
              <!-- SVG path for dots icon -->
            </svg>
            <div class="absolute dropdown-content top-6 right-0 z-10 mt-2 w-40 origin-top-right divide-gray-100 rounded-md bg-ISwhite shadow-lg hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
              <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="#383838" class="w-4 h-4 mx-2 mb-2 mt-[0.60rem]">
                  <!-- SVG path for delete icon -->
                </svg>
                <p class="pl-0 pr-2 py-2 text-sm text-ISshade2">Delete Assessment</p>
              </div>
            </div>
          </span>
        </div>
    `;
}

jQuery(function () {
    // Changing href redirects to contain GET variable
    const studentsLink = document.getElementById('studentsLink');
    studentsLink.setAttribute('href', studentsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const makeLink = document.getElementById('makeLink');
    makeLink.setAttribute('href', makeLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const holdLink = document.getElementById('holdLink');
    holdLink.setAttribute('href', holdLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/class-page-2_loading.php",
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