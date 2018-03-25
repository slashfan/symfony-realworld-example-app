<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * UserNormalizer.
 */
class UserNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManager;

    /**
     * @param TokenStorageInterface    $tokenStorage
     * @param JWTTokenManagerInterface $jwtManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $jwtManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtManager = $jwtManager;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var User $object */

        $data = [
            'username' => $object->getUsername(),
            'image' => $object->getImage(),
            'bio' => $object->getBio(),
        ];

        if (isset($context['groups']) && in_array('me', $context['groups'], true)) {
            $data['email'] = $object->getEmail();
            $data['token'] = $this->jwtManager->create($object);
        } else {
            /** @var User $user */
            $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
            $data['following'] = $user instanceof User ? $user->follows($object) : false;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof User;
    }
}
