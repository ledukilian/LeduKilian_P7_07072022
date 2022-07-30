<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "getProduct",
 *          parameters = { "product" = "expr(object.getId())" },
 *          absolute=true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getProducts")
 * )
 *
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(["getProducts", "getProduct"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProducts", "getProduct"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProducts", "getProduct"])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProducts", "getProduct"])]
    private ?string $brand = null;

    #[ORM\Column]
    #[Groups(["getProducts", "getProduct"])]
    private ?float $price = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
