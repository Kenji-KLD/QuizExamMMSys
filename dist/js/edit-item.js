function updateQuestion(questionData) {
  $.ajax({
    url: "/php/assessment-page_controller.php",
    method: "POST",
    data: JSON.stringify(questionData),
    beforeSend: function(xhr) {
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    },
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.error(error);
    }
  });
}

export function loadEdit() {
  const articles = document.querySelectorAll("article");

  articles.forEach((article) => {
    const editButton = article.querySelector(".edit-button");
    const questionContent = article.querySelector(".question-content");
    const labels = article.querySelectorAll(".answers label");
    const icons = article.querySelector(".icons");
    const checkIcon = article.querySelector(".check-icon");
    const xIcon = article.querySelector(".x-icon");
    const radios = article.querySelectorAll(".answers input[type='radio']");
    const questionIDInput = article.querySelector("input[name='question_ID']");
    const choiceIDInput = article.querySelectorAll("input[name='choice_ID']");

    let initialContent = {}; // Store initial content states
    let radioStates = {}; // Store initial radio button states
    let editMode = false;

    // Capture initial radio button states
    radios.forEach((radio) => {
      radioStates[radio.id] = radio.checked || false; // Store initial checked state
    });

    editButton.addEventListener("click", () => {  
      editMode = !editMode;
      editButton.style.display = "none";
      icons.style.display = editMode ? "flex" : "none";
      questionContent.contentEditable = editMode;
      labels.forEach((label) => {
        label.contentEditable = editMode;
      });

      // Enable or disable radio buttons based on editMode
      radios.forEach((radio) => {
        radio.disabled = !editMode;
      });

      if (editMode) {
        // Save initial content
        initialContent.questionContent = questionContent.textContent;
        labels.forEach((label) => {
          initialContent[label.getAttribute("for")] = label.textContent;
        });
      }
    });

    checkIcon.addEventListener("click", () => {
      if (editMode) {
        // Save the changes
        const questionText = questionContent.textContent;
        const choiceIDs = Array.from(choiceIDInput).mal((choice_ID) => choice_ID.value);
        const choiceLabel = Array.from(labels).map((label) => label.textContent);
        const choice = choiceIDs.map((choice_ID, index) => [choice_ID, choiceLabel[index]]);

        // Find the selected radio button value
        let selectedRadioValue = null;
        radios.forEach((radio) => {
          if (radio.checked) {
            selectedRadioValue = radio.value;
          }
        });

        // Update the stored initial content
        initialContent.questionContent = questionText;
        labels.forEach((label) => {
          const labelId = label.getAttribute("for");
          initialContent[labelId] = label.textContent;
        });

        // Update radio button selections
        radios.forEach((radio) => {
          radioStates[radio.id] = radio.checked;
        });

        // Sending data to the database
        updateQuestion({
          question_ID: questionIDInput.value,
          questionText: questionText,
          questionAnswer: selectedRadioValue,
          choice: choice
        });

        // Disable radio buttons after saving changes
        radios.forEach((radio) => {
          radio.disabled = true;
        });

        // Exit edit mode
        questionContent.contentEditable = false;
        labels.forEach((label) => {
          label.contentEditable = false;
        });

        editButton.style.display = "block";
        icons.style.display = "none";
        editMode = false;
      }
    });

    xIcon.addEventListener("click", () => {
      if (editMode) {
        // Revert content to original when cancelling
        questionContent.textContent = initialContent.questionContent;
        labels.forEach((label) => {
          const labelId = label.getAttribute("for");
          label.textContent = initialContent[labelId];
        });

        // Restore radio button selections
        radios.forEach((radio) => {
          radio.checked = radioStates[radio.id] || false;
        });

        // Disable radio buttons after canceling
        radios.forEach((radio) => {
          radio.disabled = true;
        });

        // Exit edit mode
        questionContent.contentEditable = false;
        labels.forEach((label) => {
          label.contentEditable = false;
        });

        editButton.style.display = "block";
        icons.style.display = "none";
        editMode = false;
      }
    });
  });
}