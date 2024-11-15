

let questionCount = 0;

function addQuestion() {
    questionCount++;

    const questionContainer = document.createElement('div');
    questionContainer.classList.add('question');

    questionContainer.innerHTML = `
        <div class = "question">
        <h3>Question ${questionCount}</h3>
        <label for="question-${questionCount}">Question:</label>
        <textarea type="text" id="question-${questionCount}" name="questions[${questionCount}][text]" rows= "3" cols = "100" required> </textarea>
        </div>
        
        <div class="choices">
            <label>Choices:</label> 
            <label id="choiceNumber">1.</label><textarea name="questions[${questionCount}][choices][]" rows = "2" cols = "100" required> </textarea>
            <label id="choiceNumber">2.</label><textarea name="questions[${questionCount}][choices][]" rows = "2" cols = "100" required> </textarea>
            <label id="choiceNumber">3.</label><textarea name="questions[${questionCount}][choices][]" rows = "2" cols = "100" required> </textarea>
            <label id="choiceNumber">4.</label><textarea name="questions[${questionCount}][choices][]" rows = "2" cols = "100" required> </textarea>
        </div>

        <label for="correct-choice-${questionCount}">Correct Choice (1-4):</label>
        <input type="number" id="correct-choice-${questionCount}" name="questions[${questionCount}][correct]" min="1" max="4" required>
    `;

    document.getElementById('questions-container').appendChild(questionContainer);

}



/* 
function submitQuiz(event) {
    event.preventDefault();

    const confirmation = confirm("Are you sure you want to create this quiz?");
    if (!confirmation) {
        return;
    }

    const form = event.target;
    const data = new FormData(form);

    fetch('submit-quiz.php', {
        "method": 'POST',
        "body": data
    })
    .then(response => response.text())
    .then(result => {
        //alert(result);
        form.reset();
        document.getElementById('questions-container').innerHTML = ''; // Clear questions
    })
    .catch(error => console.error('Error:', error));
} */