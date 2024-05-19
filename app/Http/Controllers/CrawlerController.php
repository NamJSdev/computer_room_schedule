<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
    public function index()
    {
        // Tạo một client của Guzzle HTTP
        $client = new Client();

        $url = 'https://daotao.vnua.edu.vn/Default.aspx?page=thoikhoabieu&load=all';

        // Gửi yêu cầu GET đến trang web và lấy nội dung HTML
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();
        
        // Tạo một đối tượng Crawler với base URI là URL tuyệt đối
        $crawler = new Crawler($html, $url);
        
        // Chọn select bằng name
        $selectNode = $crawler->filter('select#ctl00_ContentPlaceHolder1_ctl00_ddlChonNHHK')->first();
        
        // Chọn option có giá trị là "20233"
        $optionNode = $selectNode->filter('option[value="20233"]');
        
        // Làm cho option được chọn
        $optionNode->getNode(0)->setAttribute('selected', 'selected');

        // Lấy form và submit
        $form = $crawler->selectButton('ctl00$ContentPlaceHolder1$ctl00$bntLocTKB')->form();
        $crawler = $client->request('POST', $url, [
            'form_params' => $form->getValues(),
        ]);
        
        // Lấy nội dung HTML sau khi đã chọn option
        $newHtml = $crawler->getBody()->getContents();
        
        // Hiển thị nội dung HTML sau khi đã chọn option
        echo $newHtml;
    }
}