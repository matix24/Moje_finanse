<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Klasa definiująca konta bankowe użytkownika
 *
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @ORM\Table(
 *      uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unq_context_accountName", columns={"context_id", "name"})
 *    }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Account
{
    /**
     * ID konta bankowego
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $id;

    /**
     * Kontekst dla którego dodał konto dany użytkownik
     *
     * @ORM\ManyToOne(targetEntity=Context::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     * @var Context
     */
    private Context $context;

    /**
     * Wyświetlana nazwa konta
     *
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private string $name;

    /**
     * Dodatkowa notatka do konta bankowego
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $description;

    /**
     * Ikona banku
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $icon;

    /**
     * Czy konto zostało zarchiwizowane
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     * $var boolean
     */
    private bool $is_archive = false;

    /**
     * Czy konto jest ukryte
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @var boolean
     */
    private bool $is_hidden = false;

    /**
     * Data utworzenia rekordu
     *
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private ?\DateTime $created_at = null;

    /**
     * Data ostatniej modyfikacji rekordu
     *
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private ?\DateTime $updated_at = null;


    /**
     * Zwracam id konta bankowego
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwracam kontekst dla którego zostało utworzone konto bankowe
     *
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * Ustawiam kontekst dla którego zostało utworzone konto bankowe
     *
     * @param Context|null $context
     * @return self
     */
    public function setContext(?Context $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Zwracam nazwę konta bankowego
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Ustawiam nazwę konta bankowego
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Zwracam opis do danego konta
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Ustawiam opis do danego konta
     *
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Zwracam ikonę danego banku
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Ustawiam ikonę danego banku
     *
     * @param string|null $icon
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Sprawdzam czy dane konto nie jest zarchiwizowane
     *
     * @return bool|null
     */
    public function isArchive(): ?bool
    {
        return $this->is_archive;
    }

    /**
     * Ustawiam czy konto jest zarchiwizowane
     *
     * @param bool $is_archive
     * @return self
     */
    public function setIsArchive(bool $is_archive): self
    {
        $this->is_archive = $is_archive;
        return $this;
    }

    /**
     * Sprawdzam czy konto jest ukryte
     *
     * @return bool|null
     */
    public function isHidden(): ?bool
    {
        return $this->is_hidden;
    }

    /**
     * Ustawiam czy konto ma być ukryte
     *
     * @param bool $is_hidden
     * @return self
     */
    public function setIsHidden(bool $is_hidden): self
    {
        $this->is_hidden = $is_hidden;
        return $this;
    }

    /**
     * Zwracam datę dodania rekordu do bazy
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * Ustawiam datę dodania rekordu do bazy danych
     *
     * @param \DateTime $created_at
     * @return self
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Zwracam datę ostatniej modyfikacji rekordu w bazie
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * Ustawiam datę ostatniej modyfikacji rekordu w bazie
     *
     * @param \DateTime $updated_at
     * @return self
     */
    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * Automatycznie ustawiam czasy dodania i aktualizacji rekordu
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }
    }// end updatedTimestamps
} // end class
