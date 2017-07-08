<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupRepository")
 * @ORM\Table(name="stgroup")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="name", message="小组名称已存在")
 *
 */
class Group implements JsonSerializable
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
     * @Assert\NotBlank(message="小组名称不得为空")
     */
    private $name;

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
     * Many Groups have Many Students.
     * @ORM\ManyToMany(targetEntity="Student", mappedBy="groups")
     */
    private $students;

    /**
     * Many Groups have Many Teachers.
     * @ORM\ManyToMany(targetEntity="Teacher", mappedBy="groups")
     */
    private $teachers;

    /**
     * Many Groups have Many Advisors.
     * @ORM\ManyToMany(targetEntity="Advisor", mappedBy="groups")
     */
    private $advisors;
    /**
     * 一个小组对应一门课程.
     * @ORM\OneToOne(targetEntity="Course", inversedBy="group")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    public function __construct() {
        $this->students = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->advisors = new ArrayCollection();
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
    public function setStudents($students)
    {
        $this->students[] = $students;

        return $this;
    }
    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
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
            'created_time' => $this->getCreatedTime(),
        ];
    }

    /**
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Group
     */
    public function addStudent(\AppBundle\Entity\Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(\AppBundle\Entity\Student $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Add teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Group
     */
    public function addTeacher(\AppBundle\Entity\Teacher $teacher)
    {
        $this->teachers[] = $teacher;

        return $this;
    }

    /**
     * Remove teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     */
    public function removeTeacher(\AppBundle\Entity\Teacher $teacher)
    {
        $this->teachers->removeElement($teacher);
    }

    /**
     * Get teachers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Add advisor
     *
     * @param \AppBundle\Entity\Teacher $advisor
     *
     * @return Group
     */
    public function addAdvisor(\AppBundle\Entity\Teacher $advisor)
    {
        $this->advisors[] = $advisor;

        return $this;
    }

    /**
     * Remove advisor
     *
     * @param \AppBundle\Entity\Teacher $advisor
     */
    public function removeAdvisor(\AppBundle\Entity\Teacher $advisor)
    {
        $this->advisors->removeElement($advisor);
    }

    /**
     * Get advisors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvisors()
    {
        return $this->advisors;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return Group
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
}
