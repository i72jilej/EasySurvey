<?php

namespace IW\EasySurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TextQuestionOption
 */
class TextQuestionOption
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $questionId;

    /**
     * @var string
     */
    private $text;


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
     * Set questionId
     *
     * @param integer $questionId
     * @return TextQuestionOption
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    
        return $this;
    }

    /**
     * Get questionId
     *
     * @return integer 
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return TextQuestionOption
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}
