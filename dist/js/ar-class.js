function generateOptions(valuesArray, divId){
    const divElement = document.getElementById(divId);

    valuesArray.forEach(value => {
        const optionElement = document.createElement("option");
        optionElement.setAttribute('class', 'bg-ISshade3');
        optionElement.value = value;
        optionElement.textContent = value;
        divElement.appendChild(optionElement);
    });
}

function sendData(dataFlag, jsonData){
    console.log(jsonData);
    $.ajax({
        url: "/php/ar-class_controller.php",
        method: "POST",
        data: {
            dataFlag: dataFlag,
            jsonData: JSON.stringify(jsonData)
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            console.log(response);
            data = JSON.parse(response);

            if(data.processed == true){
                alert("Success");
                location.reload();
            }
            else{
                alert(data.error_message);
            }
        },
        error: function(error){
            console.error(error);
        }
    });
}

jQuery(function() {
   $.ajax({
        url: "/php/ar-class_loading.php",
        method: "POST",
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response){
            data = JSON.parse(response);

            [data.handledSubject].forEach(value => {
                const optionElement = document.createElement("option");
                optionElement.setAttribute('class', 'bg-ISshade3');
                optionElement.value = value.subject_ID;
                optionElement.textContent = value.subjectName;
                document.getElementById('handledSubject').appendChild(optionElement);
            });
            generateOptions(data.handledSection, 'handledSection');
            generateOptions(data.unhandledSection, 'unhandledSection');
        },
        error: function(error){
            console.error(error);
        }
   });
});