<?php
 
namespace RH;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('file', 'file')
        ;
    }
 
    public function getName()
    {
        return 'upload';
    }
}