<?php

namespace Example\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Example\AppBundle\Entity\Doctor;

/**
 * @ORM\Entity(repositoryClass="Example\AppBundle\Entity\SheduleRepository")
 * @ORM\Table(name="shedule")
 */
class Shedule {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Doctor", inversedBy="shedules")
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     */
    private $doctor;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $week;

    /**
     * @ORM\Column(type="text", length=1000)
     */
    private $records;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Shedule
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set week
     *
     * @param integer $week
     *
     * @return Shedule
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get week
     *
     * @return integer
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set records
     *
     * @param string $records
     *
     * @return Shedule
     */
    public function setRecords($records)
    {
        $this->records = $records;

        return $this;
    }

    /**
     * Get records
     *
     * @return string
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Set doctor
     *
     * @param \Example\AppBundle\Entity\Doctor $doctor
     *
     * @return Shedule
     */
    public function setDoctor(\Example\AppBundle\Entity\Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \Example\AppBundle\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'doctor_id' => $this->getDoctor()->getId(),
            'week' => $this->getWeek(),
            'year' => $this->getYear(),
            'records' => json_decode($this->getRecords(), true),
        );
    }
}
