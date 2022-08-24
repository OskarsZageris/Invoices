<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,array(
                'attr' => array('style' => 'width: 300px')
            ))
            ->add('JIRA',IntegerType::class,array(
                'attr' => array('style' => 'width: 50px'),
                'required' => true
            ))
            ->add('JiraTask',TextType::class,array(
                'attr' => array('style' => 'width: 150px')
            ))
            ->add('ClientJiraTask',TextType::class,array(
                'attr' => array('style' => 'width: 150px')
            ))
            ->add('Description',TextType::class,array(
                'attr' => array('style' => 'width: 300px')
            ))
            ->add('Price',NumberType::class,array(
                'attr' => array('style' => 'width: 150px')
            ))
            ->add('Unit',IntegerType::class,array(
                'attr' => array('style' => 'width: 70px')
            ))
            ->add('Amount',NumberType::class,array(
                'attr' => array('style' => 'width: 150px')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
