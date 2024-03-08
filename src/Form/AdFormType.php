<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\AdType;
use App\Entity\Condition;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'adType',
                EntityType::class,
                [
                    'class' => AdType::class,
                    'choice_label' => 'title'
                ]
            )
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('duration', IntegerType::class,
            ['required' => false])
            ->add(
                'conditionAd',
                EntityType::class,
                [
                    'class' => Condition::class,
                    'choice_label' => 'title',
                    'required' => false
                ]
            )
            ->add(
                'subCategory',
                EntityType::class,
                [
                    'class' => SubCategory::class,
                    'choice_label' => 'title'
                ]
            )
            ->add('imageFile', FileType::class,
            [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
