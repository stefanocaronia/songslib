<?php

namespace App\Entity\Timestampable;

interface TimestampableInterface
{
    public function setCreatedAt($createdAt);
    public function getCreatedAt();
    public function setUpdatedAt( $updatedAt);
    public function getUpdatedAt();
}