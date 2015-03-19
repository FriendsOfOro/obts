<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;

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
                /**
                 * @var IssueType $issueType
                 */
                $issueType = $typeRepository->findOneByName($typeName);
                if (!$issueType) {
                    $issueType = new IssueType();
                    $issueType->setName($typeName);
                    $issueType->setOrder($order);
                }

                // set locale and label
                $typeLabel = $this->translate($typeName, static::ISSUE_TYPE_PREFIX, $locale);
                $issueType
                    ->setLocale($locale)
                    ->setLabel($typeLabel);

                // save
                $manager->persist($issueType);
            }
        }

        $manager->flush();
    }
}
