document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.scm-menu-toggle');
  const nav = document.querySelector('.scm-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
    });
  }
});
