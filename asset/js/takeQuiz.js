// Gestion des questions une par une
const questions = document.querySelectorAll('.question');
const nextButton = document.getElementById('nextButton');
const submitButton = document.getElementById('submitButton');
const resultMessage = document.getElementById('resultMessage');
let currentQuestionIndex = 0;
const answers = [];

if (questions.length > 0) {
    // Afficher la première question
    questions[0].classList.add('active');

    questions.forEach((question, index) => {
        const radios = question.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                answers[index] = radio.value;
                nextButton.disabled = false;
            });
        });
    });

    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex < questions.length - 1) {
            questions[currentQuestionIndex].classList.remove('active');
            currentQuestionIndex++;
            questions[currentQuestionIndex].classList.add('active');
            nextButton.disabled = !answers[currentQuestionIndex];
            gsap.from(questions[currentQuestionIndex], {
                opacity: 0,
                x: 50,
                duration: 0.5,
                ease: "power2.out"
            });

            if (currentQuestionIndex === questions.length - 1) {
                nextButton.style.display = 'none';
                submitButton.style.display = 'inline-block';
            }
        }
    });

    submitButton.addEventListener('click', () => {
        if (answers.length < questions.length) {
            alert('Veuillez répondre à toutes les questions avant de soumettre.');
            return;
        }

        // Envoyer les réponses via AJAX
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `ajax_submission=1&answers=${encodeURIComponent(JSON.stringify(answers))}`
        })
            .then(response => response.json())
            .then(data => {
                resultMessage.style.display = 'block';
                resultMessage.className = 'result-message';
                resultMessage.classList.add(data.success ? 'success' : 'error');
                resultMessage.textContent = data.message;
                submitButton.style.display = 'none';
                nextButton.style.display = 'none';
            })
            .catch(error => {
                resultMessage.style.display = 'block';
                resultMessage.className = 'result-message error';
                resultMessage.textContent = 'Erreur réseau : ' + error.message;
            });
    });
}

// Animation GSAP
gsap.from(".quiz-section h1", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
gsap.from(".question.active", {
    opacity: 0,
    x: 50,
    duration: 0.8,
    ease: "power2.out",
    scrollTrigger: {
        trigger: ".question.active",
        start: "top 80%",
    }
});
gsap.from(".btn-next, .btn-submit, .btn-back", {
    opacity: 0,
    scale: 0.8,
    duration: 0.5,
    stagger: 0.1,
    ease: "back.out(1.7)",
    delay: 0.5,
    onComplete: () => {
        gsap.to(".btn-next, .btn-submit, .btn-back", {
            scale: 1.1,
            duration: 0.2,
            repeat: -1,
            yoyo: true,
            ease: "power1.inOut",
            paused: true,
            onStart: function () { this.targets().forEach(btn => btn.addEventListener('mouseenter', () => this.play())) },
            onComplete: function () { this.targets().forEach(btn => btn.addEventListener('mouseleave', () => this.pause())) }
        });
    }
});