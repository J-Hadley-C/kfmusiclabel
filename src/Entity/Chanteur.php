<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Chanteur extends Artist
{
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $styleVocal = null;

    public function getStyleVocal(): ?string
    {
        return $this->styleVocal;
    }

    public function setStyleVocal(?string $styleVocal): self
    {
        $this->styleVocal = $styleVocal;
        return $this;
    }
}
