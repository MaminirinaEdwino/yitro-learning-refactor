function openPaymentModal() {
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.getElementById('paymentError').style.display = 'none';
    document.getElementById('paymentSuccess').style.display = 'none';
    document.getElementById('paymentForm').reset();
}

// Gestion du formulaire de paiement
/*
document.getElementById('paymentForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
    const expiryDate = document.getElementById('expiry-date').value;
    const cvv = document.getElementById('cvv').value;
    const cardHolder = document.getElementById('card-holder').value.trim();

    const error = document.getElementById('paymentError');
    const success = document.getElementById('paymentSuccess');

    // Réinitialisation des messages
    error.style.display = 'none';
    success.style.display = 'none';

    // Validation des champs de paiement
    const cardNumberRegex = /^\d{16}$/;
    const expiryDateRegex = /^(0[1-9]|1[0-2])\/(20)?\d{2}$/; // Nouvelle regex pour la date
    const cvvRegex = /^\d{3,4}$/;

    if (cardNumber === '') {
        error.textContent = 'Le numéro de carte ne peut pas être vide.';
        error.style.display = 'block';
        return;
    }
    if (!cardNumberRegex.test(cardNumber)) {
        error.textContent = 'Le numéro de carte est invalide. Il doit contenir 16 chiffres.';
        error.style.display = 'block';
        return;
    }

    if (expiryDate === '') {
        error.textContent = 'La date d\'expiration ne peut pas être vide.';
        error.style.display = 'block';
        return;
    }
    if (!expiryDateRegex.test(expiryDate)) {
        error.textContent = 'La date d\'expiration est invalide. Utilisez le format MM/AA ou MM/AAAA.';
        error.style.display = 'block';
        return;
    }

    if (cvv === '') {
        error.textContent = 'Le CVV ne peut pas être vide.';
        error.style.display = 'block';
        return;
    }
    if (!cvvRegex.test(cvv)) {
        error.textContent = 'Le CVV est invalide. Il doit contenir 3 ou 4 chiffres.';
        error.style.display = 'block';
        return;
    }

    if (cardHolder === '') {
        error.textContent = 'Le nom du titulaire ne peut pas être vide.';
        error.style.display = 'block';
        return;
    }

    fetch('/enroll/cours', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cours_id=<?php echo $cours_id; ?>`
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau ou réponse serveur incorrecte');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                error.style.display = 'none';
                success.style.display = 'block';
                setTimeout(() => {
                    closePaymentModal();
                    window.location.reload();
                }, 2000);
            } else {
                error.textContent = data.message || 'Une erreur est survenue lors de l\'inscription.';
                error.style.display = 'block';
                success.style.display = 'none';
            }
        })
        .catch(err => {
            console.error('Erreur lors de la requête:', err);
            const errorMessage = document.getElementById('paymentError');
            errorMessage.textContent = 'Une erreur de connexion est survenue. Veuillez réessayer.';
            errorMessage.style.display = 'block';
        });
});*/

// Formatage du numéro de carte
document.getElementById('card-number')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(.{4})/g, '$1 ').trim();
    e.target.value = value;
});

// Formatage de la date d'expiration
document.getElementById('expiry-date')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 3) {
        value = value.slice(0, 2) + '/' + value.slice(2);
    }
    e.target.value = value;
});

// Gestion des cases à cocher pour la complétion des modules
document.querySelectorAll('.module-completion').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const moduleId = this.dataset.moduleId;
        const coursId = this.dataset.coursId;
        const isChecked = this.checked;
        const messageElement = this.closest('.lecon').querySelector('.completion-message');

        fetch('complete_module.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `module_id=${moduleId}&cours_id=${coursId}&is_checked=${isChecked}`
        })
            .then(response => response.json())
            .then(data => {
                messageElement.style.display = 'block';
                messageElement.className = 'completion-message';
                if (data.success) {
                    messageElement.classList.add('success');
                    messageElement.textContent = data.message;
                } else {
                    messageElement.classList.add('error');
                    messageElement.textContent = data.message;
                    this.checked = !isChecked;
                }
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 3000);
            })
            .catch(error => {
                messageElement.style.display = 'block';
                messageElement.classList.add('error');
                messageElement.textContent = 'Erreur réseau : ' + error.message;
                this.checked = !isChecked;
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 3000);
            });
    });
});

// Animation GSAP pour les quiz
document.querySelectorAll('.quiz').forEach(quiz => {
    gsap.from(quiz, {
        opacity: 0,
        y: 20,
        duration: 0.5,
        ease: "power2.out",
        scrollTrigger: {
            trigger: quiz,
            start: "top 80%",
        }
    });
});