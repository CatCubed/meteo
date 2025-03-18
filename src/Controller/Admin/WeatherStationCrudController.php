<?php

namespace App\Controller\Admin;

use App\Entity\WeatherStation;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WeatherStationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WeatherStation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Weather Station')
            ->setEntityLabelInPlural('Weather Stations')
            ->setSearchFields(['name', 'key'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('key'),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
            DateTimeField::new('updatedAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', true),
            AssociationField::new('measurements')
                ->hideOnForm()
                ->hideOnIndex(),
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
        $station = new WeatherStation();
        $station->setCreatedAt(new \DateTime());
        $station->setUpdatedAt(new \DateTime());

        return $station;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
