<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;

use Conkal\YOKSIS\REST\Entities\Entity;


class NotBaremi extends Entity
{

    /** @var NotDTO[] */
    public $not = [];

    /** @var string */
    public $notBaremi;
    
    /** @var string */
    public $notBaremiEng;
}