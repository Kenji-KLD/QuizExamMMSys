import { getParameterByName } from '/dist/js/function.js';

window.takeAssessment = function (href) {
    if(confirm("WARNING: You cannot exit or tab out once you have started this assessment. Failure to comply will result in automatic termination of your answers.\nAre you absolutely sure you want to continue?")){
        window.open(href, '_blank', 'noopener,noreferrer');
    }
}

function createAssessmentCard(isAllowed, questionSetData) {
    const cardType = isAllowed == true ? 'card-user1' : 'card-user2';

    const assessmentDiv = document.createElement('div');
    assessmentDiv.classList.add(cardType, 'w-auto', 'rounded-xl');
    
    const questionSetTitle = document.createElement('h1');
    questionSetTitle.classList.add('font-bold', 'text-xl');
    questionSetTitle.textContent = questionSetData.questionSetTitle;

    const score = document.createElement('h5');
    score.classList.add('font-base', 'text-sm', 'mb-8');
    score.textContent = (isAllowed ? '-' : questionSetData.score) + '/' + questionSetData.total;

    const date = document.createElement('p');
    date.classList.add('font-base', 'text-sm');
    date.textContent = questionSetData.date;

    const time = document.createElement('p');
    time.classList.add('font-base', 'text-sm');
    time.textContent = questionSetData.time;

    if(isAllowed == true){
        const anchor = document.createElement('a');
        const href = `/dist/userView/html/assessment.html?questionSet_ID=${questionSetData.questionSet_ID}`
        anchor.setAttribute('onclick', `takeAssessment('${href}')`);

        anchor.appendChild(questionSetTitle);
        anchor.appendChild(score);
        anchor.appendChild(date);
        anchor.appendChild(time);
        assessmentDiv.appendChild(anchor);
    }
    else{
        assessmentDiv.appendChild(questionSetTitle);
        assessmentDiv.appendChild(score);
        assessmentDiv.appendChild(date);
        assessmentDiv.appendChild(time);
    }

    return assessmentDiv;
}

jQuery(function () {
    const peopleLinks = Array.from(document.getElementsByClassName('peopleLink'));
    peopleLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/people.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });
    const leaderboardLinks = Array.from(document.getElementsByClassName('leaderboardLink'));
    leaderboardLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/leaderboard.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });

    $.ajax({
        url: "/php/subject_loading.php",
        method: "POST",
        data: {
            secHandle_ID: getParameterByName('secHandle_ID')
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            let data = JSON.parse(response);

            document.getElementById('subjectName').textContent = data.secHandleData.subjectName;

            const assessmentList = document.getElementById('assessmentList');

            // Generates view cards of answerable assessments
            if(data.assessmentAllowed) {
                data.assessmentAllowed.forEach(assessmentArray => {
                    assessmentArray.forEach(assessment => {
                        const assessmentCard = createAssessmentCard(true, assessment);
                        assessmentList.appendChild(assessmentCard);
                    });
                });
            }
            
            // Generates view cards of answered assessments
            if(data.assessmentScored) {
                data.assessmentScored.forEach(assessmentArray => {
                    assessmentArray.forEach(assessment => {
                        const assessmentCard = createAssessmentCard(false, assessment);
                        assessmentList.appendChild(assessmentCard);
                    });
                });
            }
        },
        error: function(error){
          console.error(error);
        }
    });
});