<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\BugTrackingSystemBundle\Model\ExtendIssue;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

/**
 * Issue
 *
 * @ORM\Table(
 *      name="obts_issue",
 *      indexes={
 *          @ORM\Index(name="uidx_obts_issue_code",columns={"code"})
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
 *              "form_type"="oro_bug_tracking_system_issue_select",
 *              "grid_name"="issues_grid",
 *          }
 *      }
 * )
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Issue extends ExtendIssue implements Taggable
{
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
    protected $id;

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
    protected $summary;

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
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=30,
     *              "header"="Description"
     *          }
     *      }
     * )
     */
    protected $description;

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
    protected $issueType;

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
    protected $issuePriority;

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
    protected $issueResolution;

    /**
     * @var ArrayCollection
     */
    protected $tags;

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
    protected $reporter;

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
    protected $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *      name="obts_issue_collaborators",
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
    protected $collaborators;

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
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Issue")
     * @ORM\JoinTable(
     *      name="obts_issue_relations",
     *      joinColumns={
     *          @ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="linked_issue_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    protected $relatedIssues;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent", cascade={"remove"})
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $children;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=90,
     *              "header"="Created At"
     *          }
     *      }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=100,
     *              "header"="Created At"
     *          }
     *      }
     * )
     */
    protected $updatedAt;

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
    protected $organization;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $workflowStep;

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

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Issue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set issueType
     *
     * @param IssueType $issueType
     * @return Issue
     */
    public function setIssueType(IssueType $issueType = null)
    {
        $this->issueType = $issueType;

        return $this;
    }

    /**
     * Get issueType
     *
     * @return IssueType
     */
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
     * Set issuePriority
     *
     * @param IssuePriority $issuePriority
     * @return Issue
     */
    public function setIssuePriority(IssuePriority $issuePriority = null)
    {
        $this->issuePriority = $issuePriority;

        return $this;
    }

    /**
     * Get issuePriority
     *
     * @return IssuePriority
     */
    public function getIssuePriority()
    {
        return $this->issuePriority;
    }

    /**
     * Set issueResolution
     *
     * @param IssueResolution $issueResolution
     * @return Issue
     */
    public function setIssueResolution(IssueResolution $issueResolution = null)
    {
        $this->issueResolution = $issueResolution;

        return $this;
    }

    /**
     * Get issueResolution
     *
     * @return IssueResolution
     */
    public function getIssueResolution()
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

    /**
     * Set reporter
     *
     * @param User $reporter
     * @return Issue
     */
    public function setReporter(User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return Issue
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add collaborator
     *
     * @param User $user
     * @return Issue
     */
    public function addCollaborator(User $user)
    {
        if (!$this->hasCollaborator($user)) {
            $this->collaborators->add($user);
        }

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param User $user
     */
    public function removeCollaborator(User $user)
    {
        $this->collaborators->removeElement($user);
    }

    /**
     * Has collaborator
     *
     * @param User $user
     * @return  boolean
     */
    public function hasCollaborator(User $user)
    {
        return $this->collaborators->contains($user);
    }

    /**
     *  Get collaborators
     *
     *  @return User[]
     */
    public function getCollaborators()
    {
        return $this->collaborators->toArray();
    }

    /**
     * Set parent
     *
     * @param Issue $parent
     * @return Issue
     */
    public function setParent(Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param Issue $child
     * @return Issue
     */
    public function addChild(Issue $child)
    {
        if (!$this->hasChild($child)) {
            $this->children->add($child);
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param Issue $child
     */
    public function removeChild(Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Add related issue
     *
     * @param Issue $issue
     * @return Issue
     */
    public function addRelatedIssue(Issue $issue)
    {
        if (!$this->hasRelatedIssue($issue)) {
            $this->relatedIssues->add($issue);
        }

        return $this;
    }

    /**
     * Remove related issue
     *
     * @param Issue $issue
     */
    public function removeRelatedIssue(Issue $issue)
    {
        $this->relatedIssues->removeElement($issue);
    }

    /**
     * Has related issue
     *
     * @param Issue $issue
     * @return boolean
     */
    public function hasRelatedIssue(Issue $issue)
    {
        return $this->relatedIssues->contains($issue);
    }

    /**
     *  Get related issues
     *
     *  @return Issue[]
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues->toArray();
    }

    /**
     * Has child
     *
     * @param Issue $child
     * @return boolean
     */
    public function hasChild(Issue $child)
    {
        return $this->children->contains($child);
    }

    /**
     * Get children
     *
     * @return Issue[]
     */
    public function getChildren()
    {
        return $this->children->toArray();
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     * @return Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param WorkflowItem $workflowItem
     * @return Issue
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowStep $workflowStep
     * @return Issue
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
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
    public function setCreatedAtAndUpdatedAtOnPrePersist()
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));

        if (!$this->getId()) {
            $this
                ->setCreatedAt($date)
                ->setUpdatedAt($date);
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function refreshUpdatedAtOnPreUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * @return boolean
     */
    public function isStory()
    {
        return $this->getIssueType() && $this->getIssueType()->getName() == IssueType::STORY;
    }

    /**
     * @return boolean
     */
    public function isSubTask()
    {
        return $this->getIssueType() && $this->getIssueType()->getName() == IssueType::SUB_TASK;
    }
}
