<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;

/**
 * @codeCoverageIgnore
 */
class LoadIssueResolutionData extends AbstractTranslatableEntityFixture
{
    const ISSUE_RESOLUTION_PREFIX = 'issueResolution';

    /**
     * @var array
     */
    private $data = [
        1 => IssueResolution::FIXED,
        2 => IssueResolution::WONT_FIX,
        3 => IssueResolution::DUPLICATE,
        4 => IssueResolution::INCOMPLETE,
        5 => IssueResolution::CANNOT_REPRODUCE,
        6 => IssueResolution::DONE,
        7 => IssueResolution::WONT_DO,
    ];

    /**
     * {@inheritdoc}
     */
    public function loadEntities(ObjectManager $manager)
    {
        $resolutionRepository = $manager->getRepository('OroBugTrackingSystemBundle:IssueResolution');

        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->data as $order => $resolutionName) {
                /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution $issueResolution */
                $issueResolution = $resolutionRepository->findOneBy(['name' => $resolutionName]);
                if (!$issueResolution) {
                    $issueResolution = new IssueResolution();
                    $issueResolution->setName($resolutionName);
                    $issueResolution->setOrder($order);
                }

                $resolutionLabel = $this->translate($resolutionName, static::ISSUE_RESOLUTION_PREFIX, $locale);
                $issueResolution
                    ->setLocale($locale)
                    ->setLabel($resolutionLabel);

                $manager->persist($issueResolution);
            }

            $manager->flush();
        }
    }
}
