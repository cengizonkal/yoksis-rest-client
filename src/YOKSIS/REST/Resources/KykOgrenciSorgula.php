<?php

namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Entities\KykOgrenciSayi;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Kredi ve Yurtlar Kurumu (KYK) sorguları.
 *
 * @method \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula[] query(array $query) ör. ['tcKimlikNo' => '...']
 */
class KykOgrenciSorgula extends ResourceAbstract
{
    use QueryTrait;

    /**
     * Toplu sorguda tek istekte gönderilebilecek en fazla kişi sayısı.
     */
    const TOPLU_SORGU_LIMITI = 100;

    protected $endPoint = 'kykogrencisorgula';
    protected $entity = \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula::class;

    /**
     * Öğrencinin KYK burs/kredi ve yurt barınma bilgisini döndürür.
     *
     * @param string|int $tcKimlikNo
     * @return \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula|null
     */
    public function sorgula($tcKimlikNo)
    {
        $items = $this->query(['tcKimlikNo' => $tcKimlikNo]);
        return $items ? $items[0] : null;
    }

    /**
     * Birden fazla öğrenciyi sorgular (kykogrencitoplusorgula). Servis istek başına en fazla
     * 100 kişi kabul ettiği için liste gerektiğinde parçalara bölünür.
     *
     * @param array $tcKimlikNolar
     * @return \Conkal\YOKSIS\REST\Entities\KykOgrenciSorgula[]
     */
    public function topluSorgula(array $tcKimlikNolar)
    {
        $sonuc = [];
        foreach (array_chunk(array_values($tcKimlikNolar), self::TOPLU_SORGU_LIMITI) as $parca) {
            $json = array_map(function ($tc) {
                // Servis T.C. kimlik numaralarını sayı olarak bekler; 32 bit sistemlerde taşmamak için metin kalır.
                return PHP_INT_SIZE >= 8 && ctype_digit((string)$tc) ? (int)$tc : $tc;
            }, $parca);
            $response = $this->request('kykogrencitoplusorgula', ['method' => 'POST', 'json' => $json]);
            foreach ($this->hydrateMany($response) as $item) {
                $sonuc[] = $item;
            }
        }
        return $sonuc;
    }

    /**
     * Birimde öğrenim kredisi ve burs alan öğrenci sayılarını döndürür (kykogrencisayisorgula).
     *
     * @param int|string $birimId
     * @return KykOgrenciSayi[]
     */
    public function ogrenciSayisi($birimId)
    {
        return $this->hydrateManyAs(
            $this->request('kykogrencisayisorgula', ['query' => ['birimId' => $birimId]]),
            KykOgrenciSayi::class
        );
    }

    /**
     * Yıl bazlı öğrenim kredisi ve burs alan öğrenci sayıları (kykogrencisayisorgulabyyil).
     *
     * @param int|string $birimId
     * @param int|string $birimTur
     * @param int $yil 2020 ve sonrası
     * @return KykOgrenciSayi[]
     */
    public function ogrenciSayisiYilaGore($birimId, $birimTur, $yil)
    {
        return $this->hydrateManyAs(
            $this->request('kykogrencisayisorgulabyyil', [
                'query' => ['birimId' => $birimId, 'birimTur' => $birimTur, 'yil' => (int)$yil],
            ]),
            KykOgrenciSayi::class
        );
    }
}
