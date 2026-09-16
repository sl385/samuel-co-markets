// Dev-only helper, not part of the theme build. Usage:
//   node .tools/screenshot.js <url> <output-prefix> [width]
// Saves <output-prefix>-1.png, -2.png, ... one real 1440x900-ish viewport at a
// time by scrolling, instead of a single full-page capture. A single very-tall
// single-shot capture (both Chromium's fullPage:true stitching AND manually
// resizing the viewport to the page's full scrollHeight) reliably misrenders
// this site's header — content shifted/clipped on the left — even though the
// actual DOM layout is correct (verified via boundingBox(): x:0 as expected).
// Real users never see this; it's a capture-only artifact of very tall single
// shots. Segmented, normal-height captures don't have the problem.
const { chromium } = require('playwright');

(async () => {
  const [, , url, outPrefix, width] = process.argv;
  if (!url || !outPrefix) {
    console.error('Usage: node screenshot.js <url> <output-prefix> [width]');
    process.exit(1);
  }
  const w = Number(width) || 1440;
  const h = 900;
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: w, height: h }, ignoreHTTPSErrors: true });
  await page.goto(url, { waitUntil: 'load', timeout: 30000 });
  await page.evaluate(() => document.fonts.ready);
  await page.waitForTimeout(500);
  const gotIt = page.locator('text=Got it!');
  if (await gotIt.count()) await gotIt.click().catch(() => {});
  await page.waitForTimeout(300);

  const fullHeight = await page.evaluate(() => document.documentElement.scrollHeight);
  const shots = Math.max(1, Math.ceil(fullHeight / h));
  const saved = [];
  for (let i = 0; i < shots; i++) {
    await page.evaluate((y) => window.scrollTo(0, y), i * h);
    await page.waitForTimeout(150);
    const out = `${outPrefix}-${i + 1}.png`;
    await page.screenshot({ path: out, fullPage: false });
    saved.push(out);
  }
  await browser.close();
  console.log('Saved', saved.join(', '));
})().catch((e) => { console.error(e); process.exit(1); });
