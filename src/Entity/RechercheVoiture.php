<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;


class RechercheVoiture{
    /**
     * @Assert\LessThanOrEqual(propertyPath="maxAnnee", message="doit être plus petit ou égale que l'année max")
     */
    private $minAnnee;

    /**
     * @Assert\GreaterThanOrEqual(propertyPath="minAnnee", message="doit être plus grand ou égale que l'année max")
     */

    private $maxAnnee;

    public function getMinAnnee(){
        return $this->minAnnee;
    }

    public function getMaxAnnee(){
        return $this->maxAnnee;
    }

    public function setMinAnnee($minAnnee){
        $this->minAnnee = $minAnnee;
        return $this;
    }

    public function setMaxAnnee($maxAnnee){
        $this->maxAnnee = $maxAnnee;
        return $this;
    }
}