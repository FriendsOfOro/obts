<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\BugTrackingSystemBundle\Model\ExtendIssue;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * Issue
 *
 * @ORM\Table(
 *      name="oro_bts_issue",
 *      indexes={
 *          @ORM\Index(name="uidx_oro_bts_issue_code",columns={"code"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Oro\Bundle\BugTrackingSystemBundle\Entity\Repository\IssueRepository")
 * @ORM\HasLifecycleCallbacks
 * @Config(
 *      routeName="oro_bug_tracking_system_issue_index",
 *      routeView="oro_bug_tracking_system_issue_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-list-alt"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "workflow"={
 *              "active_workflow"="issue_flow",
 *              "show_step_in_grid"=false
 *          },
 *          "form"={
 *              "form_type"="Oro\Bundle\BugTrackingSystemBundle\Form\Type\IssueSelectType",
 *              "grid_name"="issues-grid",
 *          }
 *      }
 * )
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Issue extends ExtendIssue implements Taggable, DatesAwareInterface
{
    use DatesAwareTrait;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=false)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=20,
     *              "header"="Summary"
     *          }
     *      }
     * )
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false, unique=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=10,
     *              "header"="Code",
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=30,
     *              "header"="Description"
     *          }
     *      }
     * )
     */
    private $description;

    /**
     * @var IssueType
     *
     * @ORM\ManyToOne(targetEntity="IssueType")
     * @ORM\JoinColumn(name="issue_type_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=40,
     *              "header"="Type",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $issueType;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="issue_priority_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=50,
     *              "header"="Priority",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $issuePriority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="issue_resolution_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=60,
     *              "header"="Resolution",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $issueResolution;

    /**
     * @var ArrayCollection
     */
    private $tags;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=70,
     *              "header"="Reporter",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=80,
     *              "header"="Assignee",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *      name="oro_bts_issue_collaborators",
     *      joinColumns={
     *          @ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=120,
     *              "header"="Parent",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Issue")
     * @ORM\JoinTable(
     *      name="oro_bts_issue_relations",
     *      joinColumns={
     *          @ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="linked_issue_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $relatedIssues;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent", cascade={"all"})
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $children;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=110,
     *              "header"="Organization",
     *              "short"=true
     *          }
     *      }
     * )
     */
    private $organization;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->children = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setIssueType(?IssueType $issueType): void
    {
        $this->issueType = $issueType;
    }

    public function getIssueType(): ?IssueType
    {
        return $this->issueType;
    }

    public function setIssuePriority(IssuePriority $issuePriority): void
    {
        $this->issuePriority = $issuePriority;
    }

    public function getIssuePriority(): ?IssuePriority
    {
        return $this->issuePriority;
    }

    public function setIssueResolution(IssueResolution $issueResolution): void
    {
        $this->issueResolution = $issueResolution;
    }

    public function getIssueResolution(): ?IssueResolution
    {
        return $this->issueResolution;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        if (!$this->tags) {
            $this->tags = new ArrayCollection();
        }

        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    public function setReporter(User $reporter): void
    {
        $this->reporter = $reporter;
    }

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function addCollaborator(User $user): void
    {
        if (!$this->hasCollaborator($user)) {
            $this->collaborators->add($user);
        }
    }

    public function removeCollaborator(User $user): void
    {
        $this->collaborators->removeElement($user);
    }

    public function hasCollaborator(User $user): bool
    {
        return $this->collaborators->contains($user);
    }

    /**
     * @return User[]|Collection
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function setParent(Issue $parent): void
    {
        $this->parent = $parent;
    }

    public function detachParent(): void
    {
        $this->parent = null;
    }

    public function getParent(): ?Issue
    {
        return $this->parent;
    }

    public function addChild(Issue $child): void
    {
        if (!$this->hasChild($child)) {
            $this->children->add($child);
        }
    }

    public function removeChild(Issue $child): void
    {
        $this->children->removeElement($child);
    }

    public function addRelatedIssue(Issue $issue): void
    {
        if (!$this->hasRelatedIssue($issue)) {
            $this->relatedIssues->add($issue);
        }
    }

    public function removeRelatedIssue(Issue $issue): void
    {
        $this->relatedIssues->removeElement($issue);
    }

    public function hasRelatedIssue(Issue $issue): bool
    {
        return $this->relatedIssues->contains($issue);
    }

    /**
     *  @return Issue[]|Collection
     */
    public function getRelatedIssues(): Collection
    {
        return $this->relatedIssues->toArray();
    }

    public function hasChild(Issue $child): bool
    {
        return $this->children->contains($child);
    }

    /**
     * @return Issue[]|Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setOrganization(Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getCode();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateTemporaryCodeOnPrePersist()
    {
        if (!$this->getCode() && $this->getOrganization()) {
            $this->setCode(sprintf('%s-%d', $this->getOrganization()->getName(), crc32(microtime())));
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function refreshCreatedAtOnPrePersist()
    {
        $this->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * @ORM\PreUpdate
     */
    public function refreshUpdatedAtOnPreUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    public function isStory(): bool
    {
        return $this->getIssueType() && $this->getIssueType()->getName() === IssueType::STORY;
    }

    public function isSubTask(): bool
    {
        return $this->getIssueType() && $this->getIssueType()->getName() === IssueType::SUB_TASK;
    }
}
