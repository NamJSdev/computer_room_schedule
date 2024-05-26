<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class ScrapeSchedule extends Command
{
    protected $signature = 'scrape:schedule';
    protected $description = 'Scrape schedule data from website';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // $client = Client::createChromeClient();
        $client = PantherTestCase::createPantherClient();
        $crawler = $client->request('GET', 'https://daotao.vnua.edu.vn/Default.aspx?page=thoikhoabieu&load=all');
        $crawler = $client->waitFor('#ctl00_ContentPlaceHolder1_ctl00_pnlHeader')->filter('ctl00_ContentPlaceHolder1_ctl00_pnlHeader');

        // Chọn học kỳ từ dropdown và submit form
        $form = $crawler->selectButton('Submit')->form();
        // $form['hoc_ky'] = '2024';
        $crawler = $client->submit($form);

        // Lấy dữ liệu từ bảng thời khóa biểu
        $html = $crawler->html();

        // In ra HTML
        $this->info($html);

        return Command::SUCCESS;
    }
}