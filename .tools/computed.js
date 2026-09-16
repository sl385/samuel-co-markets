const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  const el = page.locator(process.argv[3]).first();
  console.log('outerHTML:', await el.evaluate(e => e.outerHTML));
  console.log('computed bg:', await el.evaluate(e => getComputedStyle(e).backgroundColor));
  console.log('computed radius:', await el.evaluate(e => getComputedStyle(e).borderRadius));
  console.log('inline style attr:', await el.evaluate(e => e.getAttribute('style')));
  await browser.close();
})();
