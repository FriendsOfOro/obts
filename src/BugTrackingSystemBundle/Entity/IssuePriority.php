<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Table(name="oro_bts_issue_priority")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriorityTranslation")
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
class IssuePriority implements Translatable
{
    const BLOCKER  = 'blocker';
    const CRITICAL = 'critical';
    const MAJOR    = 'major';
    const MINOR    = 'minor';
    const TRIVIAL  = 'trivial';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, unique=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $label;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_order", type="integer")
     */
    private $entityOrder;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setEntityOrder(int $entityOrder): void
    {
        $this->entityOrder = $entityOrder;
    }

    public function getEntityOrder(): ?int
    {
        return $this->entityOrder;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->label;
    }
}
