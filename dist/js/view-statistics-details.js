import { getParameterByName } from '/dist/js/function.js';

export function getRemarks() {
    return [document.getElementById('passCount').value, document.getElementById('failCount').value];
  }

jQuery(function () {
    // Changing href redirects to contain GET variable
    const viewStatisticsLink = document.getElementById('viewStatisticsLink');
    viewStatisticsLink.setAttribute('href', viewStatisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/view-statistics-details_loading.php",
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

            const scoreData = document.getElementById('scoreData');

            document.getElementById('questionSetTitle').textContent = data.assessmentData.questionSetTitle;
            document.getElementById('deadlineDate').textContent = data.assessmentData.deadlineDate;
            document.getElementById('deadlineTime').textContent = data.assessmentData.deadlineTime;

            document.getElementById('highestScore').textContent = data.statistics.numberData.highest_score;
            document.getElementById('lowestScore').textContent = data.statistics.numberData.lowest_score;

            document.getElementById('questionNumber').textContent = data.statistics.questionData.questionNumber;
            document.getElementById('questionText').textContent = data.statistics.questionData.questionText;

            const passCount = document.createElement('input');
            passCount.id = 'passCount';
            passCount.type = 'hidden';
            passCount.value = data.statistics.numberData.passCount;

            const failCount = document.createElement('input');
            failCount.id = 'failCount';
            failCount.type = 'hidden';
            failCount.value = data.statistics.numberData.failCount;

            scoreData.appendChild(passCount);
            scoreData.appendChild(failCount);
        },
        error: function(error){
          console.error(error);
        }
    });
});