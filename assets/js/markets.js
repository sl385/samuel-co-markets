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

  // "Trading" dropdown parent (header.php main-menu) is a placeholder
  // link (href="#") that exists only to reveal its real sub-menu items on
  // hover — stop it jumping the page to the top when clicked directly.
  document.querySelectorAll('.scm-nav .menu-item-has-children > a[href="#"]').forEach(a => {
    a.addEventListener('click', e => e.preventDefault());
  });

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

  // Newsletter wrappers can relabel the shared CF7 form's submit button
  // (e.g. the Morning Brief panel says "Get the Brief" not "Subscribe").
  document.querySelectorAll('[data-newsletter][data-cta]').forEach(wrap => {
    wrap.querySelectorAll('input[type="submit"]').forEach(btn => { btn.value = wrap.dataset.cta; });
  });

  // Beehiiv signup forms (partials/components/newsletter-form.php) → REST relay
  document.querySelectorAll('[data-bh-form]').forEach(form => {
    const wrap = form.closest('[data-bh]');
    const msg = form.querySelector('.scm-bh-form__msg');
    const btn = form.querySelector('button[type="submit"]');
    const success = wrap && wrap.querySelector('.scm-newsletter-success');
    const show = (text) => { msg.textContent = text; msg.hidden = !text; };
    form.addEventListener('submit', async e => {
      e.preventDefault();
      show('');
      const email = form.email.value.trim();
      if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { show('Please enter a valid email address.'); form.email.focus(); return; }
      if (!form.consent.checked) { show('Please tick the consent box.'); form.consent.focus(); return; }
      const cfg = window.scmBeehiiv || {};
      if (!cfg.endpoint) { show('Signup is not available right now.'); return; }
      btn.disabled = true; btn.classList.add('is-loading');
      try {
        const res = await fetch(cfg.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, consent: true, source: form.dataset.source || 'website', website: form.website.value }) });
        const data = await res.json().catch(() => ({}));
        if (res.ok && data.ok) {
          form.hidden = true;
          if (success) { if (data.message) success.querySelector('span').textContent = data.message; success.hidden = false; }
          if (window.dataLayer) window.dataLayer.push({ event: 'newsletter_signup', source: form.dataset.source || 'website' });
        } else {
          show(data.message || 'Something went wrong. Please try again.');
        }
      } catch (err) {
        show('Could not reach the server. Please try again.');
      } finally {
        btn.disabled = false; btn.classList.remove('is-loading');
      }
    });
  });

  // "Markets at a glance" tabs (partials/components/static-markets-glance.php)
  document.querySelectorAll('[data-glance]').forEach(glance => {
    const tabs = glance.querySelectorAll('[data-panel]');
    tabs.forEach(tab => tab.addEventListener('click', () => {
      tabs.forEach(t => { const on = t === tab; t.classList.toggle('is-active', on); t.setAttribute('aria-selected', String(on)); });
      glance.querySelectorAll('.scm-glance__panel').forEach(p => { p.hidden = p.id !== tab.dataset.panel; });
    }));
  });

  // Team card -> bio modal (partials/components/team-card.php + footer.php .team-modal)
  const teamModal = document.querySelector('.team-modal');
  if (teamModal) {
    document.querySelectorAll('.scm-team-card').forEach(card => {
      card.addEventListener('click', () => {
        teamModal.querySelector('.team-modal__image').src = card.dataset.photo || '';
        teamModal.querySelector('.team-modal__title').textContent = card.dataset.name || '';
        teamModal.querySelector('.team-modal__sub').textContent = card.dataset.role || '';
        teamModal.querySelector('.team-modal__contents').innerHTML = card.dataset.bio || '';
        const social = teamModal.querySelector('.team-modal__social');
        social.innerHTML = '';
        if (card.dataset.linkedin) social.innerHTML += `<a href="${card.dataset.linkedin}" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin"></i></a>`;
        if (card.dataset.email) social.innerHTML += `<a href="mailto:${card.dataset.email}"><i class="fa-solid fa-envelope"></i></a>`;
        teamModal.classList.add('active');
      });
      card.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); card.click(); }
      });
    });
    teamModal.querySelectorAll('.team__member--exit').forEach(btn => {
      btn.addEventListener('click', e => { e.preventDefault(); teamModal.classList.remove('active'); });
    });
    teamModal.addEventListener('click', e => { if (e.target === teamModal) teamModal.classList.remove('active'); });
  }

  // Sticky mobile "buy" bar (woocommerce/content-product-extras.php) — shows
  // once the real add-to-cart block (#product-<id>) has scrolled out of view,
  // mobile only; it links back up to that same real block rather than
  // duplicating the add-to-cart form.
  const mobileBuy = document.getElementById('scm-mobile-buy');
  const productBlock = document.querySelector('[id^="product-"]');
  if (mobileBuy && productBlock && window.matchMedia('(max-width: 680px)').matches) {
    const io = new IntersectionObserver(([entry]) => {
      mobileBuy.hidden = entry.isIntersecting;
    }, { threshold: 0 });
    io.observe(productBlock);
  }

  // Success story carousel (template-funded-2025.php) — real success_story
  // posts, one shown at a time via .is-active.
  const storyCarousel = document.querySelector('.scm-story-carousel');
  if (storyCarousel) {
    const slides = storyCarousel.querySelectorAll('.scm-story-slide');
    const counter = document.getElementById('scm-story-current');
    let current = 0;
    storyCarousel.querySelectorAll('[data-story-dir]').forEach(btn => {
      btn.addEventListener('click', () => {
        slides[current].classList.remove('is-active');
        current = (current + Number(btn.dataset.storyDir) + slides.length) % slides.length;
        slides[current].classList.add('is-active');
        if (counter) counter.textContent = String(current + 1);
      });
    });
  }

  // Contact form success state (template-contact.php) — real CF7 event
  // (wpcf7mailsent), not a fake submit handler; CF7's own validation and
  // ajax submission are untouched, this only reacts to its real success event.
  const contactForm = document.getElementById('contact-form');
  const contactSuccess = document.getElementById('scm-contact-success');
  if (contactForm && contactSuccess) {
    contactForm.addEventListener('wpcf7mailsent', () => {
      contactForm.hidden = true;
      contactSuccess.hidden = false;
      contactSuccess.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  }

  // Same real wpcf7mailsent pattern, generalised for any real CF7 mailing-list
  // form wrapped in [data-newsletter] with a .scm-newsletter-success sibling
  // (currently just the hero's "Get the Morning Brief" form, hero.php).
  document.querySelectorAll('[data-newsletter]').forEach(wrap => {
    const success = wrap.querySelector('.scm-newsletter-success');
    const form = wrap.querySelector('form.wpcf7-form');
    if (!success || !form) return;
    form.addEventListener('wpcf7mailsent', () => {
      form.hidden = true;
      success.hidden = false;
    });
  });

  // Footer nav groups -> accordions on mobile (footer.php + _footer.scss)
  document.querySelectorAll('.scm-footer__toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('.scm-footer__group');
      const open = group.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', String(open));
      btn.querySelector('span').textContent = open ? '−' : '+';
    });
  });
});
