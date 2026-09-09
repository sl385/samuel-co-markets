</main>
<footer class="scm-footer">
  <div class="scm-shell scm-footer__grid">
    <div>
      <a class="scm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <span class="scm-brand__main">SAMUEL &amp; CO</span>
        <span class="scm-brand__sub">TRADING</span>
      </a>
      <p class="scm-footer__tagline">Markets. Research. Education.</p>
    </div>
    <div>
      <?php
      wp_nav_menu([
        'theme_location' => 'footer',
        'container' => false,
        'fallback_cb' => false,
        'items_wrap' => '<ul class="scm-footer__links">%3$s</ul>',
      ]);
      ?>
    </div>
    <div class="scm-footer__legal">
      <p>Knowledge. Discipline. Opportunity.</p>
      <p>&copy; <?php echo esc_html(date('Y')); ?> Samuel &amp; Co Trading.</p>
      <p>Market content is provided for information and education and is not personal investment advice.</p>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
