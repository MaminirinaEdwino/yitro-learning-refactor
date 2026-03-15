const topNav = document.querySelector('.main-nav');
let lastScrollTop = 0;
let isAnimating = false;
let scrollTimeout = null;

function handleScroll() {
    if (isAnimating) return;

    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > 100) {
        if (!topNav.classList.contains('hidden')) {
            isAnimating = true;
            topNav.classList.add('hidden');
            setTimeout(() => {
                topNav.classList.add('invisible');
                isAnimating = false;
            }, 500);
        }
    } else {
        if (topNav.classList.contains('hidden') || topNav.classList.contains('invisible')) {
            isAnimating = true;
            topNav.classList.remove('invisible');
            topNav.offsetHeight;
            topNav.classList.remove('hidden');
            setTimeout(() => {
                isAnimating = false;
            }, 500);
        }
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
}

window.addEventListener('scroll', () => {
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }
    scrollTimeout = setTimeout(handleScroll, 100);
});

//la sidebar et le filtrage
const sidebarToggle = document.querySelector('.sidebar-toggle');
const sidebar = document.querySelector('.sidebar');
const applyFiltersButton = document.querySelector('#apply-filters');

// FILTRES DE COURS
const categoryFilter = document.querySelector('#category_filter');
const courseLevel = document.querySelector('#course-level');
const priceMin = document.querySelector('#price-min');
const priceMax = document.querySelector('#price-max');
const priceMinValue = document.querySelector('#price-min-value');
const priceMaxValue = document.querySelector('#price-max-value');
const courseCards = document.querySelectorAll('.course-card');

// FILTRES DE FORUM
const forumCourse = document.querySelector('#forum-course');
const forumKeyword = document.querySelector('#forum-keyword');
const forumCards = document.querySelectorAll('.forum-card');
// Toggle sidebar on mobile
sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
});

// Update price range values
priceMin.addEventListener('input', () => {
    // S'assurer que le min ne dépasse pas le max
    if (parseFloat(priceMin.value) > parseFloat(priceMax.value)) {
        priceMax.value = priceMin.value;
    }
    priceMinValue.textContent = priceMin.value;
    priceMaxValue.textContent = priceMax.value;
});

priceMax.addEventListener('input', () => {
    // S'assurer que le max ne soit pas inférieur au min
    if (parseFloat(priceMax.value) < parseFloat(priceMin.value)) {
        priceMin.value = priceMax.value;
    }
    priceMinValue.textContent = priceMin.value;
    priceMaxValue.textContent = priceMax.value;
});


// Fonction principale de filtrage, appelée par le bouton
function applyFilters() {
    //Recuperation des valeurs des filtres de cours
    const selectedThemeId = categoryFilter.value;
    const selectedLevel = courseLevel.value;
    const minPrice = parseFloat(priceMin.value);
    const maxPrice = parseFloat(priceMax.value);

    //filtrer les cours
    let coursesVisibleCount = 0;
    courseCards.forEach(card => {
        const themeId = card.dataset.themeId || '';
        const level = card.dataset.level || '';
        const price = parseFloat(card.dataset.price) || 0;

        const matchesTheme = !selectedThemeId || themeId === selectedThemeId;
        const matchesLevel = !selectedLevel || level === selectedLevel;
        const matchesPrice = price >= minPrice && price <= maxPrice;

        if (matchesTheme && matchesLevel && matchesPrice) {
            card.style.display = 'block';
            coursesVisibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // recuperation des valeurs des filtres de forum
    const selectedForumCourse = forumCourse.value;
    const keyword = forumKeyword.value.toLowerCase();

    // filtrer les forums 
    forumCards.forEach(card => {
        const courseId = card.dataset.courseId || '';
        const title = card.dataset.title.toLowerCase();
        const description = card.dataset.description.toLowerCase();

        const matchesCourse = !selectedForumCourse || courseId === selectedForumCourse;
        const matchesKeyword = !keyword || title.includes(keyword) || description.includes(keyword);

        if (matchesCourse && matchesKeyword) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

}

applyFiltersButton.addEventListener('click', applyFilters);

document.addEventListener('DOMContentLoaded', () => {
    applyFilters();
});
