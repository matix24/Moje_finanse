<?php

namespace App\Entity;

use App\Repository\ContextRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Klasa definiująca konteksty w których może działać użytkownik
 *
 * @ORM\Entity(repositoryClass=ContextRepository::class)
 * @ORM\Table(
 *      uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unq_user_contextName", columns={"user_id", "name"})
 *    }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Context
{
    /**
     * ID danego kontekstu
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $id;

    /**
     * Użytkownik dla którego ma się wyświetlać dany kontekst
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contexts")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private \App\Entity\User $user;

    /**
     * Krótka nazwa danego kontekstu
     *
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private string $name;

    /**
     * Notatka przy danym kontekście co on oznacza
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $description;

    /**
     * Flaga czy dany kontekst został zarchiwizowany
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @var boolean
     */
    private bool $is_archive = false;

    /**
     * Ikona dla danego kontekstu
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     * @var string|null
     */
    private ?string $icon = null;

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
     * Konta bankowe przypisane do danego kontekstu
     *
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="context")
     * @var Collection
     */
    private Collection $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    /**
     * Zwracam id danego kontekstu
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwracam użytkownika przypisanego do danego kontekstu
     *
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Ustawiam użytkownika przypisanego do danego kontekstu
     *
     * @param \App\Entity\User|null $user
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Zwracam nazwę danego kontekstu
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Ustawiam nazwę danego kontekstu
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
     * Zwracam notatkę dodaną do danego kontekstu
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Ustawiam notatkę dla danego kontekstu
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
     * Sprawdzam czy dany kontekst jest zarchiwizowany
     *
     * @return bool|null
     */
    public function isArchive(): ?bool
    {
        return $this->is_archive;
    }

    /**
     * Ustawiam dany kontekst jako archiwum
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
     * Zwracam ikonę dla danego kontekstu
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Ustawiam ikonę dla danego kontekstu
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
     * Zwracam datę utworzenia rekordu
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
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
     * Zwracam datę ostatniej aktualizacji rekordu
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * Ustawiam datę ostatniej aktualizacji rekordu
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

    /**
     * Pobieram konta bankowe przypisane do kolekcji
     *
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    } // end getAccounts

    /**
     * Dodaje konto bankowe do kolekcji
     *
     * @param Account $account
     * @return self
     */
    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setContext($this);
        }
        return $this;
    } // end addAccount

    /**
     * Usuwam konto bankowe z kolekcji
     *
     * @param Account $account
     * @return self
     */
    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getContext() === $this) {
                $account->setContext(null);
            }
        }
        return $this;
    } // removeAccount

    /********************************************************
     * HELPERY
     ********************************************************/

    /**
     * Sprawdzam czy dany użytkownik może coś zrobić z danym kontekstem
     *
     * @param \App\Entity\User $user
     * @return bool
     */
    public function checkPermission(User $user): bool
    {
        if ($this->getUser()->getId() === $user->getId()) {
            return true;
        }
        return false;
    } // end checkPermission

    /********************************************************
     * HELPERY
     ********************************************************/
} // end class
