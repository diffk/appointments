<?php

namespace Example\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Example\AppBundle\Entity\Doctor;

/**
 * @ORM\Entity
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
    private $opened;

    /**
     * @ORM\Column(type="text", length=1000)
     */
    private $closed;

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
     * Set opened
     *
     * @param string $opened
     *
     * @return Shedule
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get opened
     *
     * @return string
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Set closed
     *
     * @param string $closed
     *
     * @return Shedule
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return string
     */
    public function getClosed()
    {
        return $this->closed;
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
}
