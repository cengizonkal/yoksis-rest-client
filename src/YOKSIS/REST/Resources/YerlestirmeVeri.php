<?php


namespace Conkal\YOKSIS\REST\Resources;


use Conkal\YOKSIS\REST\Resources\Traits\QueryTrait;

class YerlestirmeVeri extends ResourceAbstract
{
    use QueryTrait;

    protected $endPoint = 'yerlestirmeveri';
    protected $entity = \Conkal\YOKSIS\REST\Entities\YerlestirmeVeri::class;

}