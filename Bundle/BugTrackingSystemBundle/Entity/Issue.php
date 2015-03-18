<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\BugTrackingSystemBundle\Model\ExtendIssue;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

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
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=false)
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var IssueType
     *
     * @ORM\ManyToOne(targetEntity="IssueType")
     * @ORM\JoinColumn(name="issue_type_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $issueType;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="issue_priority_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $issuePriority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="issue_resolution_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $issueResolution;

    /**
     * //@var
     */
    //protected $status;

    /**
     * //@var
     */
    //protected $tags;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $owner;

    /**
     * //@var
     */
    //protected $related_issues;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *      name="obts_issue_collaborators",
     *      joinColumns={
     *          @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     * )
     */
    protected $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     */
    protected $children;

    /**
     * //@var
     */
    //protected $notes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->children = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
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
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $issueType
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setIssueType(IssueType $issueType = null)
    {
        $this->issueType = $issueType;

        return $this;
    }

    /**
     * Get issueType
     *
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType
     */
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
     * Set issuePriority
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $issuePriority
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setIssuePriority(IssuePriority $issuePriority = null)
    {
        $this->issuePriority = $issuePriority;

        return $this;
    }

    /**
     * Get issuePriority
     *
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority
     */
    public function getIssuePriority()
    {
        return $this->issuePriority;
    }

    /**
     * Set issueResolution
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution $issueResolution
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setIssueResolution(IssueResolution $issueResolution = null)
    {
        $this->issueResolution = $issueResolution;

        return $this;
    }

    /**
     * Get issueResolution
     *
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution
     */
    public function getIssueResolution()
    {
        return $this->issueResolution;
    }

    /**
     * Set reporter
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $reporter
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setReporter(User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set owner
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $owner
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $user
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @param \Oro\Bundle\UserBundle\Entity\User $user
     */
    public function removeCollaborator(User $user)
    {
        $this->collaborators->removeElement($user);
    }

    /**
     * Has collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $user
     * @return  boolean
     */
    public function hasCollaborator(User $user)
    {
        return $this->collaborators->contains($user);
    }

    /**
     *  Get collaborators
     *
     *  @return \Oro\Bundle\UserBundle\Entity\User[]
     */
    public function getCollaborators()
    {
        return $this->collaborators->toArray();
    }

    /**
     * Set parent
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $parent
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setParent(Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $child
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
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
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $child
     */
    public function removeChild(Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Has child
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $child
     * @return boolean
     */
    public function hasChild(Issue $child)
    {
        return $this->children->contains($child);
    }

    /**
     * Get children
     *
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue[]
     */
    public function getChildren()
    {
        return $this->children->toArray();
    }

    /**
     * Set organization
     *
     * @param \Oro\Bundle\OrganizationBundle\Entity\Organization $organization
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Oro\Bundle\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
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
    public function setCreatedAtAndUpdatedAtOnPrePersist()
    {
        $date = new \DateTime();

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
        $this->setUpdatedAt(new \DateTime());
    }
}
