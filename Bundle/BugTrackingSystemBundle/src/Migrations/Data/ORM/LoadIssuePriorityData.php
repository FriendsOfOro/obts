<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;

/**
 * @codeCoverageIgnore
 */
class LoadIssuePriorityData extends AbstractTranslatableEntityFixture
{
    const ISSUE_PRIORITY_PREFIX = 'issuePriority';

    /**
     * @var array
     */
    private $data = [
        1 => IssuePriority::BLOCKER,
        2 => IssuePriority::CRITICAL,
        3 => IssuePriority::MAJOR,
        4 => IssuePriority::MINOR,
        5 => IssuePriority::TRIVIAL,
    ];

    /**
     * {@inheritdoc}
     */
    public function loadEntities(ObjectManager $manager)
    {
        $priorityRepository = $manager->getRepository('OroBugTrackingSystemBundle:IssuePriority');

        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->data as $order => $priorityName) {
                /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $issuePriority */
                $issuePriority = $priorityRepository->findOneBy(['name' => $priorityName]);
                if (!$issuePriority) {
                    $issuePriority = new IssuePriority();
                    $issuePriority->setName($priorityName);
                    $issuePriority->setEntityOrder($order);
                }

                $priorityLabel = $this->translate($priorityName, static::ISSUE_PRIORITY_PREFIX, $locale);
                $issuePriority->setLocale($locale);
                $issuePriority->setLabel($priorityLabel);

                $manager->persist($issuePriority);
            }

            $manager->flush();
        }
    }
}
