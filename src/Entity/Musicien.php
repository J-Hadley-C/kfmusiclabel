<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Musicien extends Artist
{
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $instrument = null;

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(?string $instrument): self
    {
        $this->instrument = $instrument;
        return $this;
    }
}
