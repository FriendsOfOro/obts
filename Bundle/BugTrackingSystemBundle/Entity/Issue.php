<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\BugTrackingSystemBundle\Model\ExtendIssue;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * Issue
 *
 * @ORM\Table(name="obts_issue")
 * @ORM\Entity(repositoryClass="Oro\Bundle\BugTrackingSystemBundle\Entity\Repository\IssueRepository")
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-list-alt"
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
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $assignee;

    /**
     * //@var
     */
    //protected $related_issues;

    /**
     * //@var
     */
    //protected $collaborators;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->children = new ArrayCollection();
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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return sprintf('%s-%d', 'ORO', $this->getId());
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
     * Set assignee
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $assignee
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function setAssignee(User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
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
     * Add children
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $children
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue
     */
    public function addChild(Issue $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $children
     */
    public function removeChild(Issue $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
