<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
/**
 * @ORM\Entity
 * @ORM\Table(name="base_web")
 * @ORM\HasLifecycleCallbacks()
 */
class BaseWeb implements JsonSerializable
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
     */
    private $webTitle;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=true})
     */
    private $isOpen;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="logo图片不得为空")
     */
    private $logo;
    /**
     * @ORM\Column(type="string")
     */
    private $footer;

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
            'webTitle'=> $this->getWebTitle(),
            'logo'=> $this->getLogo(),
            'footer'=> $this->getFooter(),
            'isOpen'=> $this->getIsOpen(),
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
     * Set webTitle
     *
     * @param string $webTitle
     *
     * @return Base
     */
    public function setWebTitle($webTitle)
    {
        $this->webTitle = $webTitle;

        return $this;
    }

    /**
     * Get webTitle
     *
     * @return string
     */
    public function getWebTitle()
    {
        return $this->webTitle;
    }

    /**
     * Set isOpen
     *
     * @param boolean $isOpen
     *
     * @return Base
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * Get isOpen
     *
     * @return boolean
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Base
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set footer
     *
     * @param string $footer
     *
     * @return Base
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Get footer
     *
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     *
     * @return Base
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
     * @return Base
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
