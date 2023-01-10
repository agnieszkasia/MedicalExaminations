<?php

namespace App\Form;

use App\Entity\Exam;
use App\Entity\Param;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('value', NumberType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('exam', EntityType::class, [
                'class' => Exam::class,
                'choice_value' => function (Exam $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'attr' => ['style' => 'width:0;height:0;position:absolute']
            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Param::class,
        ]);
    }
}
