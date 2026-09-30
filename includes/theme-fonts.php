<?php
/**
 * Display font switcher (30 Sep 2026).
 *
 * The display face is a token (--font-display). This file lets it be chosen:
 *   1. ?font=<key> on any URL (preview; anyone can use it, nothing is saved)
 *   2. S&Co Settings → Design → Display font (ACF option; the real choice)
 *   3. default: source-serif
 * The chosen Google Fonts stylesheet is printed in <head> and --font-display
 * is overridden inline. Editors also get a small floating switcher on the
 * front end (bottom-left) to flick between options on the page they're on.
 * Elza (body sans, Typekit) is unchanged.
 */
if (!defined('ABSPATH')) exit;

function scm_display_fonts() {
    return [
        'source-serif' => ['label' => 'Source Serif 4 (current)', 'stack' => "'Source Serif 4', Georgia, serif", 'google' => 'Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600', 'note' => 'Transitional, crisp. The square-editorial default.'],
        'fraunces'     => ['label' => 'Fraunces', 'stack' => "'Fraunces', Georgia, serif", 'google' => 'Fraunces:opsz,wght,SOFT@9..144,400,100;9..144,500,100', 'note' => 'Soft, warm, slightly quirky. Closest to Finimize.'],
        'newsreader'   => ['label' => 'Newsreader', 'stack' => "'Newsreader', Georgia, serif", 'google' => 'Newsreader:opsz,wght@6..72,400;6..72,500', 'note' => 'Gentle editorial serif, newspaper feel without the sharpness.'],
        'lora'         => ['label' => 'Lora', 'stack' => "'Lora', Georgia, serif", 'google' => 'Lora:wght@400;500;600', 'note' => 'Rounded, friendly, very readable at card sizes.'],
        'literata'     => ['label' => 'Literata', 'stack' => "'Literata', Georgia, serif", 'google' => 'Literata:opsz,wght@7..72,400;7..72,500', 'note' => 'Bookish, soft terminals, calm.'],
        'instrument'   => ['label' => 'Instrument Serif', 'stack' => "'Instrument Serif', Georgia, serif", 'google' => 'Instrument+Serif', 'note' => 'Light, refined, single weight. Elegant at large sizes only.'],
        'dm-sans'      => ['label' => 'DM Sans (sans headings)', 'stack' => "'DM Sans', elza, Arial, sans-serif", 'google' => 'DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600', 'note' => 'Rounded geometric sans. The Quiver Quant direction: no serif at all.'],
        'jakarta'      => ['label' => 'Plus Jakarta Sans (sans headings)', 'stack' => "'Plus Jakarta Sans', elza, Arial, sans-serif", 'google' => 'Plus+Jakarta+Sans:wght@400;500;600', 'note' => 'Soft, modern sans. Fintech-app feel.'],
    ];
}

function scm_display_font_key() {
    $fonts = scm_display_fonts();
    if (!empty($_GET['font'])) {
        $k = sanitize_key($_GET['font']);
        if (isset($fonts[$k])) return $k;
    }
    $k = function_exists('get_field') ? (string) get_field('scm_display_font', 'option') : '';
    return isset($fonts[$k]) ? $k : 'source-serif';
}

add_action('wp_head', function () {
    $fonts = scm_display_fonts();
    $key = scm_display_font_key();
    $f = $fonts[$key];
    echo "\n" . '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="stylesheet" href="' . esc_url('https://fonts.googleapis.com/css2?family=' . $f['google'] . '&display=swap') . '">' . "\n";
}, 5);

// The token override must print AFTER the enqueued stylesheet (wp_print_styles
// runs at wp_head priority 8), otherwise _tokens.scss's :root wins on source order.
add_action('wp_head', function () {
    $fonts = scm_display_fonts();
    $key = scm_display_font_key();
    $f = $fonts[$key];
    echo '<style id="scm-display-font">:root{--font-display:' . $f['stack'] . ';}' . ($key === 'fraunces' ? ':root{--display-weight:500;}' : '') . '</style>' . "\n";
}, 99);

// Editor-only floating switcher.
add_action('wp_footer', function () {
    if (!current_user_can('edit_theme_options') || is_admin()) return;
    $fonts = scm_display_fonts(); $key = scm_display_font_key();
    $saved = function_exists('get_field') ? (string) get_field('scm_display_font', 'option') : '';
    ?>
    <div class="scm-font-switch" id="scm-font-switch">
      <label>Display font
        <select onchange="var u=new URL(location.href);u.searchParams.set('font',this.value);location.href=u;">
          <?php foreach ($fonts as $k => $f): ?><option value="<?php echo esc_attr($k); ?>"<?php selected($k, $key); ?>><?php echo esc_html($f['label']); ?><?php echo $k === ($saved ?: 'source-serif') ? ' ✓ saved' : ''; ?></option><?php endforeach; ?>
        </select>
      </label>
      <small><?php echo esc_html($fonts[$key]['note']); ?> Save the choice in S&amp;Co Settings → Design.</small>
      <button type="button" onclick="this.parentNode.remove()" aria-label="Close">×</button>
    </div>
    <?php
});
