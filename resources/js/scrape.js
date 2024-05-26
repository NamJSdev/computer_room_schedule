const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();

    // Mở trang web
    await page.goto('https://daotao.vnua.edu.vn/Default.aspx?page=thoikhoabieu&load=all');

    // Chọn học kỳ từ dropdown
    // await page.select('select[name="hoc_ky"]', '2024'); // Thay '2024' bằng giá trị học kỳ cần chọn

    // Submit form
    await page.click('button[type="submit"]');
    await page.waitForNavigation();

    // Lấy toàn bộ mã HTML của trang
    const html = await page.content();

    console.log(html);
    await browser.close();
})();
