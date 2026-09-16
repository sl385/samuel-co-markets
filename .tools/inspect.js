const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  const errors = [];
  page.on('requestfailed', r => errors.push(r.url() + ' -> ' + (r.failure()?.errorText)));
  page.on('response', r => { if (r.url().includes('gravatar')) console.log('gravatar response', r.status(), r.url()); });
  await page.goto(process.argv[2], { waitUntil: 'load', timeout: 30000 });
  await page.waitForTimeout(1000);
  const info = await page.locator('.scm-byline--on-dark img.avatar').evaluate(el => ({
    naturalWidth: el.naturalWidth, naturalHeight: el.naturalHeight,
    clientWidth: el.clientWidth, clientHeight: el.clientHeight,
    computedWidth: getComputedStyle(el).width, computedHeight: getComputedStyle(el).height,
    complete: el.complete, currentSrc: el.currentSrc,
  }));
  console.log(JSON.stringify(info, null, 2));
  console.log('failed requests:', errors.filter(e => e.includes('gravatar')));
  await browser.close();
})();
