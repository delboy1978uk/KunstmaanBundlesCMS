<?php

namespace Tests\Kunstmaan\FormBundle\Entity;

use Kunstmaan\FormBundle\Entity\FormSubmissionFieldTypes\BooleanFormSubmissionField;
use Kunstmaan\FormBundle\Entity\PageParts\CheckboxPagePart;
use Kunstmaan\FormBundle\Form\BooleanFormSubmissionType;
use Kunstmaan\FormBundle\Form\CheckboxPagePartAdminType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CheckboxPagePartAdminTypeTest
 */
class CheckboxPagePartAdminTypeTest extends TypeTestCase
{
    public function testFormType()
    {
        $formData = [
            'required' => false,
            'label' => 'check this box!',
            'errormessage_required' => 'fill in the form',
        ];

        $form = $this->factory->create(CheckboxPagePartAdminType::class);

        $object = new CheckboxPagePart();
        $object->setRequired(false);
        $object->setErrorMessageRequired('fill in the form');
        $object->setLabel('check this box!');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertEquals($object, $form->getData());
    }
}