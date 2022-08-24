<?php

namespace App\Form;

use App\Entity\Invoices;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Client', TextType::class)
            ->add('Type', ChoiceType::class,[
                'choices'  => [
                    'Invoice' => 'Invoice',
                    'Credit invoice' => 'Credit invoice'
                ]]
            )
            ->add('Issuer', TextType::class)
            ->add('Owner', TextType::class)
            ->add('IssueDate',DateType::class)
            ->add('DueDate',DateType::class)
            ->add('Paid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoices::class,
        ]);
    }
}
