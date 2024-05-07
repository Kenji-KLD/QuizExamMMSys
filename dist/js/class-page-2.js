function createAssessmentCard(assessment) {
    return `
        <div class="card w-auto rounded-3xl relative">
          <a href="/dist/profView/html/assessment-page.html">
            <h1 class="font-bold text-xl">${assessment.questionSetTitle}</h1>
            <h5 class="font-base text-sm mb-8">${assessment.section_ID}</h5>
            <p class="font-base text-sm">${assessment.date}</p>
            <p class="font-base text-sm">${assessment.time}</p>
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
    
});