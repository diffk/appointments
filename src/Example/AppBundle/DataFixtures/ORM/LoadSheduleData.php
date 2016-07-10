<?php

namespace Example\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Example\AppBundle\Entity\Shedule;

class LoadSheduleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $elMocked = [
            [
                "start" => "11:00",
                "end"   => "11:20"
            ],
            [
                "start" => "11:20",
                "end"   => "11:40"
            ],
            [
                "start" => "11:40",
                "end"   => "12:00"
            ],
            [
                "start" => "15:00",
                "end"   => "15:20"
            ],
            [
                "start" => "15:20",
                "end"   => "15:40"
            ],
            [
                "start" => "15:40",
                "end"   => "16:00"
            ],
            [
                "start" => "19:00",
                "end"   => "19:20"
            ],
            [
                "start" => "19:20",
                "end"   => "19:40"
            ],
            [
                "start" => "19:40",
                "end"   => "20:00"
            ]
        ];

        $opened = [
            "mon" => $elMocked,
            "tue" => $elMocked,
            "wed" => $elMocked,
            "thu" => $elMocked,
            "fri" => $elMocked,
            "sat" => $elMocked,
            "sun" => $elMocked
        ];


        for ($j = 1; $j <= 3; $j++) {
            $doctor = $manager->getRepository('ExampleAppBundle:Doctor')->find($j);

            for ($i = 0; $i <= 53; $i++) {

                $testShedule = new Shedule();
                $testShedule->setDoctor($doctor);
                $testShedule->setYear(2016);
                $testShedule->setWeek($i);
                $testShedule->setRecords(json_encode($opened));

                $manager->persist($testShedule);
                $manager->flush();

            }

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}