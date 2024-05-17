import { getParameterByName } from '/dist/js/function.js';

// Anti-cheating
let questionnaireTerminated = false;
let antiCheatFlag = true;

window.addEventListener('unload', function () {
    // Window is unloaded (user might have exited the tab)
    if(antiCheatFlag){
        terminateQuestionnaire();
    }
});

window.addEventListener('blur', function() {
    // Window lost focus (user might have switched to another application)
    if(antiCheatFlag){
        terminateQuestionnaire();
    }
});

document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden' && antiCheatFlag) {
        // Tab is now hidden (user might have switched to a different application)
        terminateQuestionnaire();
    }
});
// -------------

window.promptSubmitQuestionnaire = function () {
    showModal("Are you sure you want to submit your answers?");
};

window.submitQuestionnaire = function () {
    // Define an empty array to store the formatted data
    const answerList = [];

    // Define a variable that increments if nulls are present
    var hasNull = 0;

    // Select all <article> elements in the document
    const articles = document.querySelectorAll('article');

    // Loop through each <article> element
    articles.forEach(article => {
        // Find the <input> element with name="question_ID" within the current article
        const questionIdInput = article.querySelector('input[name="question_ID"]');
        
        // Get the value of the question_ID
        const questionId = questionIdInput.value;
        
        // Initialize a variable to store the selected answer
        let selectedAnswer = null; hasNull++;
        
        // Find all radio buttons (input[type="radio"]) within the current article
        const radioButtons = article.querySelectorAll('input[type="radio"]');
        
        // Loop through each radio button to check if it is selected
        radioButtons.forEach(radioButton => {
            if (radioButton.checked) {
                // Get the value of the selected radio button
                selectedAnswer = radioButton.value; hasNull--;
            }
        });
        
        // Create an object with the question_ID and the selected answer
        const answerObject = {
            'question_ID': questionId,
            'questionAnswer': selectedAnswer
        };
        
        // Push the object into the answerList array
        answerList.push(answerObject);
    });

    if(hasNull == 0){
        $.ajax({
            url: "/php/assessment_controller.php",
            method: "POST",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            data: {
                questionSet_ID: getParameterByName('questionSet_ID'),
                answerList: JSON.stringify(answerList)
            },
            success: function (response) {
                window.location.replace("score.html?questionSet_ID=" + getParameterByName('questionSet_ID'));
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    else{
        showWarningModal("You have not provided an answer to all questions!");
    }
};

window.terminateQuestionnaire = function () { function executeTermination() {
    // Define an empty array to store the formatted data
    const answerList = [];

    // Select all <article> elements in the document
    const articles = document.querySelectorAll('article');

    // Loop through each <article> element
    articles.forEach(article => {
        // Find the <input> element with name="question_ID" within the current article
        const questionIdInput = article.querySelector('input[name="question_ID"]');
        
        // Get the value of the question_ID
        const questionId = questionIdInput.value;
        
        // Initialize a variable to store the selected answer
        let selectedAnswer = "No Answer";
        
        // Find all radio buttons (input[type="radio"]) within the current article
        const radioButtons = article.querySelectorAll('input[type="radio"]');
        
        // Loop through each radio button to check if it is selected
        radioButtons.forEach(radioButton => {
            if (radioButton.checked) {
                // Get the value of the selected radio button
                selectedAnswer = radioButton.value;
            }
        });
        
        // Create an object with the question_ID and the selected answer
        const answerObject = {
            'question_ID': questionId,
            'questionAnswer': selectedAnswer
        };
        
        // Push the object into the answerList array
        answerList.push(answerObject);
    });

    $.ajax({
        url: "/php/assessment_controller.php",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        data: {
            questionSet_ID: getParameterByName('questionSet_ID'),
            answerList: JSON.stringify(answerList)
        },
        success: function (response) {
            window.location.replace("score.html?questionSet_ID=" + getParameterByName('questionSet_ID'));
        },
        error: function (error) {
            console.error(error);
        }
    });};

    if(!questionnaireTerminated){
        questionnaireTerminated = true;
        executeTermination();
    }
};

// Get modal element and buttons
const modal = document.getElementById('modal');
const modalText = document.getElementById('modal-text');
const modalYesBtn = document.getElementById('modal-yes');
const modalNoBtn = document.getElementById('modal-no');

// Function to show modal
function showModal(text) {
    modalText.textContent = text;
    modal.classList.remove('hidden');
}

// Function to hide modal
function hideModal() {
    modal.classList.add('hidden');
}

// Attach click event listeners
modalYesBtn.addEventListener('click', () => {
    // Handle 'Yes' button click
    hideModal();
    submitQuestionnaire();
});

modalNoBtn.addEventListener('click', () => {
    // Handle 'No' button click
    hideModal();
});

// Get warning modal elements
const warningModal = document.getElementById('warningModal');
const confirmWarningBtn = document.getElementById('confirmWarning');
const warningMessage = document.getElementById('warningMessage');

// Function to show warning modal
function showWarningModal(message) {
    warningMessage.textContent = message;
    warningModal.classList.remove('hidden');
}

// Function to hide warning modal
function hideWarningModal() {
    warningModal.classList.add('hidden');
}

// Attach click event listener to OK button
confirmWarningBtn.addEventListener('click', () => {
    // Handle OK button click (optional)
    hideWarningModal();
});

jQuery(function () {
    $.ajax({
        url: "/php/assessment_loading.php",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        data: {
            questionSet_ID: getParameterByName('questionSet_ID')
        },
        success: function (response) {
            let data = JSON.parse(response);

            document.getElementById('questionSetTitle').innerHTML = data.questionSetTitle;
            document.getElementById('rubrics').innerHTML = data.rubrics;

            // Get a reference to the container element where you want to add the articles
            const container = document.getElementById('questions');

            // Loop through each question in the JSON data
            data.questions.forEach((question, index) => {
                // Create a new article element
                const article = document.createElement('article');
                article.classList.add('flex', 'flex-col', 'gap-6', 'justify-center', 'items-center', 'my-6');

                // Create a new div element for the card
                const card = document.createElement('div');
                card.classList.add('card-test', 'w-full', 'lg:w-[75%]', 'rounded-3xl');

                // Create a new form element
                const form = document.createElement('form');
                form.action = '#';

                // Create a hidden input for question_ID
                const questionIdInput = document.createElement('input');
                questionIdInput.type = 'hidden';
                questionIdInput.name = `question_ID`;
                questionIdInput.value = question.question_ID;

                // Create a new h1 element for the question number
                const questionNumber = document.createElement('h1');
                questionNumber.classList.add('question-number', 'font-bold', 'text-xl');
                questionNumber.textContent = `Question ${index + 1}`;

                // Create a new h5 element for the question text
                const questionText = document.createElement('h5');
                questionText.classList.add('question-content', 'font-base', 'text-base', 'mb-8');
                questionText.textContent = question.questionText;

                // Append the question id, question number and question text to the form
                form.appendChild(questionIdInput);
                form.appendChild(questionNumber);
                form.appendChild(questionText);

                // Create a new div element for the answers
                const answers = document.createElement('div');
                answers.classList.add('answers');

                // Loop through each choice for the question
                question.choices.forEach((choice, choiceIndex) => {
                    // Create a new div element for the answer item
                    const answerItem = document.createElement('div');
                    answerItem.classList.add('answers-item');

                    // Create a new input element for the radio button
                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = `q${index + 1}-answers`;
                    input.id = `A${index + 1}${choiceIndex + 1}`;
                    input.value = choice;

                    // Create a new label element for the radio button
                    const label = document.createElement('label');
                    label.htmlFor = `A${index + 1}${choiceIndex + 1}`;
                    label.textContent = choice;

                    // Append the input and label elements to the answer item
                    answerItem.appendChild(input);
                    answerItem.appendChild(label);

                    // Append the answer item to the answers div
                    answers.appendChild(answerItem);
                });

                // Append the form and answers div to the card div
                card.appendChild(form);
                card.appendChild(answers);

                // Append the card div to the article element
                article.appendChild(card);

                // Append the article element to the container element
                container.appendChild(article);
            });

            const timer = setInterval(() => {
                if (data.timeLimit < 0) {
                    clearInterval(timer);
                    terminateQuestionnaire();
                } else {
                    document.getElementById('hours').textContent = String(
                        Math.floor(data.timeLimit / 3600)).padStart(2, '0'
                    );
                    document.getElementById('minutes').textContent = String(
                        Math.floor((data.timeLimit % 3600) / 60)).padStart(2, '0'
                    );
                    document.getElementById('seconds').textContent = String(
                        data.timeLimit % 60).padStart(2, '0'
                    );
                    
                    data.timeLimit--;
                }
            }, 1000);
        },
        error: function (error) {
            console.error(error);
        }
    });
});