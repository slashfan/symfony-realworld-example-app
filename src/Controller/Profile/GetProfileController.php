<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}", methods={"GET"}, name="api_profiles_get")
 */
final class GetProfileController
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
