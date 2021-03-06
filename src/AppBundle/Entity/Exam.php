<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="exam")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Exam implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * 考试标题
     */
    private $title;
    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="notes")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * 所属课程
     */
    private $course;
    /**
     * @ORM\ManyToMany(targetEntity="Questions", mappedBy="exams")
     * 提目
     */
    private $questions;
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * 考试说明
     */
    private $info;
    /**
     * @var array
     *
     * @ORM\Column( type="array",nullable=true)
     * 客观题型的单体分值
     */
    private $scoreRate;
    /**
     * @var array
     *
     * @ORM\Column( type="integer")
     * @Assert\NotBlank(message="Please enter duration value")
     * 考试的时长
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="string")
     * 开始时间
     */
    private $startTime ;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="string")
     * 结束时间
     */
    private $endTime ;
    /**
     * 一个学生对应一个成绩.
     * @ORM\OneToMany(targetEntity="Score", mappedBy="exam")
     */
    private $scores;

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
            'startTime'=>$this->getStartTime(),
            'scoreRate'=>$this->getScoreRate(),
            'endTime'=>$this->getEndTime(),
            'info'=>$this->getInfo(),
            'title'=>$this->getTitle(),
            'questions'=>$this->getQuestions()->getValues(),
            'duration'=>$this->getDuration(),
            'created_time' => $this->getCreatedTime(),
        ];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Exam
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Exam
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set scoreRate
     *
     * @param array $scoreRate
     *
     * @return Exam
     */
    public function setScoreRate($scoreRate)
    {
        $this->scoreRate = $scoreRate;

        return $this;
    }

    /**
     * Get scoreRate
     *
     * @return array
     */
    public function getScoreRate()
    {
        return $this->scoreRate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Exam
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Exam
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return Exam
     */
    public function setCourse(\AppBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \AppBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Add question
     *
     * @param \AppBundle\Entity\Questions $question
     *
     * @return Exam
     */
    public function addQuestion(\AppBundle\Entity\Questions $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \AppBundle\Entity\Questions $question
     */
    public function removeQuestion(\AppBundle\Entity\Questions $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Exam
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Add score
     *
     * @param \AppBundle\Entity\Score $score
     *
     * @return Exam
     */
    public function addScore(\AppBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \AppBundle\Entity\Score $score
     */
    public function removeScore(\AppBundle\Entity\Score $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }
}
