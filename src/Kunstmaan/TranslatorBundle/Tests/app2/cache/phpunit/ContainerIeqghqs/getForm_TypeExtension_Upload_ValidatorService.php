<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'form.type_extension.upload.validator' shared service.

return $this->services['form.type_extension.upload.validator'] = new \Symfony\Component\Form\Extension\Validator\Type\UploadValidatorExtension(${($_ = isset($this->services['kunstmaan_translator.service.translator.translator']) ? $this->services['kunstmaan_translator.service.translator.translator'] : $this->load(__DIR__.'/getKunstmaanTranslator_Service_Translator_TranslatorService.php')) && false ?: '_'}, 'validators');
