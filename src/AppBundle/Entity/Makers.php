<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="makers")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Makers implements JsonSerializable
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
     */
    private $makerTime;
    /**
     * @ORM\ManyToOne(targetEntity="Questions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $question;
    /**
     * @ORM\ManyToOne(targetEntity="Lesson",inversedBy="makers",cascade={"persist"})
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $lesson;
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
            'time'=>$this->getMakerTime(),
            'text'=>$this->getQuestion(),
        ];
    }



    /**
     * Set makerTime
     *
     * @param string $makerTime
     *
     * @return Makers
     */
    public function setMakerTime($makerTime)
    {
        $this->makerTime = $makerTime;

        return $this;
    }

    /**
     * Get makerTime
     *
     * @return string
     */
    public function getMakerTime()
    {
        return $this->makerTime;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Questions $question
     *
     * @return Makers
     */
    public function setQuestion(\AppBundle\Entity\Questions $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \AppBundle\Entity\Questions
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Makers
     */
    public function setLesson(\AppBundle\Entity\Lesson $lesson = null)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return \AppBundle\Entity\Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}
