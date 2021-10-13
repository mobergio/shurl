<?php

namespace App\Form;

use App\Entity\UrlItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                "label" => "form.url",
                "attr"  => [
                    "oninput" => "checkUrl()",
                ],
            ])
            ->add('slug', TextType::class, [
                "label"    => "form.slug",
                "required" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UrlItem::class,
        ]);
    }
}
