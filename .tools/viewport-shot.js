const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  await page.screenshot({ path: process.argv[3], fullPage: false });
  await browser.close();
})();
