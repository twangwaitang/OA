<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="carousel")
 * @ORM\HasLifecycleCallbacks()
 */
class Carousel implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=false})
     */
    private $isPublic;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="图片不得为空")
     */
    private $pic;
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
     * Set isPublic
     *
     * @param boolean $isPublic
     *
     * @return Carousel
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set pic
     *
     * @param string $pic
     *
     * @return Carousel
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Carousel
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
     * @return Carousel
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
     * @return Carousel
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
