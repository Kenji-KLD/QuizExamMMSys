import { getParameterByName } from '/dist/js/function.js';
import {} from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js';

function loadChart(passCount, failCount) {
  Chart.defaults.color = "#FEFEFE";

  const ctx = document.getElementById("myChart");

  new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Passed", "Failed"],
      datasets: [
        {
          label: "Number of Students",
          backgroundColor: ["#F9C91F7F", "#F845027F"],
          borderColor: ["#F9C91FCE", "#F84502CE"],
          data: [passCount, failCount],
          borderWidth: 1,
          hoverOffset: 5,
        },
      ],
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: "Statistics of Students", // Customize the title text here
          font: {
            size: 18, // Customize the font size of the title
          },
        },
      },
    },
  });
}

jQuery(function () {
    // Changing href redirects to contain GET variable
    const viewStatisticsLink = document.getElementById('viewStatisticsLink');
    viewStatisticsLink.setAttribute('href', viewStatisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const manageLink = document.getElementById('manageLink');
    manageLink.setAttribute('href', manageLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const makeLink = document.getElementById('makeLink');
    makeLink.setAttribute('href', makeLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const holdLink = document.getElementById('holdLink');
    holdLink.setAttribute('href', holdLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

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

            document.getElementById('questionSetTitle').textContent = data.assessmentData.questionSetTitle;
            document.getElementById('deadlineDate').textContent = data.assessmentData.deadlineDate;
            document.getElementById('deadlineTime').textContent = data.assessmentData.deadlineTime;

            document.getElementById('highestScore').textContent = data.statistics.numberData.highest_score;
            document.getElementById('lowestScore').textContent = data.statistics.numberData.lowest_score;

            if(data.statistics.questionData.questionNumber == null && data.statistics.questionData.questionText == null){
                document.getElementById('questionCard').style = "display: none";
            }
            else{
                document.getElementById('questionNumber').textContent = data.statistics.questionData.questionNumber;
                document.getElementById('questionText').textContent = data.statistics.questionData.questionText;
            } 

            loadChart(data.statistics.numberData.passCount, data.statistics.numberData.failCount);
        },
        error: function(error){
          console.error(error);
        }
    });
});