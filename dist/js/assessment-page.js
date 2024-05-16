import { getParameterByName } from '/dist/js/function.js';
import { loadEdit } from '/dist/js/edit-item.js';

jQuery(function () {
    // Changing href redirects to contain GET variable
    const editAssessmentLink = document.getElementById('editAssessmentLink');
    editAssessmentLink.setAttribute('href', editAssessmentLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));

    $.ajax({
        url: "/php/assessment-page_loading.php",
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
                article.classList.add('flex', 'flex-col', 'gap-6', 'justify-center', 'items-center');

                // Create a new div element for the card
                const card = document.createElement('div');
                card.classList.add('card-test', 'w-[75%]', 'rounded-3xl');

                // Create a new form element
                const form = document.createElement('form');
                form.action = '#';

                // Create the button
                const Button = document.createElement('button');
                Button.type = 'button';
                Button.classList.add('edit-button', 'flex', 'cursor-pointer', 'bg-gradient-to-br', 'from-[#4862FFCE]', 'to-[#11CBFECE]', 'py-1', 'px-4', 'rounded-[2.5rem]', 'mb-4');

                // Create the inner paragraph for the button
                const buttonParagraph = document.createElement('p');
                buttonParagraph.classList.add('text-base', 'mx-auto', 'my-auto', 'font-medium');
                buttonParagraph.textContent = 'Edit';

                // Append paragraph to the button
                Button.appendChild(buttonParagraph);

                // Create the div for icons
                const iconsDiv = document.createElement('div');
                iconsDiv.classList.add('icons', 'hidden');

                // Create the flex container for icons
                const flexDiv = document.createElement('div');
                flexDiv.classList.add('flex', 'gap-1', 'mb-4');

                // Create the check icon
                const checkIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                checkIcon.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                checkIcon.setAttribute('viewBox', '0 0 24 24');
                checkIcon.setAttribute('fill', 'currentColor');
                checkIcon.classList.add('w-8', 'h-8', 'check-icon');
                checkIcon.innerHTML = `
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                `;

                // Create the x icon
                const xIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                xIcon.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                xIcon.setAttribute('fill', 'none');
                xIcon.setAttribute('viewBox', '0 0 24 24');
                xIcon.setAttribute('stroke-width', '1.25');
                xIcon.setAttribute('stroke', 'currentColor');
                xIcon.classList.add('w-8', 'h-8', 'x-icon');
                xIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                `;

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

                // Append icons to the flex container
                flexDiv.appendChild(checkIcon);
                flexDiv.appendChild(xIcon);

                // Append the flex container to the icons div
                iconsDiv.appendChild(flexDiv);

                // Append the button and icons div to the form
                form.appendChild(Button);
                form.appendChild(iconsDiv);

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

                    // Create a hidden input for choice_ID
                    const choiceIdInput = document.createElement('input');
                    choiceIdInput.type = 'hidden';
                    choiceIdInput.name = `choice_ID`;
                    choiceIdInput.value = choice.choice_ID;

                    // Create a new input element for the radio button
                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = `q${index + 1}-answers`;
                    input.id = `A${index + 1}${choiceIndex + 1}`;
                    input.value = choice.choiceLabel;
                    input.checked = question.questionAnswer == choice.choiceLabel ? true : false;
                    input.disabled = true;

                    // Create a new label element for the radio button
                    const label = document.createElement('label');
                    label.htmlFor = `A${index + 1}${choiceIndex + 1}`;
                    label.textContent = choice.choiceLabel;

                    // Append the input and label elements to the answer item
                    answerItem.appendChild(choiceIdInput);
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

            loadEdit();
        },
        error: function (error) {
            console.error(error);
        }
    });
});