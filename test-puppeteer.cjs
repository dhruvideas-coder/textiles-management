const puppeteer = require('puppeteer');
(async () => {
  try {
    const browser = await puppeteer.launch({ args: ['--no-sandbox'] });
    const page = await browser.newPage();
    await page.setContent('<h1>Test</h1>');
    await page.pdf({ path: 'test.pdf' });
    await browser.close();
    console.log('Success');
  } catch (err) {
    console.error(err);
    process.exit(1);
  }
})();
