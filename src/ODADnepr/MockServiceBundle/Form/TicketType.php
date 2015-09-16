<?php

namespace ODADnepr\MockServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body')
            ->add('created_date')
            ->add('completed_date')
            ->add('ticket_id')
            ->add('image')
            ->add('comment')
            ->add('user')
            ->add('address')
            ->add('manager')
            ->add('category')
            ->add('type')
            ->add('state')
            ->add('latitude')
            ->add('longitude')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ODADnepr\MockServiceBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odadnepr_mockservicebundle_ticket';
    }
}
