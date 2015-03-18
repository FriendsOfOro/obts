<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Oro\Bundle\BugTrackingSystemBundle\DependencyInjection\OroBugTrackingSystemExtension;

class OroBugTrackingSystemExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OroBugTrackingSystemExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new OroBugTrackingSystemExtension();
    }

    /**
     * Test load
     */
    public function testLoad()
    {
        $this->extension->load(array(), $this->container);
    }
}
