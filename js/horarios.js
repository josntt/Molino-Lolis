// public/js/horarios.js

document.addEventListener("DOMContentLoaded", () => {
  const days = document.querySelectorAll(".horario-dia");

  if (!("IntersectionObserver" in window) || days.length === 0) {
    // Si el navegador es viejito o no hay elementos, los mostramos directo
    days.forEach(d => d.classList.add("is-in"));
    return;
  }

  const obs = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-in");
          obs.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.18
    }
  );

  days.forEach(d => obs.observe(d));
});
