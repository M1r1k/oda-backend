<?php

namespace ODADnepr\MockServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('middle_name')
            ->add('email')
            ->add('birthday')
            ->add('image')
            ->add('gender')
            ->add('phone')
            ->add('password')
            ->add('facilities')
            ->add('socialCondition')
            ->add('address')
            ->add('fb_registered', 'integer', array('required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ODADnepr\MockServiceBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odadnepr_mockservicebundle_user';
    }
}
