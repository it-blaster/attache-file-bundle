<?php

namespace ItBlaster\AttachFileBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Виджет "Файл с информацией для объектов i18n"
 *
 * Class AttachFileType
 * @package ItBlaster\AttachFileBundle\Form\Type
 */
class AttachFileType extends FileType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'empty_data' => null,
            'multiple' => false,
            'object' => false,
            'help' => ''
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
            $view->vars['attr']['multiple'] = 'multiple';
        }

        $view->vars = array_replace($view->vars, array(
            'type'  => 'file',
            'value' => '',
        ));

        $field = substr($view->vars['name'], 0, -5);
        $object_i18n = $view->parent->vars['data'];
        $attach_file = $object_i18n->getFileObject($field);
        $view->vars['options'] = $options;
        if (!$attach_file->isNew() && $attach_file->issetFile()) {
            $view->vars['object'] = $object_i18n;
            $view->vars['attach_file'] = $attach_file;
        } else {
            $view->vars['object'] = false;
            $view->vars['attach_file'] = false;
        }
    }

    public function getName()
    {
        return 'attach_file';
    }
}