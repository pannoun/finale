<?php

namespace App\Form;

use App\Entity\Tache;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreTache', TextType::class, [
                'label' => 'Task Title',
                'required' => true,
            ])
            ->add('descriptionTache', TextType::class, [
                'label' => 'Task Description',
                'required' => true,
            ])
            ->add('dateCreation', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
                'label' => 'Creation Date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('dateEcheance', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
                'label' => 'Due Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            // Champ pour télécharger un fichier PDF ou Word
            ->add('file', FileType::class, [
                'label' => 'Upload File (PDF or Word)',
                'mapped' => false,  // This field is not mapped to the entity directly
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                        'mimeTypesMessage' => 'Please upload a valid PDF or Word file.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
