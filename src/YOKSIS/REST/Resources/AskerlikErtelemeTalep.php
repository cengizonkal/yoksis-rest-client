<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\Constants\Askerlik\IslemSonucKodu;
use Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalepSonucu;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

/**
 * Askerlik erteleme ve erteleme iptal talepleri (ASAL).
 *
 * create() başarılı olursa {"talepKayitUid": ..., "islemSonucu": {...}} döndürür.
 * delete($talepKayitUid) talep askerlik şubesine gönderilmediyse siler; gönderildiyse
 * islemSonucKodu EDV09.010 (SILMEBASARISIZ) döner.
 *
 * @method \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep[] query(array $query)
 */
class AskerlikErtelemeTalep extends ResourceAbstract
{
    use CreateTrait, QueryTrait, DeleteTrait;

    protected $endPoint = 'askerlikErtelemeTalep';
    protected $entity = \Conkal\YOKSIS\REST\Entities\AskerlikErtelemeTalep::class;

    /**
     * Daha önce yapılan talebin durumunu sorgular. Talepler 3-15 günde sonuçlanır;
     * YÖK bu sorgunun günde en fazla bir kez yapılmasını önerir.
     *
     * Talep henüz sonuçlanmadıysa servis HTTP 206 döndürür; bu durumda dönen nesnenin
     * beklemedeMi() metodu true olur.
     *
     * @param string $talepKayitUid
     * @return AskerlikErtelemeTalepSonucu
     * @throws \Conkal\YOKSIS\REST\Exceptions\NotFoundException Talep ASAL'da bulunamazsa (EDV09.006)
     */
    public function sonuc($talepKayitUid)
    {
        $response = $this->client->send($this->path($talepKayitUid));
        $body = trim((string)$response->getBody());
        $decoded = $body === '' ? null : json_decode($body);

        $sonuc = new AskerlikErtelemeTalepSonucu(is_object($decoded) ? $decoded : null);
        $sonuc->talepKayitUid = $talepKayitUid;

        if (!isset($sonuc->islemSonucu) && $response->getStatusCode() === 206) {
            $mesaj = is_string($decoded) ? $decoded : $body;
            $sonuc->islemSonucu = (object)[
                'islemSonucKodu' => IslemSonucKodu::BEKLEMEDE,
                'aciklama' => $mesaj,
            ];
        }
        return $sonuc;
    }
}
