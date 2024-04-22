const editButton = document.querySelector(".edit-button");
const questionNumber = document.querySelector(".question-number");
const questionContent = document.querySelector(".question-content");
const labels = document.querySelectorAll(".answers label");
const icons = document.querySelector(".icons");
const checkIcon = document.querySelector(".check-icon");
const xIcon = document.querySelector(".x-icon");
const radioButtons = document.querySelectorAll(".answers input[type='radio']");

let editMode = false;

editButton.addEventListener("click", () => {
  editMode = !editMode;
  editButton.style.display = "none";
  icons.style.display = editMode ? "flex" : "none";
  questionNumber.contentEditable = editMode;
  questionContent.contentEditable = editMode;
  labels.forEach((label) => {
    label.contentEditable = editMode;
  });
  radioButtons.forEach((radioButton) => {
    radioButton.disabled = editMode;
    radioButton.checked = false;
  });
});

checkIcon.addEventListener("click", () => {
  if (editMode) {
    // Save the changes
    const updatedQuestionNumber = questionNumber.textContent;
    const updatedQuestionContent = questionContent.textContent;
    const updatedAnswerLabels = Array.from(labels).map(
      (label) => label.textContent
    );

    // Do something with the updated values
    console.log("Updated Question Number:", updatedQuestionNumber);
    console.log("Updated Question Content:", updatedQuestionContent);
    console.log("Updated Answer Labels:", updatedAnswerLabels);

    // Revert to original values
    questionNumber.contentEditable = !editMode;
    questionContent.contentEditable = !editMode;
    labels.forEach((label) => {
      label.contentEditable = !editMode;
    });

    radioButtons.forEach((radioButton) => {
      radioButton.disabled = false;
      radioButton.checked = false;
    });

    editButton.style.display = "block";
    icons.style.display = "none";
    editMode = false;
  }
});

xIcon.addEventListener("click", () => {
  if (editMode) {
    // Cancel edit mode without saving changes
    questionNumber.contentEditable = !editMode;
    questionContent.contentEditable = !editMode;
    labels.forEach((label) => {
      label.contentEditable = !editMode;
    });
    radioButtons.forEach((radioButton) => {
      radioButton.disabled = false;
      radioButton.checked = false;
    });

    editButton.style.display = "block";
    icons.style.display = "none";
    editMode = false;
  }
});
