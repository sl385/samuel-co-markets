const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(500);
  const rules = await page.evaluate((sel) => {
    const el = document.querySelector(sel);
    const out = [];
    for (const sheet of document.styleSheets) {
      let cssRules;
      try { cssRules = sheet.cssRules; } catch (e) { continue; }
      for (const rule of cssRules) {
        if (rule.selectorText) {
          try {
            if (el.matches(rule.selectorText) && /width|height/.test(rule.style.cssText)) {
              out.push(rule.selectorText + ' { ' + rule.style.cssText + ' }');
            }
          } catch (e) {}
        }
      }
    }
    return out;
  }, process.argv[3]);
  console.log(rules.join('\n'));
  await browser.close();
})();
