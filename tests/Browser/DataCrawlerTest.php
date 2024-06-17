<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Symfony\Component\DomCrawler\Crawler;

class DataCrawlerTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testCrawler()
    {
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('https://daotao.vnua.edu.vn/Default.aspx?page=thoikhoabieu&load=all')
                    ->pause(2000) // Chờ 2 giây để trang load hoàn toàn
                    ->select('ctl00$ContentPlaceHolder1$ctl00$ddlChonNHHK', '20232')
                    ->pause(2000)
                    ->select('ctl00$ContentPlaceHolder1$ctl00$ddlChon', 'k')
                    ->pause(2000)
                    ->select('ctl00$ContentPlaceHolder1$ctl00$ddlHienThiKQ', 'TH')
                    ->press('ctl00$ContentPlaceHolder1$ctl00$bntLocTKB');

                // Kiểm tra xem truy cập có bị từ chối không
                // if ($browser->driver->getStatusCode() === 403) {
                //     throw new \Exception('Truy cập bị từ chối.');
                // }

                // Lấy nội dung HTML của trang
                $html = $browser->driver->getPageSource();

                // Sử dụng Symfony DomCrawler để trích xuất các bảng thỏa mãn điều kiện
                $crawler = new Crawler($html);
                $tables = $crawler->filter('table[width="100%"]')->each(function (Crawler $node) {
                    // Kiểm tra xem bảng có chứa td dữ liệu chứa ký tự "CNTT-ND" không
                    if ($node->filter('td:contains("CNTT-ND")')->count() > 0) {
                        return $node->outerHtml();
                    }
                    return null;
                });

                // Loại bỏ các phần tử null khỏi mảng
                $tables = array_filter($tables);

                // Kiểm tra xem có bảng nào được tìm thấy hay không
                if (empty($tables)) {
                    throw new \Exception('Không tìm thấy bảng nào thỏa mãn điều kiện.');
                }

                // Ghi các bảng vào tệp HTML
                file_put_contents(storage_path('app/public/crawled_page.html'), implode("\n", $tables));
            } catch (\Exception $e) {
                // Xử lý ngoại lệ khi truy cập bị từ chối
                echo $e->getMessage();
            }
        });
    }
}