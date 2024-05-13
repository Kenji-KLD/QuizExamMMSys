import { getParameterByName } from '/dist/js/function.js';

jQuery(function () {
    $.ajax({
        url: "/php/score_loading.php",
        method: "POST",
        data: {
            questionSet_ID: getParameterByName('questionSet_ID')
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            console.log(response);
            let scoreDetails = JSON.parse(response);

            document.getElementById('questionSetTitle').textContent = scoreDetails.questionSetTitle;
            document.getElementById('score').textContent = scoreDetails.score;
            document.getElementById('total').textContent = scoreDetails.total;
        },
        error: function(error){
          console.error(error);
        }
    });
});