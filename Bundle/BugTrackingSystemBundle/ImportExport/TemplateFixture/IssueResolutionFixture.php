<?php

namespace Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssueResolutionFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData(IssueResolution::FIXED);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssueResolution();
    }

    /**
     * @param string  $key
     * @param IssueResolution $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case IssueResolution::FIXED:
                $entity
                    ->setName(IssueResolution::FIXED)
                    ->setLabel(ucfirst(IssueResolution::FIXED))
                    ->setOrder(1);
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
