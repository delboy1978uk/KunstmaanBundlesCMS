<?php

namespace Tests\Kunstmaan\MultiDomainBundle\Helper;

use Kunstmaan\MultiDomainBundle\Helper\DomainConfiguration;
use Kunstmaan\NodeBundle\Entity\Node;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class DomainConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Node
     */
    protected $node;


    public function testGetHostWithMultiLanguage()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('multilangdomain.tld', $object->getHost());
    }

    public function testGetHostWithSingleLanguage()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('singlelangdomain.tld', $object->getHost());
    }

    public function testGetHostWithAlias()
    {
        $request = $this->getAliasedRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('singlelangdomain.tld', $object->getHost());
    }

    public function testGetHostWithOverrideOnFrontend()
    {
        $request = $this->getRequestWithOverride('/frontend-uri');
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('multilangdomain.tld', $object->getHost());
    }

    public function testGetHostWithOverrideOnBackend()
    {
        $request = $this->getRequestWithOverride('/nl/admin/backend-uri');
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('singlelangdomain.tld', $object->getHost());
    }

    public function testGetHosts()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('multilangdomain.tld', 'singlelangdomain.tld'), $object->getHosts());
    }

    public function testGetDefaultLocale()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('en_GB', $object->getDefaultLocale());
    }

    public function testGetDefaultLocaleWithUnknownDomain()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals('en', $object->getDefaultLocale());
    }

    public function testGetExtraDataWithoutDataSet()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array(), $object->getExtraData());
    }

    public function testGetExtraDataWithDataSet()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('foo' => 'bar'), $object->getExtraData());
    }

    public function testGetRootNode()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals($this->node, $object->getRootNode());
    }

    public function testGetRootNodeWithUnknown()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertNull($object->getRootNode());
    }

    public function testIsMultiDomainHostWithSingleLanguage()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertTrue($object->isMultiDomainHost());
    }

    public function testIsMultiDomainHostWithMultiLanguage()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertTrue($object->isMultiDomainHost());
    }

    public function testIsMultiDomainHostWithUnknown()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertFalse($object->isMultiDomainHost());
    }

    public function testIsMultiLanguageWithSingleLanguage()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertFalse($object->isMultiLanguage());
    }

    public function testIsMultiLanguageWithMultiLanguage()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertTrue($object->isMultiLanguage());
    }

    public function testIsMultiLanguageWithUnknown()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertFalse($object->isMultiLanguage());
    }

    public function testGetFrontendLocalesWithSingleLanguage()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('en'), $object->getFrontendLocales());
    }

    public function testGetFrontendLocalesWithMultiLanguage()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('nl', 'fr', 'en'), $object->getFrontendLocales());
    }

    public function testGetFrontendLocalesWithUnknown()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('en'), $object->getFrontendLocales());
    }

    public function testGetBackendLocalesWithSingleLanguage()
    {
        $request = $this->getSingleLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('en_GB'), $object->getBackendLocales());
    }

    public function testGetBackendLocalesWithMultiLanguage()
    {
        $request = $this->getMultiLanguageRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('nl_BE', 'fr_BE', 'en_GB'), $object->getBackendLocales());
    }

    public function testGetBackendLocalesWithUnknown()
    {
        $request = $this->getUnknownDomainRequest();
        $object  = $this->getDomainConfiguration($request);
        $this->assertEquals(array('en'), $object->getBackendLocales());
    }

    private function getContainer($map, $request)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $container
            ->method('getParameter')
            ->will($this->returnValueMap($map));

        $serviceMap = array(
            array('request_stack', 1, $this->getRequestStack($request)),
            array('doctrine.orm.entity_manager', 1, $this->getEntityManager()),
        );

        $container
            ->method('get')
            ->will($this->returnValueMap($serviceMap));

        return $container;
    }

    private function getEntityManager()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $em
            ->method('getRepository')
            ->with($this->equalTo('KunstmaanNodeBundle:Node'))
            ->willReturn($this->getNodeRepository());

        return $em;
    }

    private function getNodeRepository()
    {
        $repository = $this->getMockBuilder('Kunstmaan\NodeBundle\Repository\NodeRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository
            ->method('getNodeByInternalName')
            ->willReturn($this->getRootNode());

        return $repository;
    }

    private function getRootNode()
    {
        $this->node = $this->getMock('Kunstmaan\NodeBundle\Entity\Node');

        return $this->node;
    }

    private function getRequestStack($request)
    {
        $requestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->expects($this->any())->method('getMasterRequest')->willReturn($request);

        return $requestStack;
    }

    private function getUnknownDomainRequest()
    {
        $request = Request::create('http://unknown.tld/');

        return $request;
    }

    private function getMultiLanguageRequest()
    {
        $request = Request::create('http://multilangdomain.tld/');

        return $request;
    }

    private function getSingleLanguageRequest()
    {
        $request = Request::create('http://singlelangdomain.tld/');

        return $request;
    }

    private function getAliasedRequest()
    {
        $request = Request::create('http://single-alias.tld/');

        return $request;
    }

    private function getRequestWithOverride($uri)
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set(DomainConfiguration::OVERRIDE_HOST, 'singlelangdomain.tld');

        $request = Request::create('http://multilangdomain.tld' . $uri);
        $request->setSession($session);
        $request->cookies->set($session->getName(), null);

        return $request;
    }

    private function getDomainConfiguration($request)
    {
        $hostMap = array(
            'multilangdomain.tld'  => array(
                'host'            => 'multilangdomain.tld',
                'type'            => 'multi_lang',
                'default_locale'  => 'en_GB',
                'locales'         => array('nl' => 'nl_BE', 'fr' => 'fr_BE', 'en' => 'en_GB'),
                'reverse_locales' => array('nl_BE' => 'nl', 'fr_BE' => 'fr', 'en_GB' => 'en'),
                'root'            => 'homepage_multi',
                'aliases'         => array('multi-alias.tld'),
                'extra'           => array('foo' => 'bar'),
            ),
            'singlelangdomain.tld' => array(
                'host'            => 'singlelangdomain.tld',
                'type'            => 'single_lang',
                'default_locale'  => 'en_GB',
                'locales'         => array('en' => 'en_GB'),
                'reverse_locales' => array('en_GB' => 'en'),
                'root'            => 'homepage_single',
                'aliases'         => array('single-alias.tld'),
            )
        );

        $map = array(
            array('multilanguage', false),
            array('defaultlocale', 'en'),
            array('requiredlocales', 'en'),
            array('kunstmaan_multi_domain.hosts', $hostMap)
        );

        $object = new DomainConfiguration($this->getContainer($map, $request));

        return $object;
    }
}