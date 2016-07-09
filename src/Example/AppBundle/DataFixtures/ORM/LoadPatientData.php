<?php

namespace Example\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Example\AppBundle\Entity\Patient;

class LoadPatientData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $testPatient = new Patient();

        $testPatient->setName('Default');
        $testPatient->setDescription('Тестовый пользователь');

        $manager->persist($testPatient);
        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}