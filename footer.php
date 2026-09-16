<?php
$is_account = function_exists('is_account_page') && is_account_page();
// The course player is its own, stronger case than account pages: an
// immersive, full-height lesson view shouldn't carry ANY of the
// marketing footer — not just the newsletter/press band (account pages
// only skip that much), but the nav links/social/risk-disclaimer/legal
// block too. Real site chrome that belongs on every other page reads as
// clutter directly under a lesson.
$is_course = is_singular('course');
$press_logos = function_exists('get_field') ? get_field('footer_logos_new', 'option') : null;
$social = [];
if (function_exists('get_field')) {
  foreach ([
    'instagram_url'     => 'fa-brands fa-instagram',
    'facebook_page_url' => 'fa-brands fa-facebook',
    'linked_in'         => 'fa-brands fa-linkedin',
    'twitter_url'       => 'fa-brands fa-twitter', // fa-x-twitter doesn't exist in the fa v6.1.1 bundle this theme loads — same class of gap as the fa-regular icons fixed earlier
    'youtube_url'       => 'fa-brands fa-youtube',
  ] as $field => $icon) {
    if ($url = get_field($field, 'option')) $social[$url] = $icon;
  }
}

// Footer nav, grouped by what a visitor's trying to do — real pages, the
// same ones in the header's real "Main Menu" (wp_nav_menu id 2), just
// regrouped for the footer instead of one flat link list.
$footer_groups = [
  'Trade' => [
    ['Get Funded', home_url('/get-funded/')],
    ['Trading Tools', home_url('/trading-tools/')],
    ['Trader Hub', home_url('/product/trader-hub/')],
    ['Shop', home_url('/shop/')],
  ],
  'Learn' => [
    ['Trading Courses', home_url('/trading-courses/')],
    ['News & Research', home_url('/news/')],
    ['Events', home_url('/event/')],
  ],
  'Support' => [
    ['About Us', home_url('/about-us/')],
    ['Contact Us', home_url('/contact-us/')],
    ['My Account', home_url('/my-account/')],
  ],
];

// Legal baseline row — the real "Footer Menu" (Privacy/Cookie/Risk
// Disclosure/Terms) minus "Contact Us", which now lives in Support above
// instead of being lumped in with legal links. 'footer' here is the
// registered theme_location, not the menu's own slug — has to be resolved
// via nav_menu_locations first, wp_get_nav_menu_items() doesn't take a
// location name.
$legal_links = [];
$footer_locations = get_nav_menu_locations();
if (!empty($footer_locations['footer']) && ($footer_menu = wp_get_nav_menu_items($footer_locations['footer']))) {
  foreach ($footer_menu as $item) {
    if (stripos($item->title, 'contact') !== false) continue;
    $legal_links[] = ['title' => $item->title, 'url' => $item->url];
  }
}
?>
<?php if (!$is_account && !$is_course): ?>
  <section class="scm-prefooter" id="maillist">
    <div class="scm-shell scm-prefooter__inner">
      <div>
        <span class="scm-eyebrow" style="color:#dfbe88">Market perspective</span>
        <h2>Useful trading insight, without the daily noise.</h2>
        <p>Get Samuel &amp; Co's market commentary, practical education and selected event invitations &mdash; sent when we have something worth reading.</p>
      </div>
      <div class="scm-newsletter">
        <?php echo do_shortcode('[contact-form-7 id="493" title="Subscription Sign Up Form"]'); ?>
      </div>
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
<?php if (!$is_course): ?>
<footer class="scm-footer">
  <div class="scm-shell scm-footer__main">
    <div class="scm-footer__brand">
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
        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="scm-button scm-button--on-dark">Call Us</a>
      <?php endif; ?>
    </div>
    <?php foreach ($footer_groups as $group_title => $links): ?>
      <section class="scm-footer__group">
        <h3><?php echo esc_html($group_title); ?></h3>
        <button class="scm-footer__toggle" aria-expanded="false" type="button"><?php echo esc_html($group_title); ?><span>+</span></button>
        <nav class="scm-footer__links">
          <?php foreach ($links as [$label, $url]): ?>
            <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
          <?php endforeach; ?>
        </nav>
      </section>
    <?php endforeach; ?>
  </div>

  <div class="scm-footer__risk">
    <div class="scm-shell">
      <p>
        Spread betting and CFD trading are leveraged products and as such carry a high level of risk to your capital which can result in losses of your entire deposit. These products may not be suitable for all investors. CFDs are not suitable for pension building and income. Ensure you fully understand all risks involved and seek independent advice if necessary.<br><br>
        For the avoidance of doubt, Samuel &amp; Co Trading is not independently regulated by the FCA. Samuel &amp; Co Trading Limited is a Partner to Pelican Trading. Pelican Trading is a trading name of London &amp; Eastern LLP. London &amp; Eastern LLP is authorised and regulated by the Financial Conduct Authority in the UK, ref 534484. Registered address: 78 York Street, W1H 1DP, London.
      </p>
    </div>
  </div>

  <div class="scm-shell scm-footer__base">
    <div class="scm-footer__legal">
      <p>Knowledge. Discipline. Opportunity.</p>
      <p>&copy; <?php echo esc_html(date('Y')); ?> Samuel &amp; Co Trading.</p>
      <p>Market content is provided for information and education and is not personal investment advice.</p>
      <p class="scm-footer__meta">VAT Number: 225 844 109 | Limited company registered in England &amp; Wales (08100330)</p>
    </div>
    <?php if ($legal_links): ?>
      <nav class="scm-footer__legal-links" aria-label="Legal">
        <?php foreach ($legal_links as $link): ?>
          <a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['title']); ?></a>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
  </div>
</footer>
<?php endif; ?>

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
