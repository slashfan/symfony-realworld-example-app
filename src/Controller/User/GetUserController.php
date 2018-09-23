<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Exception\NoCurrentUserException;
use App\Security\UserResolver;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", methods={"GET"}, name="api_user_get")
 *
 * @View(serializerGroups={"me"})
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class GetUserController
{
    /**
     * @var UserResolver
     */
    private $userResolver;

    /**
     * @param UserResolver $userResolver
     */
    public function __construct(UserResolver $userResolver)
    {
        $this->userResolver = $userResolver;
    }

    /**
     * @throws NoCurrentUserException
     *
     * @return array
     */
    public function __invoke()
    {
        return ['user' => $this->userResolver->getCurrentUser()];
    }
}
