const canvas = document.getElementById('hero-animation');
const ctx = canvas.getContext('2d');

canvas.width = window.innerWidth;
canvas.height = canvas.parentElement.offsetHeight;

let particlesArray = [];

class Particle {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 2 + 1;
        this.speedX = Math.random() * 1 - 0.5;
        this.speedY = Math.random() * 1 - 0.5;
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.x < 0 || this.x > canvas.width) this.speedX = -this.speedX;
        if (this.y < 0 || this.y > canvas.height) this.speedY = -this.speedY;
    }
    draw() {
        ctx.fillStyle = 'rgba(255,255,255,0.8)';
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
    }
}

function init() {
    particlesArray = [];
    for (let i = 0; i < 100; i++) {
        particlesArray.push(new Particle());
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < particlesArray.length; i++) {
        particlesArray[i].update();
        particlesArray[i].draw();
    }
    requestAnimationFrame(animate);
}

init();
animate();

window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = canvas.parentElement.offsetHeight;
    init();
});

function loadSousFormations(formationId) {
    const contenuSelect = document.getElementById('subtheme_filter');
    contenuSelect.innerHTML = '<option value="">Chargement...</option>';

    if (!formationId) {
        contenuSelect.innerHTML = '<option value="">Tous les Sous-Thèmes</option>';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../Espace/formateur/get_sous_formations.php?formation_id=' + formationId, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const sousFormations = JSON.parse(xhr.responseText);
                let optionsHtml = '<option value="">Tous les Sous-Thèmes</option>';

                if (Array.isArray(sousFormations) && sousFormations.length > 0) {
                    sousFormations.forEach(sf => {
                        optionsHtml += `<option value="${sf.id_contenu}">${sf.sous_formation}</option>`;
                    });
                }
                contenuSelect.innerHTML = optionsHtml;
            } catch (e) {
                contenuSelect.innerHTML = '<option value="">Erreur de données</option>';
                console.error('Erreur de parsing JSON:', e);
            }
        } else {
            contenuSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            console.error('Erreur AJAX:', xhr.statusText);
        }
    };
    xhr.send();
}

// Fonction principale pour charger les sous-thèmes ET filtrer les cours
function loadAndFilterCourses() {
    const themeId = document.getElementById('theme_filter').value;
    loadSousFormations(themeId);
    filterCourses(false);
}

// Fonction de filtrage des cartes de cours
function filterCourses() {
    const search = document.getElementById('search-input').value.toLowerCase();

    // Récupération des IDs
    const theme_id = document.getElementById('theme_filter').value;
    const subtheme_id = document.getElementById('subtheme_filter').value;

    // Récupération du NIVEAU
    const level = document.getElementById('level_filter').value; // <-- RÉACTIVÉ

    const theme = theme_id ? theme_id : '';
    const subtheme = subtheme_id ? subtheme_id : '';
    const price = document.getElementById('price_filter').value;

    const courses = document.querySelectorAll('.course-card');

    courses.forEach(course => {
        const courseTheme = course.dataset.theme;
        const courseSubtheme = course.dataset.subtheme;
        const courseLevel = course.dataset.level; // <-- RÉACTIVÉ
        const coursePrice = course.dataset.price;

        const matchesSearch = course.querySelector('h3').textContent.toLowerCase().includes(search);

        const matchesTheme = !theme || courseTheme === theme;
        const matchesSubtheme = !subtheme || courseSubtheme === subtheme;
        const matchesPrice = !price || coursePrice === price;

        // Comparaison du NIVEAU
        const matchesLevel = !level || courseLevel === level; // <-- RÉACTIVÉ

        // Combinaison des filtres (level réintégré)
        if (matchesSearch && matchesTheme && matchesSubtheme && matchesLevel && matchesPrice) {
            course.style.display = 'block';
        } else {
            course.style.display = 'none';
        }
    });
    sortCourses();
}


function sortCourses() {
    const sort = document.getElementById('sort').value;
    const grid = document.getElementById('courses-grid');
    const courses = Array.from(grid.querySelectorAll('.course-card'));

    courses.sort((a, b) => {
        const aIsVisible = a.style.display !== 'none';
        const bIsVisible = b.style.display !== 'none';

        // Si les deux sont invisibles ou les deux sont visibles, on trie normalement
        if (aIsVisible === bIsVisible) {
            if (sort === 'popularity') {
                return b.dataset.popularity - a.dataset.popularity;
            } else if (sort === 'newest') {
                // Pour le tri par date (non critique pour l'instant)
                const dateA = new Date(a.dataset.date).getTime();
                const dateB = new Date(b.dataset.date).getTime();
                return dateB - dateA;
            } else if (sort === 'price') {
                return parseFloat(a.dataset.priceValue) - parseFloat(b.dataset.priceValue);
            }
        }
    });

    // Réinsérer les cartes triées dans la grille
    grid.innerHTML = '';
    courses.forEach(course => grid.appendChild(course));
}

document.getElementById('search-input').addEventListener('input', filterCourses);
document.getElementById('theme_filter').addEventListener('change', loadAndFilterCourses);
document.getElementById('subtheme_filter').addEventListener('change', filterCourses);
document.getElementById('level_filter').addEventListener('change', filterCourses);
document.getElementById('price_filter').addEventListener('change', filterCourses);
document.getElementById('sort').addEventListener('change', sortCourses);

loadAndFilterCourses();