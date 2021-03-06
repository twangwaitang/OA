<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="questions")
 * @ORM\HasLifecycleCallbacks()
 *
 *
 */
class Questions implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="questions")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $questionTitle;
    /**
     * @var array $makers
     *
     * @ORM\Column( type="array",nullable=true)
     * 选项
     */
    private $questionAnswer;
    /**
     * @var array $makers
     *
     * @ORM\Column( type="array",nullable=true)
     * 正确答案
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $questionLevel;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", options={"default"=0})
     * @Assert\NotBlank()
     * 0位单选，1是多选 3是判断
     */
    private $questionType;
    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Lesson", inversedBy="questions")
     * @ORM\JoinTable(name="lesson_question")
     *
     */
    private $lessons;
    /**
     * Many exams have Many questions.
     * @ORM\ManyToMany(targetEntity="Exam", inversedBy="questions")
     * @ORM\JoinTable(name="exam_question")
     *
     */
    private $exams;
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
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at http://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
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
            'title' => $this->getQuestionTitle(),
            'level' => $this->getQuestionLevel(),
            'answer' => $this->getAnswer(),
            'type'=>$this->getQuestionType(),
            'question_answer' => $this->getQuestionAnswer(),
            'created_time' => $this->getCreatedTime(),
        ];
    }


    /**
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     *
     * @return Lesson
     */
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
     * Set questionTitle
     *
     * @param string $questionTitle
     *
     * @return Questions
     */
    public function setQuestionTitle($questionTitle)
    {
        $this->questionTitle = $questionTitle;

        return $this;
    }

    /**
     * Get questionTitle
     *
     * @return string
     */
    public function getQuestionTitle()
    {
        return $this->questionTitle;
    }

    /**
     * Set questionAnswer
     *
     * @param array $questionAnswer
     *
     * @return Questions
     */
    public function setQuestionAnswer($questionAnswer)
    {
        $this->questionAnswer = $questionAnswer;

        return $this;
    }

    /**
     * Get questionAnswer
     *
     * @return array
     */
    public function getQuestionAnswer()
    {
        return $this->questionAnswer;
    }

    /**
     * Set answer
     *
     * @param array $answer
     *
     * @return Questions
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return array
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return Questions
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
     * Set questionLevel
     *
     * @param string $questionLevel
     *
     * @return Questions
     */
    public function setQuestionLevel($questionLevel)
    {
        $this->questionLevel = $questionLevel;

        return $this;
    }

    /**
     * Get questionLevel
     *
     * @return string
     */
    public function getQuestionLevel()
    {
        return $this->questionLevel;
    }

    /**
     * Set questionType
     *
     * @param integer $questionType
     *
     * @return Questions
     */
    public function setQuestionType($questionType)
    {
        $this->questionType = $questionType;

        return $this;
    }

    /**
     * Get questionType
     *
     * @return integer
     */
    public function getQuestionType()
    {
        return $this->questionType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Questions
     */
    public function addLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons[] = $lesson;

        return $this;
    }

    /**
     * Remove lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons->removeElement($lesson);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return Questions
     */
    public function addExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams[] = $exam;

        return $this;
    }

    /**
     * Remove exam
     *
     * @param \AppBundle\Entity\Exam $exam
     */
    public function removeExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams->removeElement($exam);
    }

    /**
     * Get exams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExams()
    {
        return $this->exams;
    }
}
