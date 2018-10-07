<?php

namespace Example\AppBundle\Common;

/**
 * Trait Time
 * 
 * @package Example\AppBundle\Common
 */
trait Time
{
    /**
     * days in short name
     */
    public $daysShort = [
        1 => 'mon',
        2 => 'tue',
        3 => 'wed',
        4 => 'thu',
        5 => 'fri',
        6 => 'sat',
        7 => 'sun'
    ];

    /**
     * Фильтрация прошедших дат(включая текущий день)
     *
     * @param array $scheduleList
     *
     * @return array
     */
    public function filteredDays($scheduleList): array
    {
        $currentDay = strtolower(date('D'));

        /** @var int $dayIndex */
        $dayIndex = array_search($currentDay, $this->daysShort, true) - 1;
        $i = 0;
        foreach ($scheduleList[0]['records'] as $key => &$record) {
            if ($i <= $dayIndex) {
                $record = [];
            }
            $i++;
        }

        return $scheduleList;
    }

    /**
     * @param int $weekNumber
     * @param int $year
     * @param int $day
     *
     * @return bool|string
     */
    public function getDateM($weekNumber, $year, $day)
    {
        $year = $year ? $year : date('Y');
        /** @var int $dayIndex */
        $dayIndex = array_search($day, $this->daysShort, true);
        /** @var string $weekNumber */
        $week = sprintf('%02d', $weekNumber);
        $date = strtotime($year.'W'.$week.$dayIndex.' 00:00:00');

        return date('Y-m-d', $date);
    }

    /**
     * @param int $weekNumber
     * @param null $year
     *
     * @return string
     */
    public function getDatesByWeek($weekNumber, $year = null): string
    {
        $year = $year ? $year : date('Y');
        /** @var string $weekNumber */
        $week = sprintf('%02d', $weekNumber);
        $dateFirst = strtotime($year.'W'.$week.'1 00:00:00');
        $dateEnd = strtotime($year.'W'.$week.'7 23:59:59');

        return date('Y-m-d', $dateFirst).' - '.date('Y-m-d', $dateEnd);
    }


}