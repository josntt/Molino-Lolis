// ---- Barra de progreso de scroll
(function(){
  const bar = document.createElement('div');
  bar.className = 'scrollbar';
  document.body.appendChild(bar);
  const set = ()=>{
    const s = window.scrollY, h = document.documentElement.scrollHeight - window.innerHeight;
    const p = h > 0 ? (s / h) * 100 : 0;
    bar.style.width = p + '%';
  };
  set(); window.addEventListener('scroll', set, {passive:true});
})();

// ---- Reveal en scroll
const io = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      e.target.classList.add('is-in');
      io.unobserve(e.target);
    }
  });
},{ threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el=> io.observe(el));

// ---- Accesibilidad para <summary>
document.addEventListener('keydown', (ev)=>{
  if(ev.key === 'Enter' && ev.target.matches('.acc__sum')){
    ev.preventDefault(); ev.target.click();
  }
});

// ---- Parallax sutil + tilt del logo y hover tilt de cards
const parallaxEls = document.querySelectorAll('.about-hero__bg, .parallax');
const logoEls = document.querySelectorAll('.brand-mark');
window.addEventListener('mousemove', (e)=>{
  const x = (e.clientX / window.innerWidth - 0.5);
  const y = (e.clientY / window.innerHeight - 0.5);
  parallaxEls.forEach(bg=>{
    bg.style.transform = `translate(${x * 14}px, ${y * 10}px)`;
  });
  logoEls.forEach(l=>{
    l.style.transform = `rotateX(${y * -5}deg) rotateY(${x * 5}deg) scale(1.01)`;
  });
});
window.addEventListener('mouseleave', ()=>{
  parallaxEls.forEach(bg=> bg.style.transform = '');
  logoEls.forEach(l=> l.style.transform = '');
});

// ---- Ripple en botones .btn
document.addEventListener('click', (e)=>{
  const btn = e.target.closest('.btn');
  if(!btn) return;
  const r = document.createElement('span');
  r.className = 'ripple';
  const rect = btn.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  r.style.width = r.style.height = size + 'px';
  r.style.left = (e.clientX - rect.left - size/2) + 'px';
  r.style.top  = (e.clientY - rect.top  - size/2) + 'px';
  btn.appendChild(r);
  setTimeout(()=> r.remove(), 650);
});

// Scroll suave al mapa cuando se clickea el título "Módulo de contacto"
document.addEventListener('click', (e)=>{
  const a = e.target.closest('.heading-link[href^="#"]');
  if(!a) return;
  e.preventDefault();
  const el = document.querySelector(a.getAttribute('href'));
  if(el){ el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
});

// Reusar el observer para activar slide-in en columns
document.querySelectorAll('.slide-in-left, .slide-in-right').forEach(el=> io.observe(el));

// Tilt sutil en tarjetas (seguro y ligero)
(function(){
  const cards = document.querySelectorAll('.abox');
  const max = 6; // grados máximos
  cards.forEach(card=>{
    card.addEventListener('mousemove', (e)=>{
      const r = card.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - 0.5;
      const y = (e.clientY - r.top) / r.height - 0.5;
      card.style.transform = `translateY(-6px) rotateX(${(-y*max).toFixed(2)}deg) rotateY(${(x*max).toFixed(2)}deg)`;
    });
    card.addEventListener('mouseleave', ()=>{
      card.style.transform = '';
    });
    card.addEventListener('mouseenter', ()=>{
      // subimos tantito al entrar para que el tilt se sienta más vivo
      card.style.transform = 'translateY(-6px)';
    });
  });
})();


/*  */
// Animación de las tarjetas de anuncios
document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.fade-up');
  elements.forEach(element => {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('is-in');
        }
      });
    }, { threshold: 0.2 });
    observer.observe(element);
  });
});

