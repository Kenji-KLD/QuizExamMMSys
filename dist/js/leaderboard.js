import { getCookie } from '/dist/js/function.js';
import { getParameterByName } from '/dist/js/function.js';

jQuery(function () {
    let userDetails = JSON.parse(decodeURIComponent(getCookie('userDetails')));
    document.getElementById('fName').textContent = userDetails['fName'];

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

            var ranking = 0;

            document.getElementById('subjectName').textContent = data.secHandleData.subjectName;

            const leaderboard = document.getElementById('leaderboard');

            data.leaderboard.forEach(student => {
                ranking++;
                if(data.student_ID === student.student_ID) {
                    document.getElementById('rank').textContent = String(ranking).padStart(2, '0');
                    return false;
                }
            });

            data.leaderboard.forEach((student, index) => {
                const row = document.createElement('tr');

                const rank = document.createElement('td');
                rank.classList.add('text-center', 'text-ellipsis', 'overflow-hidden');
                rank.textContent = index + 1;
                
                const id = document.createElement('td');
                id.classList.add('text-center', 'text-ellipsis', 'overflow-hidden');
                student.student_ID = student.student_ID.substring(0, 10) + '**' + student.student_ID.substring(12);
                id.textContent = student.student_ID;
            
                const score = document.createElement('td');
                score.classList.add('text-center', 'text-ellipsis', 'overflow-hidden');
                score.textContent = student.averagePercentage;

                row.appendChild(rank);
                row.appendChild(id);
                row.appendChild(score);

                leaderboard.appendChild(row);
            });
        },
        error: function(error){
          console.error(error);
        }
    });
});