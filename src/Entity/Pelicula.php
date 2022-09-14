<?php

namespace App\Entity;

use App\Repository\PeliculaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeliculaRepository::class)]
class Pelicula
{
    const TIPOS = [ 'Accion' => 'Accion',
                    'Humor' => 'Humor',
                    'CienciaFiccion' => 'CienciaFiccion',
                    'Drama' => 'Drama'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2,max: 50,minMessage: 'Your first name must be at least {{ limit }} characters long',maxMessage: 'Your first name cannot be longer than {{ limit }} characters',)]
    private $titulo;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private $tipo;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private $descripcion;

    #[ORM\Column(length: 255, nullable: true)]
    private $foto;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $fecha_alta;

    #[ORM\Column(length: 255)]
    private $url;

    #[ORM\ManyToOne(inversedBy: 'peliculas')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'peliculas')]
    private Collection $actores;


    public function __construct()
    {
        $this->actores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fecha_alta;
    }

    public function setFechaAlta(\DateTimeInterface $fecha_alta): self
    {
        $this->fecha_alta = $fecha_alta;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActores(): Collection
    {
        return $this->actores;
    }

    public function addActore(Actor $actore): self
    {
        if (!$this->actores->contains($actore)) {
            $this->actores->add($actore);
        }

        return $this;
    }

    public function removeActore(Actor $actore): self
    {
        $this->actores->removeElement($actore);

        return $this;
    }
}
