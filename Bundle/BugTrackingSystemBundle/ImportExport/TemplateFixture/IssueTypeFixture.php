<?php

namespace Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssueTypeFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData(IssueType::STORY);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssueType();
    }

    /**
     * @param string  $key
     * @param IssueType $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case IssueType::STORY:
                $entity
                    ->setName(IssueType::STORY)
                    ->setLabel(ucfirst(IssueType::STORY))
                    ->setOrder(1);
                return;
            case IssueType::SUB_TASK:
                $entity
                    ->setName(IssueType::SUB_TASK)
                    ->setLabel(ucfirst(IssueType::SUB_TASK))
                    ->setOrder(1);
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
