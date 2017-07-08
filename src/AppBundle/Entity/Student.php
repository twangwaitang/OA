<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="student")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="number", message="学号重复")
 *
 */
class Student implements JsonSerializable
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
     * @Assert\NotBlank()
     */
    private $number;
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank()
     */
    private $gendar;
    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank()
     */
    private $grade;
    /**
     * @ORM\Column(type="string",length=11,nullable=true)
     * @Assert\Length(min = 11, max = 11, minMessage = "错误,手机必须为11位数字")
     */
    private $tel;
    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     *
     */
    private $face;
    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     *
     */
    private $info;
    /**
     * 一个学生对应多个成绩.
     * @ORM\OneToMany(targetEntity="Score", mappedBy="student")
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="Recode", mappedBy="student")
     *
     */
    private $recodes;

    /**
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="student")
     *
     */
    private $blogs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime")
     */
    private $createdTime;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_time", type="datetime")
     */
    private $updatedTime;
    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="students")
     * @ORM\JoinTable(name="users_stgroups")
     *
     */

    private $groups;
    /**
     * @ORM\OneToMany(targetEntity="StudentHasAssignments", mappedBy="students",cascade={"persist","remove"} )
     */
    private $assignments;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

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
            'number'=> $this->getNumber(),
            'name' => $this->getName(),
            'grade' => $this->getGrade(),
            'gendar' => $this->getGendar(),
            'tel'=>$this->getTel(),
            'created_time' => $this->getCreatedTime()->format('Y-m-d H:i:s')
        ];
    }
    /**
     * Set number
     *
     * @param string $number
     *
     * @return Student
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set gendar
     *
     * @param string $gendar
     *
     * @return Student
     */
    public function setGendar($gendar)
    {
        $this->gendar = $gendar;

        return $this;
    }

    /**
     * Get gendar
     *
     * @return string
     */
    public function getGendar()
    {
        return $this->gendar;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return Student
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
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

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Student
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Set tel
     *
     * @param integer $tel
     *
     * @return Student
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return integer
     */
    public function getTel()
    {
        return $this->tel;
    }

    public function setGroups($groups)
    {
        $this->groups[] = $groups;

        return $this;
    }
    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return Student
     */
    public function addGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \AppBundle\Entity\Group $group
     */
    public function removeGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Add recode
     *
     * @param \AppBundle\Entity\Recode $recode
     *
     * @return Student
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
     * Set face
     *
     * @param string $face
     *
     * @return Student
     */
    public function setFace($face)
    {
        $this->face = $face;

        return $this;
    }

    /**
     * Get face
     *
     * @return string
     */
    public function getFace()
    {
        return $this->face;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Student
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
     * Add blog
     *
     * @param \AppBundle\Entity\Blog $blog
     *
     * @return Student
     */
    public function addBlog(\AppBundle\Entity\Blog $blog)
    {
        $this->blogs[] = $blog;

        return $this;
    }

    /**
     * Remove blog
     *
     * @param \AppBundle\Entity\Blog $blog
     */
    public function removeBlog(\AppBundle\Entity\Blog $blog)
    {
        $this->blogs->removeElement($blog);
    }

    /**
     * Get blogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogs()
    {
        return $this->blogs;
    }

    /**
     * Add score
     *
     * @param \AppBundle\Entity\Score $score
     *
     * @return Student
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



    /**
     * Add assignment
     *
     * @param \AppBundle\Entity\StudentHasAssignments $assignment
     *
     * @return Student
     */
    public function addAssignment(\AppBundle\Entity\StudentHasAssignments $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \AppBundle\Entity\StudentHasAssignments $assignment
     */
    public function removeAssignment(\AppBundle\Entity\StudentHasAssignments $assignment)
    {
        $this->assignments->removeElement($assignment);
    }

    /**
     * Get assignments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}
