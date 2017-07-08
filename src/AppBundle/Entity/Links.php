<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="links")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="链接名称已存在")
 */
class Links implements JsonSerializable
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
     * @Assert\NotBlank(message="链接名称不得为空")
     */
    private $title;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=false})
     */
    private $isPublic;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="链接图片不得为空")
     */
    private $thumbnial;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="链接地址不得为空")
     */
    private $url;

    /**
     * @var \DateTime
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
            'isPublic'      => $this->getIsPublic(),
            'thumbnial'     => $this->getThumbnial(),
            'created_time'  => $this->getCreatedTime(),
        ];
    }



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
     * Set title
     *
     * @param string $title
     *
     * @return Links
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
     * Set isPublic
     *
     * @param integer $isPublic
     *
     * @return Links
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
     * Set thumbnial
     *
     * @param string $thumbnial
     *
     * @return Links
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
     * Set url
     *
     * @param string $url
     *
     * @return Links
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     *
     * @return Links
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     *
     * @return Links
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
}
