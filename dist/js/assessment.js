jQuery(function () {
    $.ajax({
        url: "/php/assessment_loading.php",
        method: "POST",
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        data: {
            questionSet_ID: 1
        },
        success: function (response) {
            data = JSON.parse(response);

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
                card.classList.add('card-test', 'w-full', 'lg:w-[75%]', 'rounded-3xl');

                // Create a new form element
                const form = document.createElement('form');
                form.action = '#';

                // Create a new h1 element for the question number
                const questionNumber = document.createElement('h1');
                questionNumber.classList.add('question-number', 'font-bold', 'text-xl');
                questionNumber.textContent = `Question ${index + 1}`;

                // Create a new h5 element for the question text
                const questionText = document.createElement('h5');
                questionText.classList.add('question-content', 'font-base', 'text-base', 'mb-8');
                questionText.textContent = question.questionText;

                // Append the question number and question text to the form
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
                    input.id = `A${choiceIndex + 1}`;
                    input.value = choice;

                    // Create a new label element for the radio button
                    const label = document.createElement('label');
                    label.htmlFor = `A${choiceIndex + 1}`;
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
        },
        error: function (error) {
            console.error(error);
        }
    });
});