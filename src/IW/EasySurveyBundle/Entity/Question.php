<?php

namespace IW\EasySurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 */
class Question
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var integer
     */
    private $quizId;


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
     * Set name
     *
     * @param string $name
     * @return Question
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

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Question
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    
        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set quizId
     *
     * @param integer $quizId
     * @return Question
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
}
