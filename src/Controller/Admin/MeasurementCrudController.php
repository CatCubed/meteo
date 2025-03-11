<?php

namespace App\Controller\Admin;

use App\Entity\Measurement;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class MeasurementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Measurement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Measurement')
            ->setEntityLabelInPlural('Measurements')
            ->setSearchFields(['name', 'domain', 'placement'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('weatherStation'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('weatherStation'),
            TextField::new('name'),
            TextField::new('domain'),
            TextField::new('placement'),

            NumberField::new('temperature'),
            TextField::new('temperatureUnit'),
            NumberField::new('lowestTemp'),
            NumberField::new('highestTemp'),

            NumberField::new('humidity'),
            TextField::new('humidityUnit'),
            NumberField::new('lowestHumidity'),
            NumberField::new('highestHumidity'),

            NumberField::new('pressure'),
            TextField::new('pressureUnit'),
            NumberField::new('lowestPressure'),
            NumberField::new('highestPressure'),

            NumberField::new('rssi'),
            NumberField::new('voltage'),

            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
            DateTimeField::new('updatedAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    }

    public function createEntity(string $entityFqcn)
    {
        $measurement = new Measurement();
        $measurement->setCreatedAt(new \DateTime());
        $measurement->setUpdatedAt(new \DateTime());

        return $measurement;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime());
        $entityManager->persist($entityInstance);
    }
}
