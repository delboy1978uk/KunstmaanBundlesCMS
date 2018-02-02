<?php

namespace Tests\Kunstmaan\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\GroupInterface;
use Kunstmaan\AdminBundle\Entity\User;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-04 at 16:54:04.
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new User();
    }

    public function test__construct()
    {
        $object = new User();
        $object->setId(1);
        $this->assertEquals(1, $object->getId());
    }

    public function testGetSetId()
    {
        $this->object->setId(3);
        $this->assertEquals(3, $this->object->getId());
    }

    public function testGetGroupIds()
    {
        $group1 = $this->getMock('FOS\UserBundle\Model\GroupInterface');
        $group1
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $group2 = $this->getMock('FOS\UserBundle\Model\GroupInterface');
        $group2
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        /* @var $group1 GroupInterface */
        $this->object->addGroup($group1);
        /* @var $group2 GroupInterface */
        $this->object->addGroup($group2);

        $this->assertEquals(array(1, 2), $this->object->getGroupIds());
    }

    public function testGetGroups()
    {
        /* @var $group1 GroupInterface */
        $group1 = $this->getMock('FOS\UserBundle\Model\GroupInterface');
        /* @var $group2 GroupInterface */
        $group2 = $this->getMock('FOS\UserBundle\Model\GroupInterface');
        $this->object->addGroup($group1);
        $this->object->addGroup($group2);

        $collection = new ArrayCollection();
        $collection->add($group1);
        $collection->add($group2);

        $this->assertEquals($collection, $this->object->getGroups());
    }

    public function testHasRole()
    {
        $this->object->addRole('ROLE_CUSTOM');
        $this->assertTrue($this->object->hasRole('ROLE_CUSTOM'));

        $this->object->removeRole('ROLE_CUSTOM');
        $this->assertFalse($this->object->hasRole('ROLE_CUSTOM'));
    }


    public function testGettersAndSetters()
    {
        $user = $this->object;
        $user->setAdminLocale('en');
        $user->setPasswordChanged(true);
        $user->setGoogleId('g0oGl3');
        $user->setEnabled(true);

        $this->assertEquals('en', $user->getAdminLocale());
        $this->assertEquals('g0oGl3', $user->getGoogleId());
        $this->assertTrue($user->isPasswordChanged());
        $this->assertTrue($user->isAccountNonLocked());
        $this->assertEquals('Kunstmaan\AdminBundle\Form\UserType', $user->getFormTypeClass());
    }

    public function testLoadValidatorMetadata()
    {
        $meta = new ClassMetadata(User::class);
        User::loadValidatorMetadata($meta);
        $this->assertEquals('Kunstmaan\AdminBundle\Entity\User', $meta->getClassName());
        $this->assertEquals('User', $meta->getDefaultGroup());
        $props = $meta->getConstrainedProperties();
        $this->assertCount(3, $props);
        $this->assertEquals('username', $props[0]);
        $this->assertEquals('plainPassword', $props[1]);
        $this->assertEquals('email', $props[2]);
    }
}