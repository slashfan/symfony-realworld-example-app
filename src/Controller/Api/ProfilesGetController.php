<?php

namespace App\Controller\Api;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProfilesGetController.
 *
 * @Route("/api/profiles/{username}", name="api_profiles_get")
 * @Method("GET")
 */
class ProfilesGetController
{
    /**
     * @param User $profile
     *
     * @return array
     */
    public function __invoke(User $profile)
    {
        return ['profile' => $profile];
    }
}
