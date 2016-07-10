<?php

namespace Example\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Example\AppBundle\Entity\Shedule;

/**
 * Class SheduleRepository
 * @package Example\AppBundle\Entity
 */
class SheduleRepository extends EntityRepository
{
    public function getSheduleByPeriod($doctorId, $year, $startWeek, $endWeek)
    {

        $query = $this->_em
            ->createQuery(
                "select u from Example\AppBundle\Entity\Shedule u
                            where u.doctor = $doctorId and
                            u.year = $year and u.week >= $startWeek and u.week <= $endWeek"
            );
        $shedules = $query->getResult();

        return $shedules;
    }

    public function updateShedule($shedule, $user, $day, $start)
    {
        $result = false;

        $arrayOfShedule = json_decode($shedule->getRecords(), true);

        foreach ($arrayOfShedule[$day] as &$period) {
            if ($period['start'] === $start) {
                $period['used'] = $user;
                $result = true;
            }
        }

        $em = $this->_em;

        $shedule->setRecords(json_encode($arrayOfShedule));

        $em->persist($shedule);
        $em->flush();

        return $result;

    }
} 