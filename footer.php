<?php
$is_account = function_exists('is_account_page') && is_account_page();
$press_logos = function_exists('get_field') ? get_field('footer_logos_new', 'option') : null;
$social = [];
if (function_exists('get_field')) {
  foreach ([
    'instagram_url'     => 'fa-brands fa-instagram',
    'facebook_page_url' => 'fa-brands fa-facebook',
    'linked_in'         => 'fa-brands fa-linkedin',
    'twitter_url'       => 'fa-brands fa-x-twitter',
    'youtube_url'       => 'fa-brands fa-youtube',
  ] as $field => $icon) {
    if ($url = get_field($field, 'option')) $social[$url] = $icon;
  }
}
?>
<?php if (!$is_account): ?>
  <section class="scm-prefooter" id="maillist">
    <div class="scm-shell">
      <span class="scm-eyebrow" style="color:#dfbe88">Mailing list</span>
      <h2>Sign up to our mailing list</h2>
      <p>Join our mailing list to gain access to the latest news &amp; research.</p>
      <?php echo do_shortcode('[contact-form-7 id="493" title="Subscription Sign Up Form"]'); ?>
    </div>
  </section>
  <?php if (!empty($press_logos) && is_array($press_logos)): ?>
  <section class="scm-press">
    <div class="scm-shell">
      <span class="scm-eyebrow">Samuel &amp; Co. in the news</span>
      <ul>
        <?php foreach ($press_logos as $logo): if (empty($logo['logo'])) continue; ?>
          <li>
            <?php if (!empty($logo['logo_url'])): ?><a target="_blank" rel="noopener" href="<?php echo esc_url($logo['logo_url']); ?>"><?php endif; ?>
              <img src="<?php echo esc_url($logo['logo']); ?>" loading="lazy" alt="">
            <?php if (!empty($logo['logo_url'])): ?></a><?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>
<?php endif; ?>
</main>
<footer class="scm-footer">
  <div class="scm-shell">
    <p class="scm-footer__risk">
      Spread betting and CFD trading are leveraged products and as such carry a high level of risk to your capital which can result in losses of your entire deposit. These products may not be suitable for all investors. CFDs are not suitable for pension building and income. Ensure you fully understand all risks involved and seek independent advice if necessary.<br><br>
      For the avoidance of doubt, Samuel &amp; Co Trading is not independently regulated by the FCA. Samuel &amp; Co Trading Limited is a Partner to Pelican Trading. Pelican Trading is a trading name of London &amp; Eastern LLP. London &amp; Eastern LLP is authorised and regulated by the Financial Conduct Authority in the UK, ref 534484. Registered address: 78 York Street, W1H 1DP, London.
    </p>
  </div>
  <div class="scm-shell scm-footer__grid">
    <div>
      <a class="scm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <span class="scm-brand__main">SAMUEL &amp; CO</span>
        <span class="scm-brand__sub">TRADING</span>
      </a>
      <p class="scm-footer__tagline">Markets. Research. Education.</p>
      <?php if ($social): ?>
      <ul class="scm-footer__social">
        <?php foreach ($social as $url => $icon): ?>
          <li><a target="_blank" rel="noopener" href="<?php echo esc_url($url); ?>"><i class="<?php echo esc_attr($icon); ?>"></i></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <?php if (function_exists('get_field') && ($phone = get_field('contact_phone_number', 'option'))): ?>
        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="scm-button">Call Us</a>
      <?php endif; ?>
    </div>
    <div>
      <?php
      wp_nav_menu([
        'theme_location' => 'footer',
        'container' => false,
        'fallback_cb' => false,
        'depth' => 1,
        'items_wrap' => '<ul class="scm-footer__links">%3$s</ul>',
      ]);
      ?>
    </div>
    <div class="scm-footer__legal">
      <p>Knowledge. Discipline. Opportunity.</p>
      <p>&copy; <?php echo esc_html(date('Y')); ?> Samuel &amp; Co Trading.</p>
      <p>Market content is provided for information and education and is not personal investment advice.</p>
      <p class="scm-footer__meta">VAT Number: 225 844 109 | Limited company registered in England &amp; Wales (08100330)</p>
    </div>
  </div>
</footer>

<div class="team-modal" aria-hidden="true">
  <div class="team-modal__content">
    <a class="team__member--exit" href="#"><i class="fa-solid fa-times"></i></a>
    <img class="team-modal__image" src="" alt="">
    <header>
      <h2 class="team-modal__title"></h2>
      <div class="team-modal__social"></div>
    </header>
    <h3 class="team-modal__sub"></h3>
    <div class="team-modal__contents"></div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
