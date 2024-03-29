<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description',TextareaType::class,[
                'required'=> false
            ])

            ->add('isPublished',CheckboxType::class,[
                'required'=> false
            ])

            ->add('poster_file', FileType::class,[
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],

            ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $formEvent){
                $wish = $formEvent->getData();

                if($wish && $wish->getPoster()){
                    $form = $formEvent->getForm();

                    //
                    $form->add('delete_image', CheckboxType::class,[
                        'mapped'=>false,
                        'required'=>false,
                    ]);
		}
            })
        ->add('category', EntityType::class, [
        'placeholder' => '--Veuillez choisir une category--',
        'class' => Category::class,
        'choice_label' => 'name',

    ])
        ->add('submit', SubmitType::class,[
        'label' => 'Enregistrer',
    ])
        ->add('delete', ButtonType::class, [
            'label' => 'Supprimer',
            'attr' => [
                'onclick' => "return confirm('Êtes-vous sûr de vouloir supprimer ce wish ?')",

            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
