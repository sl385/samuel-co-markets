const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  const el = page.locator(process.argv[3]).first();
  console.log('color:', await el.evaluate(e => getComputedStyle(e).color));
  console.log('--ink on el:', await el.evaluate(e => getComputedStyle(e).getPropertyValue('--ink')));
  console.log('--ink on parent .scm-on-dark:', await el.evaluate(e => getComputedStyle(e.closest('.scm-on-dark')).getPropertyValue('--ink')));
  await browser.close();
})();
