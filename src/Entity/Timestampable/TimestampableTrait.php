<?php

namespace App\Entity\Timestampable;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ReflectionClass;

trait TimestampableTrait
{
    /**************************************/
    /* PROPERTIES                         */
    /**************************************/
    /**
     * @var DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $updatedAt;

    /**************************************/
    /* CUSTOM CODE                        */
    /**************************************/

    public function getClassName()
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function getTimestampableDetail()
    {
        $res = 'creato';
        if ($this->getCreatedAt()) $res .= '<br/>il ' . $this->getCreatedAt()->format('d/m/Y H:i:s');

        if ($this->getCreatedAt() != $this->getUpdatedAt()) {
            $res .= '<br/><br/>modificato';
            if ($this->getUpdatedAt()) $res .= '<br/>il ' . $this->getUpdatedAt()->format('d/m/Y H:i:s');
        }

        return $res;
    }
    /**************************************/
    /* GETTERS & SETTERS                  */
    /**************************************/

    /**
     * Set createdAt
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param $updatedAt
     * @return TimestampableTrait
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
} 