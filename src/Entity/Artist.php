<?php

namespace App\Entity;

use App\Entity\Timestampable\TimestampableInterface;
use App\Entity\Timestampable\TimestampableTrait;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("ajax")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Inserire il nome")
     * @Groups("ajax")
     */
    private $name;

    /**
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\GenderKindType")
     * @ORM\Column(type="GenderKindType", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\ManyToMany(targetEntity=Song::class, mappedBy="artists", cascade={"persist"})
     */
    private $songs;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->addArtist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            $song->removeArtist($this);
        }

        return $this;
    }
}
