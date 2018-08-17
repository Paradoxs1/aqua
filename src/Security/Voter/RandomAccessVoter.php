<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RandomAccessVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'RANDOM_ACCESS';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return random_int(0, 10) > 5;
    }
}
