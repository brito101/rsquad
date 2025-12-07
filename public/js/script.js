const links = document.querySelectorAll(".header-menu a");

function activateLink(link) {
  const url = location.href;
  const href = link.href;
  if (url.includes(href)) {
    link.classList.add("active");
  }
}

links.forEach(activateLink);

// Questions
const questions = document.querySelectorAll(".questions button");

function activateQuestion(event) {
  const question = event.currentTarget;
  const controls = question.getAttribute("aria-controls");
  const reponse = document.getElementById(controls);

  reponse.classList.toggle("active");
  const active = reponse.classList.contains("active");
  question.setAttribute("aria-expanded", active);
}

function eventQuestions(question) {
  question.addEventListener("click", activateQuestion);
}

questions.forEach(eventQuestions);

// Cheat
const cheats = document.querySelectorAll(".cheats button");

function activateCheat(event) {
  const cheat = event.currentTarget;
  const controls = cheat.getAttribute("aria-controls");
  const reponseCheat = document.getElementsByClassName(controls);

  Array.from(reponseCheat).forEach(function (el) {
    el.classList.toggle("active");
  });

  const activeCheat = Array.from(reponseCheat).some(el => el.classList.contains("active"));
  cheat.setAttribute("aria-expanded", activeCheat);
}

function eventCheat(cheat) {
  cheat.addEventListener("click", activateCheat);
}

cheats.forEach(eventCheat);

// Animation
if (window.SimpleAnime) {
  new SimpleAnime();
}

// Testimonials Carousel
(function () {
  const carousel = document.querySelector('.testimonials-carousel');
  if (!carousel) return;

  const wrapper = carousel.querySelector('.testimonials-wrapper');
  const items = wrapper.querySelectorAll('.testimonial-item');
  const dotsContainer = carousel.querySelector('.testimonials-dots');
  const prevBtn = carousel.querySelector('.testimonial-prev');
  const nextBtn = carousel.querySelector('.testimonial-next');

  let currentIndex = 0;
  const totalItems = items.length;
  let autoplayInterval;
  let isTransitioning = false;

  // Criar dots
  items.forEach((_, index) => {
    const dot = document.createElement('button');
    dot.className = 'testimonial-dot';
    dot.setAttribute('aria-label', `Ir para depoimento ${index + 1}`);
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });

  const dots = dotsContainer.querySelectorAll('.testimonial-dot');

  function updateCarousel() {
    if (isTransitioning) return;

    isTransitioning = true;

    const offset = -currentIndex * 100;
    wrapper.style.transform = `translateX(${offset}%)`;
    
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });

    setTimeout(() => {
      isTransitioning = false;
    }, 500);
  }

  function goToSlide(index) {
    if (isTransitioning || index === currentIndex) return;
    currentIndex = index;
    updateCarousel();
    resetAutoplay();
  }

  function nextSlide() {
    if (isTransitioning) return;
    currentIndex = (currentIndex + 1) % totalItems;
    updateCarousel();
  }

  function prevSlide() {
    if (isTransitioning) return;
    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
    updateCarousel();
  }

  function startAutoplay() {
    autoplayInterval = setInterval(nextSlide, 5000);
  }

  function stopAutoplay() {
    if (autoplayInterval) {
      clearInterval(autoplayInterval);
      autoplayInterval = null;
    }
  }

  function resetAutoplay() {
    stopAutoplay();
    startAutoplay();
  }

  // Event listeners
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetAutoplay();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetAutoplay();
    });
  }

  // Pausar autoplay quando usuário interage
  carousel.addEventListener('mouseenter', stopAutoplay);
  carousel.addEventListener('mouseleave', startAutoplay);

  // Suporte para touch/swipe em mobile
  let touchStartX = 0;
  let touchEndX = 0;
  let touchStartY = 0;
  let touchEndY = 0;

  wrapper.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    touchStartY = e.changedTouches[0].screenY;
  }, { passive: true });

  wrapper.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    touchEndY = e.changedTouches[0].screenY;
    handleSwipe();
  }, { passive: true });

  function handleSwipe() {
    const diffX = touchStartX - touchEndX;
    const diffY = Math.abs(touchStartY - touchEndY);

    // Só processar swipe horizontal se não for scroll vertical
    if (Math.abs(diffX) > 50 && diffY < 50) {
      if (diffX > 0) {
        nextSlide();
      } else {
        prevSlide();
      }
      resetAutoplay();
    }
  }

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (!carousel.matches(':hover')) return;

    if (e.key === 'ArrowLeft') {
      e.preventDefault();
      prevSlide();
      resetAutoplay();
    } else if (e.key === 'ArrowRight') {
      e.preventDefault();
      nextSlide();
      resetAutoplay();
    }
  });

  // Iniciar autoplay
  startAutoplay();

  // Garantir que o primeiro item está visível
  updateCarousel();
})();
