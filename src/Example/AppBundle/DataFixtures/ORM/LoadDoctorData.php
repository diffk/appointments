<?php

namespace Example\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Example\AppBundle\Entity\Doctor;

class LoadDoctorData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $testDoctor = new Doctor();

        $testDoctor->setId(1);
        $testDoctor->setName('Пирогов Иван Иванович');
        $testDoctor->setProfile('Терапевт');

        $manager->persist($testDoctor);
        $manager->flush();

        $testDoctor = new Doctor();

        $testDoctor->setId(2);
        $testDoctor->setName('Петрова Ирина Владимировна');
        $testDoctor->setProfile('Терапевт');

        $manager->persist($testDoctor);
        $manager->flush();

        $testDoctor = new Doctor();

        $testDoctor->setId(3);
        $testDoctor->setName('Хор Юрий Константинович');
        $testDoctor->setProfile('Хирург');

        $manager->persist($testDoctor);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}