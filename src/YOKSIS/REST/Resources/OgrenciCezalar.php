<?php


namespace Conkal\YOKSIS\REST\Resources;

use Conkal\YOKSIS\REST\Resources\Traits\AllTrait;
use Conkal\YOKSIS\REST\Resources\Traits\CreateTrait;
use Conkal\YOKSIS\REST\Resources\Traits\DeleteTrait;
use Conkal\YOKSIS\REST\Resources\Traits\FindTrait;
use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;
use Conkal\YOKSIS\REST\Resources\Traits\UpdateTrait;

/**
 * Ceza alan öğrenciler (ogrenciCezaGetir, ogrenciCezaListele, ogrenciCezaEkle, ogrenciCezaGuncelle, ogrenciCezaSil).
 *
 * @method \Conkal\YOKSIS\REST\Entities\OgrenciCeza[] all()
 * @method \Conkal\YOKSIS\REST\Entities\OgrenciCeza[] query(array $query) ör. ['tcKimlikNo' => '...']
 * @method \Conkal\YOKSIS\REST\Entities\OgrenciCeza|\Conkal\YOKSIS\REST\Entities\OgrenciCeza[]|null find($id)
 */
class OgrenciCezalar extends ResourceAbstract
{
    use AllTrait, QueryTrait, FindTrait, CreateTrait, UpdateTrait, DeleteTrait;

    protected $endPoint = 'ogrencicezalar';
    protected $entity = \Conkal\YOKSIS\REST\Entities\OgrenciCeza::class;
}
