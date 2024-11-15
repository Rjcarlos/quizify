document.addEventListener('DOMContentLoaded', () => {
    fetchQuizzes();
});
function fetchQuizzes() {
    fetch('quiz1.php')  // Ensure the path to quiz.php is correct
        .then(response => {
            if (!response.ok) {
                alert('Network response was not ok');
            }
            return response.json();
        })
        .then(quizzes => {
            const container = document.getElementById('quizzes-container');
            container.innerHTML = '';  // Clear any existing content

            if (quizzes.length === 0) {
                container.innerHTML = '<p>No quizzes available.</p>';
                alert("no quizzes bro")
                return;
            }

            quizzes.forEach(quiz => {
                const quizCard = document.createElement('div');
                quizCard.classList.add('quiz-card');
                quizCard.innerHTML = `
                    <h3>${quiz.quiz_title}</h3>
                    <p>${quiz.description}</p>
                    <button onclick="startQuiz(${quiz.id})">Take Quiz</button>
                `;
                container.appendChild(quizCard);
            });
        })

}

function startQuiz(quizId) {
    fetch(`/quiz/${quizId}`)
        .then(response => response.json())
        .then(quiz => {
            document.getElementById('quiz-list').style.display = 'none';
            document.getElementById('quiz-taking').style.display = 'block';

            const quizContent = document.getElementById('quiz-content');
            quizContent.innerHTML = ''; // Clear previous content

            quiz.questions.forEach((question, index) => {
                const questionBlock = document.createElement('div');
                questionBlock.classList.add('question-block');
                questionBlock.innerHTML = `
                    <h4>${index + 1}. ${question.text}</h4>
                    ${question.options.map((option, i) => `
                        <div>
                            <input type="radio" name="question-${question.id}" value="${i + 1}" id="option-${question.id}-${i + 1}">
                            <label for="option-${question.id}-${i + 1}">${option}</label>
                        </div>
                    `).join('')}
                `;
                quizContent.appendChild(questionBlock);
            });

            document.getElementById('submit-quiz').setAttribute('data-quiz-id', quizId);
        })
        .catch(error => console.error('Error:', error));
}

function submitQuiz() {
    const quizId = document.getElementById('submit-quiz').getAttribute('data-quiz-id');
    const answers = [];

    document.querySelectorAll('.question-block').forEach(block => {
        const questionId = block.querySelector('input[type="radio"]').name.split('-')[1];
        const selectedOption = block.querySelector('input[type="radio"]:checked');
        if (selectedOption) {
            answers.push({
                questionId: questionId,
                selectedOption: selectedOption.value
            });
        }
    });

    fetch(`/quiz/${quizId}/submit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ answers })
    })
    .then(response => response.json())
    .then(result => {
        alert(`You scored ${result.score} out of ${result.totalQuestions}`);
        document.getElementById('quiz-list').style.display = 'block';
        document.getElementById('quiz-taking').style.display = 'none';
    })
    .catch(error => console.error('Error:', error));
}
