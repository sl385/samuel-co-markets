// Dev-only helper. Usage: node .tools/crop.js <url> <css-selector> <output.png>
const { chromium } = require('playwright');
(async () => {
  const [, , url, selector, out] = process.argv;
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(url, { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(600);
  const gotIt = page.locator('text=Got it!');
  if (await gotIt.count()) await gotIt.click().catch(() => {});
  await page.locator(selector).first().screenshot({ path: out });
  await browser.close();
})().catch((e) => { console.error(e); process.exit(1); });
