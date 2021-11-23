<?php

namespace App\Entity;

use App\Entity\Timestampable\TimestampableInterface;
use App\Entity\Timestampable\TimestampableTrait;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album implements TimestampableInterface
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Inserire il titolo")
     * @Groups("ajax")
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $publicationYear;

    /**
     * @ORM\ManyToMany(targetEntity=Song::class, mappedBy="albums", cascade={"persist"})
     */
    private $songs;

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection|Artist[
     */
    public function getArtists(): Collection
    {
        $artists = new ArrayCollection();
        foreach ($this->songs as $song) {
            foreach ($song->getArtists() as $artist) {
                if (!$artists->contains($artist)) {
                    $artists->add($artist);
                }
            }
        }
        return $artists;
    }

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationYear(): ?int
    {
        return $this->publicationYear;
    }

    public function setPublicationYear(?int $publicationYear): self
    {
        $this->publicationYear = $publicationYear;

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
            $song->addAlbum($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            $song->removeAlbum($this);
        }

        return $this;
    }
}
