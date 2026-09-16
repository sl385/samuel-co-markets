<?php
/**
 * Funding programme tier card (spec C33, one column of the pricing matrix).
 * @dynamic — used by templates/template-funded-2025.php inside the real
 * "Get Qualified" / "Get Funded" ACF repeaters (get_qualified_services /
 * get_funded_services). The JS that swaps mode/balance and centres the
 * active card (inline <script> at the bottom of that template) selects on
 * `.matrix-service` + `[data-amount]` — both must stay on the root element.
 *
 * args:
 *   tag           — 'Get Qualified' | 'Get Funded'
 *   balance       — starting balance (number)
 *   cost_label    — 'Setup Cost' | 'Total Setup Cost'
 *   setup_fee     — number (this is the headline price in both real modes —
 *                   ported as-is from the original markup, not something to
 *                   "correct" here)
 *   note          — secondary pricing explainer line (wording genuinely
 *                   differs per mode in the real content)
 *   metrics       — [[label, tooltip, value], ...] (8 rows, from key_labels)
 *   product_id, requires_popup — Buy Now link + disclaimer gate
 */
$a = wp_parse_args($args ?? [], [
  'tag' => '', 'balance' => 0, 'cost_label' => 'Setup Cost',
  'setup_fee' => 0, 'note' => '', 'metrics' => [],
  'product_id' => 0, 'requires_popup' => false,
]);
?>
<article class="matrix-service" data-amount="<?php echo esc_attr($a['balance']); ?>">
  <div class="matrix-service__wrapper">
    <span class="scm-card-tag"><?php echo esc_html($a['tag']); ?></span>
    <h3>$<?php echo esc_html(number_format($a['balance'])); ?> Account</h3>
    <div class="matrix-service__price">
      <span class="matrix-service__cost-label"><?php echo esc_html($a['cost_label']); ?></span>
      <span class="matrix-service__cost">£<?php echo esc_html(number_format($a['setup_fee'])); ?></span>
      <?php if ($a['note']): ?><span class="matrix-service__note"><?php echo esc_html($a['note']); ?></span><?php endif; ?>
      <small>All prices exclude VAT</small>
    </div>
    <ul class="matrix-service__facts">
      <?php foreach ($a['metrics'] as [$label, $tooltip, $value]): ?>
        <li>
          <span>
            <?php echo esc_html($label); ?>
            <?php if ($tooltip): ?>
              <span class="tooltip" data-tooltip="<?php echo esc_attr($tooltip); ?>"><i class="fa-solid fa-circle-info" aria-hidden="true"></i></span>
            <?php endif; ?>
          </span>
          <b><?php echo esc_html($value); ?></b>
        </li>
      <?php endforeach; ?>
    </ul>
    <a class="scm-button scm-button--gold scm-button--block" href="?add-to-cart=<?php echo esc_attr($a['product_id']); ?>&quantity=1&buy_now=1"
      <?php if ($a['requires_popup']): ?>data-requiresPopUp="true"<?php endif; ?>>Buy Now</a>
  </div>
</article>
