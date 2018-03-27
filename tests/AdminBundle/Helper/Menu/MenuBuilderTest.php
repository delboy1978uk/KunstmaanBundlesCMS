<?php
namespace Tests\Kunstmaan\AdminBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\SettingsMenuAdaptor;
use Kunstmaan\AdminBundle\Helper\Menu\SimpleMenuAdaptor;
use Kunstmaan\AdminBundle\Helper\Menu\TopMenuItem;
use Kunstmaan\MenuBundle\Entity\MenuItem;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-21 at 09:05:10.
 */
class MenuBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MenuBuilder
     */
    protected $object;

    /**
     * @var ContainerInterface $container (mock)
     */
    protected $container;

    /**
     * @var ContainerInterface $container (mock)
     */
    protected $authCheck;

    /**
     * @var RequestStack $stack (mock)
     */
    protected $stack;

    protected function setUp()
    {
        $storage = $this->createMock(TokenStorageInterface::class);
        $authCheck = $this->createMock(AuthorizationCheckerInterface::class);
        $authCheck->tokenStorage = $storage;
        $this->authCheck = $authCheck;
        $container = $this->createMock(ContainerInterface::class);
        $stack = $this->createMock(RequestStack::class);
        $this->stack = $stack;

        $container->expects($this->any())->method('getParameter')->willReturn(true);
        $container->expects($this->any())->method('get')->will($this->onConsecutiveCalls(
            $stack, $stack, $authCheck
        ));

        $this->container = $container;
        $this->object = new MenuBuilder($container);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetCurrent()
    {
        $this->stack->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn(new Request([],[],['_route' => 'KunstmaanAdminBundle_settings']));
        $this->authCheck->expects($this->any())->method('isGranted')->willReturn(true);
        $adapter = new SettingsMenuAdaptor($this->container);
        $this->object->addAdaptMenu($adapter);
        $current = $this->object->getCurrent();
        $this->assertInstanceOf(TopMenuItem::class, $current);

        $mirror = new ReflectionClass(MenuBuilder::class);
        $property = $mirror->getProperty('currentCache');
        $property->setAccessible(true);
        $property->setValue($this->object, new MenuItem());
        $current = $this->object->getCurrent();
        $this->assertInstanceOf(MenuItem::class, $current);
    }


    public function testGetBreadcrumbs()
    {
        $this->stack->expects($this->atLeastOnce())
        ->method('getCurrentRequest')
        ->willReturn(new Request([],[],['_route' => 'KunstmaanAdminBundle_settings']));
        $this->authCheck->expects($this->any())->method('isGranted')->willReturn(true);
        $adapter = new SettingsMenuAdaptor($this->container);
        $this->object->addAdaptMenu($adapter);
        $crumb = $this->object->getBreadCrumb();
        $this->assertTrue(is_array($crumb));
        $this->assertCount(1, $crumb);
        $this->assertInstanceOf(TopMenuItem::class, $crumb[0]);
    }


    public function testGetLowestTopChild()
    {
        $this->stack->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn(new Request([],[],['_route' => 'KunstmaanAdminBundle_settings']));
        $this->authCheck->expects($this->any())->method('isGranted')->willReturn(true);
        $adapter = new SettingsMenuAdaptor($this->container);
        $this->object->addAdaptMenu($adapter);
        $lowest = $this->object->getLowestTopChild();
        $this->assertInstanceOf(TopMenuItem::class, $lowest);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetLowestTopChildNonTop()
    {
        $parent = new MenuItem();
        $child = new MenuItem();
        $child->setParent($parent);

        $mirror = new ReflectionClass(MenuBuilder::class);
        $property = $mirror->getProperty('currentCache');
        $property->setAccessible(true);
        $property->setValue($this->object, $child);

        $lowest = $this->object->getLowestTopChild();
        $this->assertNull($lowest);
    }
}
