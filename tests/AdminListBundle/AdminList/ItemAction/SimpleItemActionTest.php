<?php
namespace Tests\Kunstmaan\AdminListBundle\AdminList\ItemAction;

use Kunstmaan\AdminListBundle\AdminList\ItemAction\SimpleItemAction;
use stdClass;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-26 at 13:21:34.
 */
class SimpleItemActionTest extends \PHPUnit_Framework_TestCase
{
    public function test__construct()
    {
        /** @noinspection PhpUnusedParameterInspection */
        $object = new SimpleItemAction(function($item) {
            return 'http://www.domain.com/action';
        }, 'icon.png', 'Label', 'template.html.twig');

        $item = new stdClass();
        $this->assertEquals('http://www.domain.com/action', $object->getUrlFor($item));
        $this->assertEquals('icon.png', $object->getIconFor($item));
        $this->assertEquals('Label', $object->getLabelFor($item));
        $this->assertEquals('template.html.twig', $object->getTemplate());

        return null;
    }
}