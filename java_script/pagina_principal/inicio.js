// ******************************
// JS de la pagina principal
// *****************************

  // KM = Funcion para mostrar el mensaje de en proceso 
  function mostrarMensajeProceso(e) {
    if (e) e.preventDefault();
      const mensaje = document.getElementById('mensaje-proceso');

      mensaje.classList.add('show');

      setTimeout(() => {
        mensaje.classList.remove('show');
    }, 3000);
  }

  // KM = Funcion base para el sroll
  function smoothScroll(target, duration) {
    const targetEl = document.querySelector(target);
    if (!targetEl) return;

    const startPosition = window.pageYOffset;
    const targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;

    function animation(currentTime) {
      if (startTime === null) startTime = currentTime;
      const timeElapsed = currentTime - startTime;
      const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
      window.scrollTo(0, run);
      if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    function easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
  }

  // KM = Funcion para mostrar el video
  function videoLoaded() {
    document.getElementById('videoContainer').classList.add('activated');
    const thumbnail = document.getElementById('videoThumbnail');
    const btn = document.querySelector('.btn-play');
    thumbnail.style.transition = btn.style.transition = 'opacity 0.5s ease';
    thumbnail.style.opacity = btn.style.opacity = '0';
  }

  function playVideo() {
    const iframe = document.querySelector('#videoContainer iframe');
    iframe.src = "https://www.youtube.com/embed/f35vVsvNS2I?autoplay=1&modestbranding=1&showinfo=0";
    setTimeout(videoLoaded, 500);
  }

  // Funcion para mostar el carrucel mobil
  function showMobileCarousel(track, mobileCarousel) {
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        track.style.transition = "transform 0.5s ease";
        track.style.visibility = "visible";
        mobileCarousel.classList.add("loaded");
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {

  // KM = Navegacion del scroll
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const hash = this.getAttribute('href');
      if (!hash || hash === '#') return;
      e.preventDefault();
      smoothScroll(hash, 2000);
    });
  });

  // KM = Desplazammiento del menu hamburguesa
  const menu = document.getElementById("menu-navegacion");
  const boton = document.querySelector(".boton-menu");
  if (boton) {
    boton.addEventListener("click", () => {
      menu.classList.toggle("active");
    });
  }

  // KM = Carga del video
  const iframe = document.querySelector('#videoContainer iframe');
  if (iframe) iframe.addEventListener("load", videoLoaded);

  // KM =  Carrucel de valores
  const trackValues = document.querySelector('.values-track');
  const wrapper = document.querySelector('.values-wrapper');
  const nextBtnVal = document.querySelector('.values-next');
  const prevBtnVal = document.querySelector('.values-prev');
  const itemsVal = Array.from(document.querySelectorAll('.section-item'));
  let itemsPerView = 1, indexVal = 0;

  function getItemsPerView() {
    const w = window.innerWidth;
    if (w >= 930) return 3;
    if (w >= 650) return 2;
    return 1;
  }

  function updatePerView() {
    itemsPerView = getItemsPerView();
    indexVal = 0;
    moveToIndexVal(indexVal, false);
  }

  function moveToIndexVal(i, animate = true) {
    const itemWidth = itemsVal[0].getBoundingClientRect().width;
    trackValues.style.transition = animate ? 'transform 0.5s ease-in-out' : 'none';
    trackValues.style.transform = `translateX(-${i * itemWidth}px)`;
  }

  function nextSlideVal() {
    const maxIndex = itemsVal.length - itemsPerView;
    indexVal = (indexVal < maxIndex) ? indexVal + itemsPerView : 0;
    moveToIndexVal(indexVal, true);
  }

  function prevSlideVal() {
    const maxIndex = itemsVal.length - itemsPerView;
    indexVal = (indexVal > 0) ? indexVal - itemsPerView : maxIndex;
    moveToIndexVal(indexVal, true);
  }

  if (trackValues) {
    nextBtnVal.addEventListener('click', nextSlideVal);
    prevBtnVal.addEventListener('click', prevSlideVal);
    window.addEventListener('resize', updatePerView);
    updatePerView();
  }

  // KM = Tarjetas de apoyo
  document.querySelectorAll('.apoyo-card').forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('active');
    });
  });

  // KM = Carrucel mobil, si esto es para que no de como decirlo, que vaya del 1 al 3 y de 3 a 1
  const mobileCarousel = document.querySelector(".carousel-mobile");

  if (mobileCarousel) {
    const track = mobileCarousel.querySelector(".carousel-track");
    let slides = Array.from(track.children);

    
    const firstClone = slides[0].cloneNode(true);
    const lastClone = slides[slides.length - 1].cloneNode(true);
    firstClone.classList.add("clone");
    lastClone.classList.add("clone");
    track.appendChild(firstClone);
    track.insertBefore(lastClone, slides[0]);
    slides = Array.from(track.children);

    const nextBtn = mobileCarousel.querySelector(".carousel-button--right");
    const prevBtn = mobileCarousel.querySelector(".carousel-button--left");
    const indicatorsNav = mobileCarousel.querySelector(".carousel-nav");
    const indicators = Array.from(indicatorsNav.children);

    let currentIndex = 0; 
    const slideWidth = 100;
    let isAnimating = false;

  // KM = Ocultar temporalmente los hermanos del protaginista
  mobileCarousel.style.opacity = "0";
  mobileCarousel.style.transition = "opacity 0.3s ease";

  const images = track.querySelectorAll("img");
  let loadedCount = 0;

  images.forEach(img => {
    if (img.complete) {
      loadedCount++;
    } else {
      img.addEventListener("load", checkLoad);
      img.addEventListener("error", checkLoad);
    }
  });

  if (loadedCount === images.length) initCarousel();

  function checkLoad() {
    loadedCount++;
    if (loadedCount === images.length) initCarousel();
  }

  function initCarousel() {
    currentIndex = 1; 
    track.style.transition = "none";
    track.style.transform = `translateX(-${slideWidth * currentIndex}%)`;

    requestAnimationFrame(() => {
      mobileCarousel.style.opacity = "1";
      track.style.transition = "transform 0.5s ease";
    });
  }

  function updateIndicators(index) {
    indicators.forEach(btn => btn.classList.remove("current"));
    indicators[index].classList.add("current");
  }

  function moveToSlide(index) {
    if (isAnimating) return;
    isAnimating = true;
    track.style.transition = "transform 0.5s ease";
    track.style.transform = `translateX(-${slideWidth * index}%)`;
    currentIndex = index;

    let realIndex = index - 1;
    if (realIndex < 0) realIndex = indicators.length - 1;
    if (realIndex >= indicators.length) realIndex = 0;
    updateIndicators(realIndex);
  }

  function jumpToSlide(index) {
    track.style.transition = "none";
    track.style.transform = `translateX(-${slideWidth * index}%)`;
    currentIndex = index;
    isAnimating = false;
  }

  nextBtn.addEventListener("click", () => {
    if (!isAnimating) moveToSlide(currentIndex + 1);
  });

  prevBtn.addEventListener("click", () => {
    if (!isAnimating) moveToSlide(currentIndex - 1);
  });

  track.addEventListener("transitionend", () => {
    if (slides[currentIndex].classList.contains("clone")) {
      if (currentIndex === 0) jumpToSlide(slides.length - 2);
      else if (currentIndex === slides.length - 1) jumpToSlide(1);
    } else {
      isAnimating = false;
    }
  });

  indicatorsNav.addEventListener("click", (e) => {
    const targetBtn = e.target.closest("button");
    if (!targetBtn || isAnimating) return;
    const index = indicators.indexOf(targetBtn);
    moveToSlide(index + 1);
  });

  setInterval(() => {
    if (!isAnimating) moveToSlide(currentIndex + 1);
   }, 5000);
  }

  // KM = Animacion del carrucerl para escritorio
  const desktopCarousel = document.querySelector(".carousel-desktop");

  if (desktopCarousel) {
    const track = desktopCarousel.querySelector(".carousel-track");
    let slides = Array.from(track.children);

    const firstClone = slides[0].cloneNode(true);
  const lastClone = slides[slides.length - 1].cloneNode(true);
  firstClone.classList.add("clone");
  lastClone.classList.add("clone");
  track.appendChild(firstClone);
  track.insertBefore(lastClone, slides[0]);
  slides = Array.from(track.children);

  const nextBtn = desktopCarousel.querySelector(".carousel-button--right");
  const prevBtn = desktopCarousel.querySelector(".carousel-button--left");
  const indicatorsNav = desktopCarousel.querySelector(".carousel-navita");
  const indicators = Array.from(indicatorsNav.children);

  let currentIndex = 1;
  const slideWidth = 100;
  let isAnimating = false;

  desktopCarousel.style.opacity = "0";
  desktopCarousel.style.transition = "opacity 0.3s ease";

  const images = track.querySelectorAll("img");
  let loadedCount = 0;

  images.forEach(img => {
    if (img.complete) {
      loadedCount++;
    } else {
      img.addEventListener("load", checkLoad);
      img.addEventListener("error", checkLoad);
    }
  });

  if (loadedCount === images.length) initCarousel();

  function checkLoad() {
    loadedCount++;
    if (loadedCount === images.length) initCarousel();
  }

  function initCarousel() {
    currentIndex = 1;
    track.style.transition = "none";
    track.style.transform = `translateX(-${slideWidth * currentIndex}%)`;

    requestAnimationFrame(() => {
      desktopCarousel.style.opacity = "1";
      track.style.transition = "transform 0.5s ease";
    });
  }

  function updateIndicators(index) {
    indicators.forEach(btn => btn.classList.remove("current"));
    indicators[index].classList.add("current");
  }

  function moveToSlide(index) {
    if (isAnimating) return;
    isAnimating = true;
    track.style.transition = "transform 0.5s ease";
    track.style.transform = `translateX(-${slideWidth * index}%)`;
    currentIndex = index;

    let realIndex = index - 1;
    if (realIndex < 0) realIndex = indicators.length - 1;
    if (realIndex >= indicators.length) realIndex = 0;
    updateIndicators(realIndex);
  }

  function jumpToSlide(index) {
    track.style.transition = "none";
    track.style.transform = `translateX(-${slideWidth * index}%)`;
    currentIndex = index;
    isAnimating = false;
  }

  nextBtn.addEventListener("click", () => {
    if (!isAnimating) moveToSlide(currentIndex + 1);
  });

  prevBtn.addEventListener("click", () => {
    if (!isAnimating) moveToSlide(currentIndex - 1);
  });

  track.addEventListener("transitionend", () => {
    if (slides[currentIndex].classList.contains("clone")) {
      if (currentIndex === 0) jumpToSlide(slides.length - 2);
      else if (currentIndex === slides.length - 1) jumpToSlide(1);
    } else {
      isAnimating = false;
    }
  });

  indicatorsNav.addEventListener("click", (e) => {
    const targetBtn = e.target.closest("button");
    if (!targetBtn || isAnimating) return;
    const index = indicators.indexOf(targetBtn);
    moveToSlide(index + 1);
  });

  setInterval(() => {
    if (!isAnimating) moveToSlide(currentIndex + 1);
   }, 5000);
  }

  // KM = Animacion cicular de valores
  document.querySelectorAll('.circle, .circle_2').forEach(img => {
    img.addEventListener('click', () => {
      img.classList.add('rotate');
      img.addEventListener('animationend', () => {
        img.classList.remove('rotate');
      }, { once: true });
    });
  });

  // KM = Animacion para las policas de la pagina
  new Glide('.glide', {
    type: 'carousel',
    startAt: 0,
    perView: 3, 
    gap: 12,     
    focusAt: 'center',
    rewind: true,
    bound: true,
    animationDuration: 500,
  
    breakpoints: {
      1279: { perView: 2, gap: 12 }, 
      820:  { perView: 1, gap: 8 }  
    }
  }).mount();

  document.querySelectorAll('.glide__slide img').forEach(img => {
    img.addEventListener('mouseenter', () => {
      img.style.animation = 'none';
      img.offsetHeight; 
      img.style.animation = 'bounce-img 0.6s ease';
    });

    img.addEventListener('animationend', () => {
      img.style.animation = '';
    });
  });

  // KM  = Mostrar gif en equipo jajaja
  document.querySelectorAll('.flip-card').forEach(card => {
    card.addEventListener('click', () => {
      card.classList.add('show-anim');

      setTimeout(() => {
        card.classList.remove('show-anim');
      }, 4000);
    });
  });

  // KM = Carrucel de testimonios, yep aca es donde se llenan jaja 
  const testimonios = [
    {
      texto: '"Mi primer acoso sexual empezó a los 6 años, por parte de un maestro; a los 7, por un familiar. Dudé si hablar fue correcto. Muchas personas me decían que mentía, que lo mejor era guardar silencio. Pero, ¿por qué una niña de 7 años debería ser culpable? Ella fue inocente, engañada para guardar un secreto. No debemos vivir con miedo ni vergüenza; la culpa no es nuestra, es culpa de esas personas que se aprovechan de la vulnerabilidad."',
      autor: 'Anónimo',
      rol: 'No es culpa de la víctima'
    },
    {
      texto: '"En espera..."',
      autor: '...',
      rol: '...'
    },
    {
      texto: '"En espera..."',
      autor: '...',
      rol: '...'
    }
  ];
  
  let currentPage = 0;
  const pageDiv = document.getElementById('page-content');
  const btnPrev = document.getElementById('prev-tes');
  const btnNext = document.getElementById('next-tes');
  
  function updatePage(direction = 'next-tes') {
    pageDiv.classList.remove('slide-in-left', 'slide-in-right');
    void pageDiv.offsetWidth;
  
    const { texto, autor, rol } = testimonios[currentPage];
    pageDiv.innerHTML = `
    <div>
      <p class="testimonio-texto">${texto}</p>
      <p class="testimonio-autor">${autor}</p>
      <p class="testimonio-rol">${rol}</p>
    </div>
    `;
  
    if (direction === 'next-tes') {
      pageDiv.classList.add('slide-in-left');
    } else {
      pageDiv.classList.add('slide-in-right');
    }
  
    btnPrev.disabled = currentPage === 0;
    btnNext.disabled = currentPage === testimonios.length - 1;
  }
  
  btnNext.addEventListener('click', () => {
    if (currentPage < testimonios.length - 1) {
      currentPage++;
      updatePage('next-tes');
    }
  });
  
  btnPrev.addEventListener('click', () => {
    if (currentPage > 0) {
      currentPage--;
      updatePage('prev-tes');
    }
  });
  
  updatePage(); 

  // KM =  Modo oscuro
  const btnModo = document.getElementById("modo-toggle");
  const iconoModo = document.getElementById("icono-modo");

  btnModo.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

  // KM = Cambiar ícono según el modo ya sea oscuro a luz y viceversa
  const modoOscuroActivo = document.body.classList.contains("dark-mode");

  iconoModo.src = modoOscuroActivo
    ? "../../iconos/pagina_principal/sol.png" 
    : "../../iconos/pagina_principal/luna.png";

  iconoModo.alt = modoOscuroActivo ? "Modo claro" : "Modo oscuro";
  });

  // KM = Scroll para subir al incio de la pagina
  const scrollTopBtn = document.getElementById('btnScrollTop');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      scrollTopBtn.classList.add('visible');
    } else {
      scrollTopBtn.classList.remove('visible');
    }
  });

  scrollTopBtn.addEventListener('click', () => {
    const scrollStep = -window.scrollY / 60; 
    const scrollInterval = setInterval(() => {
      if (window.scrollY !== 0) {
        window.scrollBy(0, scrollStep);
      } else {
        clearInterval(scrollInterval);
      }
    }, 16);
  });

});