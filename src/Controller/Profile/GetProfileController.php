<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}", methods={"GET"}, name="api_profiles_get")
 */
final class GetProfileController extends AbstractController
{
    public function __invoke(User $profile): array
    {
        return ['profile' => $profile];
    }
}
