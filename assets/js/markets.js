document.addEventListener('DOMContentLoaded', () => {
  // Mobile nav
  const toggle = document.querySelector('.scm-menu-toggle');
  const nav = document.querySelector('.scm-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
    });
  }

  // Category filter tabs (.scm-tabs) — client-side, filters cards by data-cats
  document.querySelectorAll('.scm-tabs[data-filter-target]').forEach(tabs => {
    const grid = document.querySelector(tabs.dataset.filterTarget);
    if (!grid) return;
    tabs.addEventListener('click', e => {
      const btn = e.target.closest('[data-filter]');
      if (!btn) return;
      tabs.querySelectorAll('[data-filter]').forEach(b => b.classList.toggle('is-active', b === btn));
      const want = btn.dataset.filter;
      grid.querySelectorAll('.scm-card').forEach(card => {
        const cats = (card.dataset.cats || '').split(/\s+/);
        card.classList.toggle('is-hidden', want !== '*' && !cats.includes(want));
      });
    });
  });
});
