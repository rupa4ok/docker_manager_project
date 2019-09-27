<?php

namespace App\ReadModel\Company\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Name',
                'onchange' => 'this.form.submit()',
                ]]
            )
            ->add(
                'inn', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Inn',
                'onchange' => 'this.form.submit()',
                ]]
            )
            ->add(
                'date', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Date',
                'onchange' => 'this.form.submit()',
                ]]
            );
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
            ]
        );
    }
}
