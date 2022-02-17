<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity('email', message:"l'email est déja utilisé veuillez vous connectez!")]
#[UniqueEntity('pseudo', message:"le pseudo est deja utilisé")]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        min:5,
        max:50,
        minMessage:'le pseudo doit faire minimum 5 charactères',
        maxMessage:'le pseudo ne doit pas dépassé 50 charactères'
    )]
    #[ORM\Column(name:'pseudo', type: 'string', length: 50, unique:true)]
    private $pseudo;
    
    #[Assert\Length(
        max:150,
        maxMessage:'l\'email ne doit pas dépassé 150 charactères'
    )]
    #[Assert\NotBlank]
    #[Assert\Email(message: "l'addresse email n'est pas valide")]
    #[ORM\Column(name:'email', type: 'string', length: 150, unique: true)]
    private $email;

    #[Assert\Length(
        min:8,
        minMessage:'le mot de passe doit faire minimum 8 charactères'
    )]
 
    
    #[ORM\Column(type: 'string', length: 255)]
    private $password;
    
  
    private $confirmPassword;

    #[ORM\Column(type: 'string', length: 255)]
    private $url_img;

    #[ORM\Column(type:"json")]
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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
    // ajouté pour userinterface
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getUrlImg(): ?string
    {
        return $this->url_img;
    }

    public function setUrlImg(string $url_img): self
    {
        $this->url_img = $url_img;

        return $this;
    }
}
