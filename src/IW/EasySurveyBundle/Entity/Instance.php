<?php

namespace IW\EasySurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instance
 */
class Instance
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $quizId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $timecreated;

    /**
     * @var \DateTime
     */
    private $timefinish;

    /**
     * @var string
     */
    private $seeskey;
    
    /**
     * @var string
     */
    private $name;
    
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
     * Set quizId
     *
     * @param integer $quizId
     * @return Instance
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;
    
        return $this;
    }

    /**
     * Get quizId
     *
     * @return integer 
     */
    public function getQuizId()
    {
        return $this->quizId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Instance
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set timecreated
     *
     * @param \DateTime $timecreated
     * @return Instance
     */
    public function setTimecreated($timecreated)
    {
        $this->timecreated = $timecreated;
    
        return $this;
    }

    /**
     * Get timecreated
     *
     * @return \DateTime 
     */
    public function getTimecreated()
    {
        return $this->timecreated;
    }

    /**
     * Set timefinish
     *
     * @param \DateTime $timefinish
     * @return Instance
     */
    public function setTimefinish($timefinish)
    {
        $this->timefinish = $timefinish;
    
        return $this;
    }

    /**
     * Get timefinish
     *
     * @return \DateTime 
     */
    public function getTimefinish()
    {
        return $this->timefinish;
    }

    
    
    /**
     * Set seeskey
     *
     * @param string $seeskey
     * @return Instance
     */
    public function setSeeskey($seeskey)
    {
        $this->seeskey = $seeskey;
    
        return $this;
    }

    /**
     * Get seeskey
     *
     * @return string 
     */
    public function getSeeskey()
    {
        return $this->seeskey;
    }
    
    /**
     * Set seeskey
     *
     * @param string $name
     * @return Instance
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    
}
