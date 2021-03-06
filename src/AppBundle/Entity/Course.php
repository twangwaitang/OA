<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="name", message="课程名称已存在")
 */
class Course implements JsonSerializable
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
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="课程名称不得为空")
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="课时不得为空")
     */
    private $teachHours;
    /**
     * @ORM\Column(type="smallint")
     */
    private $isPublic;
    /**
     * @ORM\Column(type="smallint")
     */
    private $isFinished;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $thumbnial;
    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     *
     */
    private $coursePlan;
    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     *
     */
    private $courseGoal;
    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     *
     */
    private $courseInfo;

    /**
     * @ORM\OneToMany(targetEntity="Chapter", mappedBy="course")
     */
    private $chapters;
    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="course")
     */
    private $exams;
    /**
     * @ORM\OneToMany(targetEntity="Notes", mappedBy="course")
     */
    private $notes;
    /**
     * @ORM\OneToMany(targetEntity="Recode", mappedBy="course")
     *
     */
    private $recodes;
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="course")
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity="Questions", mappedBy="course")
     */
    private $questions;

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

    /**
     * 一门课程对应一个小组.
     * @ORM\OneToOne(targetEntity="Group", mappedBy="course")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

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
    /**
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     *
     * @return Student
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
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $username
     */
    public function setName($name)
    {
        $this->name = $name;
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
            'name' => $this->getName(),
            'teachHours'    => $this->getTeachHours(),
            'coursePlan'    => $this->getCoursePlan(),
            'courseGoal'    => $this->getCourseGoal(),
            'courseInfo'    => $this->getCourseInfo(),
            'isFinished'    => $this->getIsFinished(),
            'isPublic'      => $this->getIsPublic(),
            'group'         => $this->getGroup(),
            'thumbnial'     => $this->getThumbnial(),
            'created_time'  => $this->getCreatedTime(),
        ];
    }


    /**
     * Set coursePlan
     *
     * @param string $coursePlan
     *
     * @return Course
     */
    public function setCoursePlan($coursePlan)
    {
        $this->coursePlan = $coursePlan;

        return $this;
    }

    /**
     * Get coursePlan
     *
     * @return string
     */
    public function getCoursePlan()
    {
        return $this->coursePlan;
    }

    /**
     * Set courseGoal
     *
     * @param string $courseGoal
     *
     * @return Course
     */
    public function setCourseGoal($courseGoal)
    {
        $this->courseGoal = $courseGoal;

        return $this;
    }

    /**
     * Get courseGoal
     *
     * @return string
     */
    public function getCourseGoal()
    {
        return $this->courseGoal;
    }

    /**
     * Set courseInfo
     *
     * @param string $courseInfo
     *
     * @return Course
     */
    public function setCourseInfo($courseInfo)
    {
        $this->courseInfo = $courseInfo;

        return $this;
    }

    /**
     * Get courseInfo
     *
     * @return string
     */
    public function getCourseInfo()
    {
        return $this->courseInfo;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return Course
     */
    public function setGroup(\AppBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set teachHours
     *
     * @param integer $teachHours
     *
     * @return Course
     */
    public function setTeachHours($teachHours)
    {
        $this->teachHours = $teachHours;

        return $this;
    }

    /**
     * Get teachHours
     *
     * @return integer
     */
    public function getTeachHours()
    {
        return $this->teachHours;
    }

    /**
     * Set thumbnial
     *
     * @param string $thumbnial
     *
     * @return Course
     */
    public function setThumbnial($thumbnial)
    {
        $this->thumbnial = $thumbnial;

        return $this;
    }

    /**
     * Get thumbnial
     *
     * @return string
     */
    public function getThumbnial()
    {
        return $this->thumbnial;
    }

    /**
     * Set isPublic
     *
     * @param integer $isPublic
     *
     * @return Course
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return integer
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set isFinished
     *
     * @param integer $isFinished
     *
     * @return Course
     */
    public function setIsFinished($isFinished)
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * Get isFinished
     *
     * @return integer
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
        $this->chapters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add chapter
     *
     * @param \AppBundle\Entity\Chapter $chapter
     *
     * @return Course
     */
    public function addChapter(\AppBundle\Entity\Chapter $chapter)
    {
        $this->chapters[] = $chapter;

        return $this;
    }

    /**
     * Remove chapter
     *
     * @param \AppBundle\Entity\Chapter $chapter
     */
    public function removeChapter(\AppBundle\Entity\Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
    }

    /**
     * Get chapters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * Add question
     *
     * @param \AppBundle\Entity\Questions $question
     *
     * @return Course
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
     * Add note
     *
     * @param \AppBundle\Entity\Notes $note
     *
     * @return Course
     */
    public function addNote(\AppBundle\Entity\Notes $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \AppBundle\Entity\Notes $note
     */
    public function removeNote(\AppBundle\Entity\Notes $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Course
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add recode
     *
     * @param \AppBundle\Entity\Recode $recode
     *
     * @return Course
     */
    public function addRecode(\AppBundle\Entity\Recode $recode)
    {
        $this->recodes[] = $recode;

        return $this;
    }

    /**
     * Remove recode
     *
     * @param \AppBundle\Entity\Recode $recode
     */
    public function removeRecode(\AppBundle\Entity\Recode $recode)
    {
        $this->recodes->removeElement($recode);
    }

    /**
     * Get recodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecodes()
    {
        return $this->recodes;
    }


    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return Course
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
