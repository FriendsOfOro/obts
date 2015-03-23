<?php

namespace Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssuePriorityFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData(IssuePriority::MAJOR);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssuePriority();
    }

    /**
     * @param string  $key
     * @param IssuePriority $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case IssuePriority::MAJOR:
                $entity
                    ->setName(IssuePriority::MAJOR)
                    ->setLabel(ucfirst(IssuePriority::MAJOR))
                    ->setOrder(1);
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
