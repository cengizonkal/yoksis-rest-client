<?php

namespace Conkal\YOKSIS\REST\Entities\Transkript;

use Conkal\YOKSIS\REST\Entities\Entity;


class TransferUniversite extends Entity
{

    /** @var TransferDers[] */
    public $transferDersler = [];
    /** @var string */
    public $transferUniversiteLabel;
    /** @var string */
    public $transferUniversiteLabelEng;
}