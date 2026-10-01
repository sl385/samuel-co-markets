<?php
/**
 * Beehiiv integration for the Morning Brief signup (1 Oct 2026).
 *
 * Server-side relay: the native form (partials/components/newsletter-form.php)
 * posts to REST `scm/v1/subscribe`; this file validates, rate-limits and calls
 * Beehiiv's Subscriptions API (POST /v2/publications/{id}/subscriptions). The
 * API key never reaches the browser.
 *
 * Config, in order of precedence:
 *   wp-config.php constants  BEEHIIV_API_KEY, BEEHIIV_PUBLICATION_ID   (preferred)
 *   S&Co Settings → Integrations (ACF options)   beehiiv_api_key, beehiiv_publication_id
 * Plus options: welcome email on/off, UTM source, success copy.
 *
 * Beehiiv docs: https://developers.beehiiv.com/api-reference/subscriptions/create
 */
if (!defined('ABSPATH')) exit;

function scm_beehiiv_setting($name, $default = '') {
    $const = 'BEEHIIV_' . strtoupper($name);
    if (defined($const) && constant($const) !== '') return constant($const);
    return scm_opt('beehiiv_' . $name, $default);
}

function scm_beehiiv_configured() {
    return (bool) (scm_beehiiv_setting('api_key') && scm_beehiiv_setting('publication_id'));
}

/** Create (or reactivate) a subscription. Returns true or WP_Error. */
function scm_beehiiv_subscribe($email, array $args = []) {
    if (!scm_beehiiv_configured()) return new WP_Error('scm_bh_unconfigured', 'Signup is not configured yet.');
    $pub = scm_beehiiv_setting('publication_id');
    $body = [
        'email' => $email,
        'reactivate_existing' => true,
        'send_welcome_email' => (bool) scm_beehiiv_setting('welcome_email', true),
        'utm_source' => sanitize_text_field($args['source'] ?? scm_beehiiv_setting('utm_source', 'website')),
        'utm_medium' => 'organic',
        'referring_site' => home_url('/'),
    ];
    if (!empty($args['utm_campaign'])) $body['utm_campaign'] = sanitize_text_field($args['utm_campaign']);
    $res = wp_remote_post('https://api.beehiiv.com/v2/publications/' . rawurlencode($pub) . '/subscriptions', [
        'timeout' => 12,
        'headers' => ['Authorization' => 'Bearer ' . scm_beehiiv_setting('api_key'), 'Content-Type' => 'application/json', 'Accept' => 'application/json'],
        'body' => wp_json_encode($body),
    ]);
    if (is_wp_error($res)) return new WP_Error('scm_bh_network', 'Could not reach the newsletter service. Please try again.');
    $code = wp_remote_retrieve_response_code($res);
    $data = json_decode(wp_remote_retrieve_body($res), true);
    if ($code >= 200 && $code < 300) return true;
    $msg = $data['errors'][0]['message'] ?? $data['message'] ?? 'The newsletter service rejected the request.';
    error_log('[scm beehiiv] HTTP ' . $code . ' ' . $msg); // no email in the log
    return new WP_Error('scm_bh_api', $code === 400 ? 'That email address doesn\'t look right.' : 'Something went wrong. Please try again in a moment.');
}

/* REST: POST /wp-json/scm/v1/subscribe  {email, consent, source, website(honeypot)} */
add_action('rest_api_init', function () {
    register_rest_route('scm/v1', '/subscribe', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'args' => [
            'email'   => ['required' => true, 'sanitize_callback' => 'sanitize_email'],
            'consent' => ['required' => false],
            'source'  => ['required' => false, 'sanitize_callback' => 'sanitize_text_field'],
            'website' => ['required' => false], // honeypot — must be empty
        ],
        'callback' => function (WP_REST_Request $r) {
            if ($r->get_param('website') !== null && $r->get_param('website') !== '') {
                return new WP_REST_Response(['ok' => true], 200); // silently drop bots
            }
            $email = $r->get_param('email');
            if (!is_email($email)) return new WP_REST_Response(['ok' => false, 'message' => 'Please enter a valid email address.'], 400);
            if (!filter_var($r->get_param('consent'), FILTER_VALIDATE_BOOLEAN)) {
                return new WP_REST_Response(['ok' => false, 'message' => 'Please tick the consent box.'], 400);
            }
            // Rate limit: 5 per 10 minutes per IP.
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
            $key = 'scm_bh_' . md5(explode(',', $ip)[0]);
            $n = (int) get_transient($key);
            if ($n >= 5) return new WP_REST_Response(['ok' => false, 'message' => 'Too many attempts. Please try again later.'], 429);
            set_transient($key, $n + 1, 10 * MINUTE_IN_SECONDS);

            $result = scm_beehiiv_subscribe($email, ['source' => $r->get_param('source') ?: null]);
            if (is_wp_error($result)) {
                $status = $result->get_error_code() === 'scm_bh_unconfigured' ? 503 : 502;
                return new WP_REST_Response(['ok' => false, 'message' => $result->get_error_message()], $status);
            }
            return new WP_REST_Response(['ok' => true, 'message' => scm_beehiiv_setting('success_text', 'Look out for your confirmation email.')], 200);
        },
    ]);
});

/* Expose the endpoint to the front end. */
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('scm-main', 'scmBeehiiv', ['endpoint' => esc_url_raw(rest_url('scm/v1/subscribe')), 'configured' => scm_beehiiv_configured()]);
}, 101);
