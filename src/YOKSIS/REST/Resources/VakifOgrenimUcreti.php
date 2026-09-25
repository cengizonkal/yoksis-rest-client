<?php


namespace Conkal\YOKSIS\REST\Resources;

/**
 * Vakıf üniversitelerinde öğrenim ücreti ödeme bilgisi (vakifogrenimucreti).
 */
class VakifOgrenimUcreti extends ResourceAbstract
{
    protected $endPoint = 'vakifogrenimucreti';

    /**
     * Öğrencinin öğrenim ücretini ödeyip ödemediğini bildirir.
     *
     * Dokümanda parametreler hem sorgu dizesinde hem de JSON gövdede gösterildiği için
     * ikisiyle birlikte gönderilir.
     *
     * @param string|int $tcKimlikNo
     * @param bool $odendiMi
     * @param int $yil
     * @param string $tur Constants\DonemTuru değerlerinden biri (YKS, DGS, ...)
     * @return mixed Servisin döndürdüğü yanıt
     */
    public function bildir($tcKimlikNo, $odendiMi, $yil, $tur)
    {
        $params = [
            'tcKimlikNo' => (string)$tcKimlikNo,
            'ogrenimUcretiOdendiMi' => $odendiMi ? 'true' : 'false',
            'yil' => (int)$yil,
            'tur' => (string)$tur,
        ];

        return $this->request($this->endPoint, ['method' => 'POST', 'query' => $params, 'json' => $params]);
    }
}
