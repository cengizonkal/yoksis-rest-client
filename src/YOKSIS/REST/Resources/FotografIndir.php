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
}
