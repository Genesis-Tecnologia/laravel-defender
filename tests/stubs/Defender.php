<?php

namespace GenesisTecnologia\Defender\Testing;

use GenesisTecnologia\Defender\Defender as BaseDefender;

/**
 * Class Defender.
 */
class Defender extends BaseDefender
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user.
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
