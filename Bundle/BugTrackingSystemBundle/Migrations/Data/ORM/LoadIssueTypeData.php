<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;

/**
 * @codeCoverageIgnore
 */
class LoadIssueTypeData extends AbstractTranslatableEntityFixture
{
    const ISSUE_TYPE_PREFIX = 'issueType';

    /**
     * @var array
     */
    private $data = [
        1 => IssueType::STORY,
        2 => IssueType::TASK,
        3 => IssueType::SUB_TASK,
        4 => IssueType::BUG,
    ];

    /**
     * {@inheritdoc}
     */
    public function loadEntities(ObjectManager $manager)
    {
        $typeRepository = $manager->getRepository('OroBugTrackingSystemBundle:IssueType');

        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->data as $order => $typeName) {
                /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $issueType */
                $issueType = $typeRepository->findOneBy(['name' => $typeName]);
                if (!$issueType) {
                    $issueType = new IssueType();
                    $issueType->setName($typeName);
                    $issueType->setEntityOrder($order);
                }

                $typeLabel = $this->translate($typeName, static::ISSUE_TYPE_PREFIX, $locale);
                $issueType->setLocale($locale);
                $issueType->setLabel($typeLabel);

                $manager->persist($issueType);
            }

            $manager->flush();
        }
    }
}
