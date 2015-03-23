<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Table(name="obts_issue_type")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Oro\Bundle\BugTrackingSystemBundle\Entity\IssueTypeTranslation")
 * @Config(
 *      defaultValues={
 *          "grouping"={
 *              "groups"={"dictionary"}
 *          },
 *          "dictionary"={
 *              "virtual_fields"={"label"}
 *          }
 *      }
 * )
 */
class IssueType implements Translatable
{
    const BUG      = 'bug';
    const STORY    = 'story';
    const SUB_TASK = 'sub_task';
    const TASK     = 'task';

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
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, unique=true)
     * @Gedmo\Translatable
     */
    protected $label;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

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
     * @return IssueType
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
     * @return IssueType
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
     * Set locale
     *
     * @param string $locale
     * @return IssueType
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Returns locale code
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return IssueType
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
