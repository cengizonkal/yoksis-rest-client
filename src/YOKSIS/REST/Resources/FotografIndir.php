<?php


namespace Conkal\YOKSIS\REST\Resources;


class FotografIndir extends ResourceAbstract
{
    protected $endPoint = 'fotografindir';

    /**
     * Öğrencinin fotoğrafını ham HTTP yanıtı olarak döndürür.
     *
     * @param string $tcKimlikNo
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find($tcKimlikNo)
    {
        return $this->client->send($this->endPoint, ['query' => ['tcKimlikNo' => $tcKimlikNo]]);
    }

    /**
     * Öğrencinin fotoğrafını dosyaya kaydeder.
     *
     * @param string $tcKimlikNo
     * @param string $path
     * @return int Yazılan bayt sayısı
     */
    public function save($tcKimlikNo, $path)
    {
        $written = file_put_contents($path, (string)$this->find($tcKimlikNo)->getBody());
        if ($written === false) {
            throw new \RuntimeException(sprintf('Fotoğraf "%s" dosyasına yazılamadı.', $path));
        }
        return $written;
    }

    /**
     * Belirtilen yıl ve türdeki tüm yerleşen öğrencilerin fotoğraflarını zip olarak döndürür.
     * Büyük olabileceği için doğrudan dosyaya yazmak isterseniz topluKaydet() kullanın.
     *
     * @param int $yil
     * @param string $tur Constants\DonemTuru değerlerinden biri (YKS, DGS, ...)
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function toplu($yil, $tur)
    {
        return $this->client->send('toplufotografindir', ['query' => ['yil' => (int)$yil, 'tur' => (string)$tur]]);
    }

    /**
     * Toplu fotoğraf zip dosyasını belleğe almadan doğrudan diske yazar.
     *
     * @param int $yil
     * @param string $tur
     * @param string $path Kaydedilecek .zip dosyasının yolu
     * @return string Kaydedilen dosyanın yolu
     */
    public function topluKaydet($yil, $tur, $path)
    {
        $this->client->send('toplufotografindir', [
            'query' => ['yil' => (int)$yil, 'tur' => (string)$tur],
            'sink' => $path,
        ]);
        return $path;
    }
}
