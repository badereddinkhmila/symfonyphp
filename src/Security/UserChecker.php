<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
        dump($user->getActive());
        if ( $user->getActive() == false ) {
            throw new CustomUserMessageAccountStatusException('Your user account is deactivated.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}
