<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "getClient",
 *          parameters = { "client" = "expr(object.getId())" },
 *          absolute=true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"full_client"})
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "deleteClient",
 *          parameters = { "client" = "expr(object.getId())" },
 *          absolute=true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"full_client"})
 * )
 *
 */
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(["full_client"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["full_client"])]
    private ?Company $company = null;

    #[ORM\Column(length: 255)]
    #[Groups(["full_client"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(["full_client"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["full_client"])]
    private ?string $lastname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
}
