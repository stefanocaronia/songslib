<?php

namespace App\Entity;

use App\Entity\Timestampable\TimestampableInterface;
use App\Entity\Timestampable\TimestampableTrait;
use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Inserire il titolo")
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $compositionYear;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="songs", cascade={"persist"})
     * @Assert\Count(min=1, minMessage="Inserire almeno un artista")
     */
    private $artists;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, inversedBy="songs", cascade={"persist"})
     * @Assert\Count(min=1, minMessage="Inserire almeno un album")
     * @Assert\NotBlank()
     */
    private $albums;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $single;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->albums = new ArrayCollection();
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

    public function getCompositionYear(): ?int
    {
        return $this->compositionYear;
    }

    public function setCompositionYear(?int $compositionYear): self
    {
        $this->compositionYear = $compositionYear;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->addSong($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addSong($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        $this->albums->removeElement($album);

        return $this;
    }

    public function getSingle(): ?bool
    {
        return $this->single;
    }

    public function setSingle(?bool $single): self
    {
        $this->single = $single;

        return $this;
    }
}
