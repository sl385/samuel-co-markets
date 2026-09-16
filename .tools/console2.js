const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  page.on('response', r => { if (r.url().includes('recaptcha') || r.url().includes('gstatic')) console.log(r.status(), r.url()); });
  page.on('pageerror', err => console.log('PAGEERROR', err.message));
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(3000);
  const disabled = await page.locator('.scm-prefooter input[type=submit]').first().getAttribute('disabled');
  console.log('still disabled after 3s:', disabled !== null);
  const hasGrecaptcha = await page.evaluate(() => typeof window.grecaptcha !== 'undefined');
  console.log('grecaptcha defined:', hasGrecaptcha);
  await browser.close();
})();
