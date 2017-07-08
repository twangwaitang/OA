<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherRepository")
 * @ORM\Table(name="teacher")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="number", message="工号重复")
 *
 */
class Teacher implements JsonSerializable
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
     *
     */
    private $gendar;
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
    private $position;

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
     * @var boolean
     * @ORM\column(type="boolean",options={"default"=false})
     */
    protected $isTop;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = [];


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
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="teachers")
     * @ORM\JoinTable(name="teachers_stgroups")
     *
     */
    private $groups;

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
            'gendar' => $this->getGendar(),
            'tel'=>$this->getTel(),
            'position'=>$this->getPosition(),
            'face'=>$this->getFace(),
            'info'=>$this->getInfo(),
            'isTop'=>$this->getIsTop(),
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

    public function getRoles()
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return Teacher
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
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Teacher
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set face
     *
     * @param string $face
     *
     * @return Teacher
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
     * @return Teacher
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
     * Set isTop
     *
     * @param boolean $isTop
     *
     * @return Teacher
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop
     *
     * @return boolean
     */
    public function getIsTop()
    {
        return $this->isTop;
    }
}
