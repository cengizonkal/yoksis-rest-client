<?php

namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\OgrenciTranskript;
use PHPUnit\Framework\TestCase;

class OgrenciTranskriptTest extends TestCase
{

    private $client;

    public function setUp()
    {
        $pass = getenv('YOKSIS_PASSWORD');
        $user = getenv('YOKSIS_USERNAME');
        $auth = new \Conkal\YOKSIS\REST\Utilities\BasicAuth($user, $pass);
        $this->client = new \Conkal\YOKSIS\REST\YOK('https://servisler.yok.gov.tr/resttest/obs/');
        $this->client->setAuth($auth);
    }

    public function test_it_should_create_a_transcript()
    {

        $transkript = new \Conkal\YOKSIS\REST\Entities\Transkript\OgrenciTranskript();

        $transkript->ogrenciId = '123456';
        $transkript->tcKimlikNo = getenv('TEST_TCKNO');
        $transkript->birimId = '123456';

        $transkript->donemler = [
            new \Conkal\YOKSIS\REST\Entities\Transkript\Donem([
                'donemYili' => '2018-2019',
                'donemNumarasi' => '1',
                'donemNotOrtalamasi' => '3.5',
                'genelNotOrtalamasi' => '3.5',
                'toplamKredi' => '120',
                'toplamAKTS' => '120',
                'donemSonuDurumu' => '1',
                'dersler' => [
                    new \Conkal\YOKSIS\REST\Entities\Transkript\Ders([
                        'dersinKodu' => 'MAT101',
                        'dersinAdi' => 'Matematik',
                        'ortalamayaDahilmi' => '1',
                        'dersinStatusu' => '1',
                        'ogretimDili' => 'TR',
                        'dersAlinmaTuru' => '1',
                        'teorikSaati' => '3',
                        'uygulamaSaati' => '2',
                        'yerel' => '0',
                        'akts' => '5',
                        'not' => 'AA',
                        'hamBasariNotu' => '4.0',
                        'puan' => '100',
                        'aciklama' => 'Dersi başarıyla tamamladı',
                        'dersinAmaci' => 'Matematik öğrenmek',
                        'dersinIcerigi' => 'Matematik',
                        'dersDonemi' => '1',
                        'dersGecmeDurumu' => '1',
                        'digerDilDers' => '0',
                    ]),

                ]
            ])
        ];

        $transkript->notBaremleri = [
            "notBaremi"=>'AA',
            "notBaremiEng"=>'Excellent',
            "not" => [
                new \Conkal\YOKSIS\REST\Entities\Transkript\NotDTO([
                    'aciklama' => 'AA',
                    'aciklamaEng' => 'Mükemmel',
                    'katsayi' => '4',
                    'not' => '100',
                    'puan' => '4.0',
                ])
            ]
        ];


        var_dump($transkript->toArray());

        $this->client->ogrenciTranskript()->create($transkript);

    }

    public function test_it_should_get_transkript()
    {
        $transkript = $this->client->ogrenciTranskript()->find(123456);
        var_dump($transkript);
        $this->assertNotNull($transkript);
    }

}