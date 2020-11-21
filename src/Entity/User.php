<?php

namespace App\Entity;

use App\Entity\Partner;
use App\Repository\UserRepository;
use App\Security\AuthRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Klasa definiująca użytkownika systemu
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Podany email został już zarejestrowany")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     * @var array
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @var string
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": "0"})
     * @var boolean
     */
    private bool $isVerified = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": "0"})
     * @var bool
     */
    private bool $is_disabled = false;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \Datetime|null
     */
    private ?\Datetime $created_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \Datetime|null
     */
    private ?\Datetime $updated_at = null;

    /**
     * ID użytkownika
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Email użytkownika
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Ustawiam adres użytkownika
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Pobieram role dla użytkownika
     * @see UserInterface
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Ustawiam role dla użytkownika
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Zwracam hasło użytkownika z bazy
     * @see UserInterface
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Ustawiam hasło użytkownika
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Sprawdzam czy dany użytkownik jest zweryfikowany
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Ustawiam weryfikacje danego użytkownika
     * @param bool $isVerified
     * @return self
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    /**
     * Zwracam informacje czy dane konto jest wyłączone
     * @return  bool
     */
    public function isDisabled(): bool
    {
        return $this->is_disabled;
    }

    /**
     * Ustawiam informacje czy dane konto jest wyłączone
     * @param  bool  $is_disabled
     * @return  self
     */
    public function setIsDisabled(bool $is_disabled): self
    {
        $this->is_disabled = $is_disabled;
        return $this;
    }

    /**
     * Zwracam datę utworzenia rekordu
     * @return \Datetime|null
     */
    public function getCreatedAt(): ?\Datetime
    {
        return $this->created_at;
    }

    /**
     * Ustawiam datę utworzenia rekordu
     * @param \Datetime $user_created_at
     * @return self
     */
    public function setCreatedAt(\Datetime $user_created_at): self
    {
        $this->created_at = $user_created_at;
        return $this;
    }

    /**
     * Zwracam datę ostatniej aktualizacji
     * @return \Datetime|null
     */
    public function getUpdatedAt(): ?\Datetime
    {
        return $this->updated_at;
    }

    /**
     * Ustawiam datę aktualizacji rekordu
     * @param \Datetime $user_updated_at
     * @return  self
     */
    public function setUpdatedAt(\Datetime $user_updated_at): self
    {
        $this->updated_at = $user_updated_at;
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
    } // end updatedTimestamps

    /*************************************************
     * implements UserInterface
     *************************************************/

    /**
     * Wirtualny identyfikator użytkownika
     *
     * @see UserInterface
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /*************************************************
     * implements UserInterface
     *************************************************/



    /*************************************************
     * HELPERS
     *************************************************/

    /**
     * Zwracam link do odpowiedniego menu
     * @return string
     */
    public function getLinkToMenu(): string
    {
        $roles = $this->getRoles();
        switch ($roles[0]) {
            case AuthRole::ROLE_ADMIN:
                return 'layout/app/parts/usersMenu/menuAdmin.html.twig';
            default:
                return 'layout/app/parts/usersMenu/menuUser.html.twig';
        }
    } // end getLinkToMenu

    /*************************************************
     * HELPERS
     *************************************************/
} // end class
