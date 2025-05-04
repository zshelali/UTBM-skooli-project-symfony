<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $first_name = null;

    #[ORM\Column(length: 150)]
    private ?string $last_name = null;

    #[ORM\Column(length: 300)]
    private ?string $email = null;

    #[ORM\Column(length: 300)]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $profile_picture = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $id_role = null;

    /**
     * @var Collection<int, LoginHistory>
     */
    #[ORM\OneToMany(targetEntity: LoginHistory::class, mappedBy: 'id_user')]
    private Collection $loginHistories;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'id_user')]
    private Collection $posts;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'id_user')]
    private Collection $comments;

    /**
     * @var Collection<int, Submission>
     */
    #[ORM\OneToMany(targetEntity: Submission::class, mappedBy: 'id_user')]
    private Collection $submissions;

    /**
     * @var Collection<int, Announcement>
     */
    #[ORM\OneToMany(targetEntity: Announcement::class, mappedBy: 'id_user')]
    private Collection $announcements;

    /**
     * @var Collection<int, UE>
     */
    #[ORM\ManyToMany(targetEntity: UE::class, mappedBy: 'id_user')]
    private Collection $UEs;

    public function __construct()
    {
        $this->loginHistories = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->submissions = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->UEs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?string $profile_picture): static
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    public function getIdRole(): ?Role
    {
        return $this->id_role;
    }

    public function setIdRole(?Role $id_role): static
    {
        $this->id_role = $id_role;

        return $this;
    }

    /**
     * @return Collection<int, LoginHistory>
     */
    public function getLoginHistories(): Collection
    {
        return $this->loginHistories;
    }

    public function addLoginHistory(LoginHistory $loginHistory): static
    {
        if (!$this->loginHistories->contains($loginHistory)) {
            $this->loginHistories->add($loginHistory);
            $loginHistory->setIdUser($this);
        }

        return $this;
    }

    public function removeLoginHistory(LoginHistory $loginHistory): static
    {
        if ($this->loginHistories->removeElement($loginHistory)) {
            // set the owning side to null (unless already changed)
            if ($loginHistory->getIdUser() === $this) {
                $loginHistory->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setIdUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getIdUser() === $this) {
                $post->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setIdUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdUser() === $this) {
                $comment->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Submission>
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(Submission $submission): static
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions->add($submission);
            $submission->setIdUser($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): static
    {
        if ($this->submissions->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getIdUser() === $this) {
                $submission->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): static
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->setIdUser($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): static
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getIdUser() === $this) {
                $announcement->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUEs(): Collection
    {
        return $this->UEs;
    }

    public function addUE(UE $uE): static
    {
        if (!$this->UEs->contains($uE)) {
            $this->UEs->add($uE);
            $uE->addIdUser($this);
        }

        return $this;
    }

    public function removeUE(UE $uE): static
    {
        if ($this->UEs->removeElement($uE)) {
            $uE->removeIdUser($this);
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        if (!$this->id_role) {
            return ['ROLE_USER'];
        }
        return [strtoupper('ROLE_' . $this->id_role->getName())];
    }

    public function eraseCredentials(): void
    {

    }
    public function __toString(): string
    {
        return $this->email ?? '';
    }

}
