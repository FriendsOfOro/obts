<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="obts_issue_resolution")
 * @ORM\Entity
 */
class IssueResolution
{
    const CANNOT_REPRODUCE = 'cannot_reproduce';
    const DONE             = 'done';
    const DUPLICATE        = 'duplicate';
    const FIXED            = 'fixed';
    const INCOMPLETE       = 'incomplete';
    const WONT_DO          = 'wont_do';
    const WONT_FIX         = 'wont_fix';

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
     * @ORM\Column(name="name", type="string", length=32)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, unique=true)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="`order`", type="integer")
     */
    protected $order;

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
     * Set name
     *
     * @param string $name
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->label;
    }
}
