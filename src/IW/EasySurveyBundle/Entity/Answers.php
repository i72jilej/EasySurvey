<?php

namespace IW\EasySurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 */
class Answers
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idQuestion;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $timestamp;
    
    
    /**
     * @var integer
     */
    private $idInstance;


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
     * Set idQuestion
     *
     * @param integer $idQuestion
     * @return Answers
     */
    public function setIdQuestion($idQuestion)
    {
        $this->idQuestion = $idQuestion;
    
        return $this;
    }

    /**
     * Get idQuestion
     *
     * @return integer 
     */
    public function getIdQuestion()
    {
        return $this->idQuestion;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Answers
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Answers
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    
        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    /**
     * Set idInstance
     *
     * @param integer $idInstance
     * @return Instance
     */
    public function setIdInstance($idInstance)
    {
        $this->idInstance = $idInstance;
    
        return $this;
    }

    /**
     * Get idInstance
     *
     * @return integer
     */
    public function getIdInstance()
    {
        return $this->idInstance;
    }
    
}
