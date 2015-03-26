<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller\Api\Rest\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
        if (!$user) {
            return;
        }

        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        $entity = new Issue();
        $entity->setSummary('Acl issue');
        $entity->setCode('ORO-' . microtime());
        $entity->setDescription('Acl issue description');
        $entity->setReporter($user);
        $entity->setOwner($user);
        $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        $entity->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        $entity->setOrganization($organization);
        $entity->addCollaborator($user);

        $manager->persist($entity);
        $manager->flush();
    }
}
