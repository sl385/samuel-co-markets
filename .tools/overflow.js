const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  console.log('document.documentElement.scrollWidth:', await page.evaluate(() => document.documentElement.scrollWidth));
  console.log('window.scrollX:', await page.evaluate(() => window.scrollX));
  // find widest offenders
  const wide = await page.evaluate(() => {
    const vw = document.documentElement.clientWidth;
    const out = [];
    document.querySelectorAll('*').forEach(el => {
      const r = el.getBoundingClientRect();
      if (r.right > vw + 2 || r.left < -2) {
        out.push({ tag: el.tagName, cls: el.className.toString().slice(0,60), left: Math.round(r.left), right: Math.round(r.right) });
      }
    });
    return out.slice(0, 15);
  });
  console.log(JSON.stringify(wide, null, 2));
  await browser.close();
})();
