const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(1500);
  const form = page.locator('.scm-prefooter form.wpcf7-form');
  await form.locator('input[type=email]').fill('test@example.com');
  await form.locator('input[type=checkbox]').check();
  await form.locator('input[type=submit]').click();
  await page.waitForTimeout(3000);
  console.log('form classes:', await form.getAttribute('class'));
  console.log('response text:', await page.locator('.scm-prefooter .wpcf7-response-output').textContent().catch(() => '(none)'));
  await browser.close();
})();
