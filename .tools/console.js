const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  page.on('console', msg => console.log('CONSOLE', msg.type(), msg.text()));
  page.on('pageerror', err => console.log('PAGEERROR', err.message));
  page.on('requestfailed', r => console.log('REQFAIL', r.url(), r.failure()?.errorText));
  page.on('response', r => { if (r.status() >= 400) console.log('HTTP', r.status(), r.url()); });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(2000);
  const disabled = await page.locator('.scm-prefooter input[type=submit]').first().getAttribute('disabled');
  console.log('still disabled after 2s:', disabled !== null);
  await browser.close();
})();
