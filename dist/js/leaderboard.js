import { getParameterByName } from '/dist/js/function.js';

jQuery(function () {
    const assessmentLinks = Array.from(document.getElementsByClassName('assessmentLink'));
    assessmentLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/subject.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });
    const peopleLinks = Array.from(document.getElementsByClassName('peopleLink'));
    peopleLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/people.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });

    $.ajax({
        url: "/php/leaderboard_loading.php",
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
        },
        error: function(error){
          console.error(error);
        }
    });
});