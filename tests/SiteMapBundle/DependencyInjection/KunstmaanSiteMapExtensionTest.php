<?php

namespace Tests\Kunstmaan\SitemapBundle\DependencyInjection;

use Kunstmaan\SitemapBundle\DependencyInjection\KunstmaanSitemapExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Tests\Kunstmaan\AbstractPrependableExtensionTestCase;

/**
 * Class KunstmaanSiteMapExtensionTest
 */
class KunstmaanSiteMapExtensionTest extends AbstractPrependableExtensionTestCase
{
    /**
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return [new KunstmaanSitemapExtension()];
    }


    public function testCorrectParametersHaveBeenSet()
    {
        $this->container->setParameter('empty_extension', true);
        $this->load();

        $this->assertContainerBuilderHasParameter('empty_extension', true );
    }
}
