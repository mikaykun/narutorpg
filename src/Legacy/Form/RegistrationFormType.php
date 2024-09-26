<?php

namespace NarutoRPG\Legacy\Form;

use NarutoRPG\Legacy\Entity\RegisterForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nick', TextType::class, ['label' => 'Nick/Login', 'attr' => ['maxlength' => 50]])
            ->add('pw', PasswordType::class, ['label' => 'Passwort'])
            ->add('mail', EmailType::class, ['label' => 'E-Mail'])
            ->add('werb', TextType::class, ['label' => 'Geworben von (optional)', 'required' => false])
            ->add('Altersbest', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisterForm::class,
        ]);
    }
}
