<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="student_has_assignments")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class StudentHasAssignments implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="assignments", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $students;
    /**
     * @ORM\ManyToOne(targetEntity="Assignment", inversedBy="students")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id")
     */
    private $assignments;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=0})
     */
    private $isReaded;
    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=0})
     */
    private $isFinished;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime")
     */
    private $createdTime ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_time", type="datetime")
     */
    private $updatedTime;

    public function getCreatedTime()
    {
        return $this->createdTime;
    }
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;

        return $this;
    }

    /**
     * Get updatedTime
     *
     * @return \DateTime
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }
    /**
     * @param \DateTime $createdTime
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }
    public function getId()
    {
        return $this->id;
    }
    /**
     * @ORM\PrePersist    //每次在commit前都会执行这个函数，达到自动更新创建时间和更新时间
     */
    public function PrePersist(){
        $date = new \DateTime('now',new \DateTimeZone('PRC'));
        if ($this->getCreatedTime() == null){
            $this->setCreatedTime($date);
        }
        $this->setUpdatedTime($date);
    }

    public function jsonSerialize()
    {
        return [
            'id'=> $this->getId(),
            'created_time' => $this->getCreatedTime(),
        ];
    }



    /**
     * Set isReaded
     *
     * @param boolean $isReaded
     *
     * @return Assignment
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    /**
     * Get isReaded
     *
     * @return boolean
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    /**
     * Set isFinished
     *
     * @param boolean $isFinished
     *
     * @return Assignment
     */
    public function setIsFinished($isFinished)
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * Get isFinished
     *
     * @return boolean
     */
    public function getIsFinished()
    {
        return $this->isFinished;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set students
     *
     * @param \AppBundle\Entity\Student $students
     *
     * @return StudentHasAssignments
     */
    public function setStudents(\AppBundle\Entity\Student $students = null)
    {
        $this->students = $students;

        return $this;
    }

    /**
     * Get students
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Set assignments
     *
     * @param \AppBundle\Entity\Assignment $assignments
     *
     * @return StudentHasAssignments
     */
    public function setAssignments(\AppBundle\Entity\Assignment $assignments = null)
    {
        $this->assignments = $assignments;

        return $this;
    }

    /**
     * Get assignments
     *
     * @return \AppBundle\Entity\Assignment
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}
