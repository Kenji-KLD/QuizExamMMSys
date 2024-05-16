import { getParameterByName } from '/dist/js/function.js';

jQuery(function () {
    const assessmentLinks = Array.from(document.getElementsByClassName('assessmentLink'));
    assessmentLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/subject.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });
    const leaderboardLinks = Array.from(document.getElementsByClassName('leaderboardLink'));
    leaderboardLinks.forEach(anchor => {
        anchor.setAttribute('href', "/dist/userView/html/leaderboard.html?secHandle_ID=" + getParameterByName('secHandle_ID'));
    });

    $.ajax({
        url: "/php/people_loading.php",
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

            const container = document.getElementById('classList');

            let profFullName = data.profName.lName + ', ' + data.profName.fName;
                if (data.profName.mName) {
                    profFullName += ' ' + data.profName.mName.charAt(0) + '.';
            }

            document.getElementById('profName').textContent = profFullName;

            data.studentList.forEach(student => {
                const studentName = document.createElement('p');
                studentName.classList.add('ml-12', 'my-4', 'text-base', 'font-normal');
                studentName.textContent = student.fullName

                container.appendChild(studentName);
            });
        },
        error: function(error){
          console.error(error);
        }
    });
});