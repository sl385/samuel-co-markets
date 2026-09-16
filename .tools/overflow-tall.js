const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  const fullHeight = await page.evaluate(() => document.documentElement.scrollHeight);
  await page.setViewportSize({ width: 1440, height: fullHeight });
  await page.waitForTimeout(300);
  console.log('scrollWidth:', await page.evaluate(() => document.documentElement.scrollWidth));
  console.log('clientWidth:', await page.evaluate(() => document.documentElement.clientWidth));
  const brand = await page.locator('.scm-brand').first().boundingBox();
  console.log('brand boundingBox:', brand);
  const header = await page.locator('.scm-header').first().boundingBox();
  console.log('header boundingBox:', header);
  await browser.close();
})();
