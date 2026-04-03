function openPaymentModal() {
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';

    // Masquer les messages d'erreur et de succès pour les DEUX formulaires
    document.getElementById('cardError').style.display = 'none';
    document.getElementById('cardSuccess').style.display = 'none';
    document.getElementById('mvolaError').style.display = 'none';
    document.getElementById('mvolaSuccess').style.display = 'none';

    // Réinitialiser les DEUX formulaires
    document.getElementById('paymentFormCard').reset();
    document.getElementById('paymentFormMvola').reset();

    // Réinitialiser la vue par défaut si nécessaire
    document.getElementById('paymentFormCard').style.display = 'block';
    document.getElementById('paymentFormMvola').style.display = 'none';
    document.getElementById('cardBtn').classList.add('active');
    document.getElementById('mvolaBtn').classList.remove('active');
}

// Gestion du basculement entre les formulaires de paiement (NOUVEAU CODE)
document.getElementById('cardBtn').addEventListener('click', function () {
    document.getElementById('paymentFormCard').style.display = 'block';
    document.getElementById('paymentFormMvola').style.display = 'none';
    this.classList.add('active');
    document.getElementById('mvolaBtn').classList.remove('active');
});

document.getElementById('mvolaBtn').addEventListener('click', function () {
    document.getElementById('paymentFormCard').style.display = 'none';
    document.getElementById('paymentFormMvola').style.display = 'block';
    this.classList.add('active');
    document.getElementById('cardBtn').classList.remove('active');
});

// Ancien code pour la validation et l'envoi du formulaire de carte bancaire
/*
document.getElementById('paymentFormCard')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
    const expiryDate = document.getElementById('expiry-date').value;
    const cvv = document.getElementById('cvv').value;
    const cardHolder = document.getElementById('card-holder').value;

    const error = document.getElementById('cardError');
    const success = document.getElementById('cardSuccess');

    // Validation simple
    const cardNumberRegex = /^\d{16}$/;
    const expiryDateRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    const cvvRegex = /^\d{3,4}$/;

    if (!cardNumberRegex.test(cardNumber) || !expiryDateRegex.test(expiryDate) || !cvvRegex.test(cvv) || !cardHolder) {
        error.textContent = 'Veuillez remplir tous les champs correctement.';
        error.style.display = 'block';
        success.style.display = 'none';
        return;
    }

    // Envoyer la requête AJAX pour enregistrer l'inscription
    fetch('/enroll/cours', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cours_id=<?php echo $cours_id; ?>&utilisateur_id=<?php echo $_SESSION['user_id']; ?>`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                error.style.display = 'none';
                success.style.display = 'block';
                success.textContent = 'Paiement effectué avec succès ! Vous pouvez maintenant accéder au contenu.';
                setTimeout(() => {
                    closePaymentModal();
                    window.location.reload();
                }, 2000);
            } else {
                error.style.display = 'block';
                error.textContent = data.message;
                success.style.display = 'none';
            }
        })
        .catch(error => {
            error.style.display = 'block';
            error.textContent = 'Erreur réseau : ' + error.message;
            success.style.display = 'none';
        });
});

// NOUVEAU CODE pour la gestion du formulaire de paiement Mvola
document.getElementById('paymentFormMvola')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const mvolaNumber = document.getElementById('mvola-number').value.trim();
    const coursId = this.querySelector('input[name="cours_id"]').value;
    const prixCours = this.querySelector('input[name="prix_cours"]').value;
    const error = document.getElementById('mvolaError');
    const success = document.getElementById('mvolaSuccess');
    console.log(mvolaNumber, coursId, prixCours)
    error.style.display = 'none';
    success.style.display = 'none';

    if (!mvolaNumber) {
        error.textContent = 'Veuillez entrer un numéro Mobile Money Mvola.';
        error.style.display = 'block';
        return;
    }

    // Envoyer les données au script PHP de traitement Mvola
    fetch('/enroll/cours', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mvola_number=${encodeURIComponent(mvolaNumber)}&cours_id=${encodeURIComponent(coursId)}&prix_cours=${encodeURIComponent(prixCours)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                success.textContent = data.message;
                success.style.display = 'block';
            } else {
                error.textContent = data.message;
                error.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
});
*/

// Ancien code pour le formatage du numéro de carte
document.getElementById('card-number')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(.{4})/g, '$1 ').trim();
    e.target.value = value;
});

// Ancien code pour le formatage de la date d'expiration
document.getElementById('expiry-date')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 3) {
        value = value.slice(0, 2) + '/' + value.slice(2);
    }
    e.target.value = value;
});

// Ancien code pour la gestion des cases à cocher pour la complétion
document.querySelectorAll('.module-completion').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const moduleId = this.dataset.moduleId;
        const coursId = this.dataset.coursId;
        const isChecked = this.checked;
        const messageElement = this.closest('.lecon').querySelector('.completion-message');
        fetch('/module/complete/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `module_id=${moduleId}&cours_id=${coursId}&is_checked=${isChecked}`
        })
            // .then(response => response.json())
            // .then(data => {
            //     messageElement.style.display = 'block';
            //     messageElement.className = 'completion-message';
            //     if (data.success) {
            //         messageElement.classList.add('success');
            //         messageElement.textContent = data.message;
            //     } else {
            //         messageElement.classList.add('error');
            //         messageElement.textContent = data.message;
            //         this.checked = !isChecked;
            //     }
            //     setTimeout(() => {
            //         messageElement.style.display = 'none';
            //     }, 3000);
            // })
            // .catch(error => {
            //     messageElement.style.display = 'block';
            //     messageElement.classList.add('error');
            //     messageElement.textContent = 'Erreur réseau : ' + error.message;
            //     this.checked = !isChecked;
            //     setTimeout(() => {
            //         messageElement.style.display = 'none';
            //     }, 3000);
            // });
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