<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * UserNormalizer.
 */
final class UserNormalizer implements NormalizerInterface, NormalizerAwareInterface, CacheableSupportsMethodInterface
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
    public function normalize($object, $format = null, array $context = []): array
    {
        /* @var User $object */

        $data = [
            'username' => $object->getUsername(),
            'image' => $object->getImage() ?: 'https://static.productionready.io/images/smiley-cyrus.jpg',
            'bio' => $object->getBio(),
        ];

        if (isset($context['groups']) && \in_array('me', $context['groups'], true)) {
            $data['email'] = $object->getEmail();
            $data['token'] = $this->jwtManager->create($object);
        } else {
            $user = $this->tokenStorage->getToken() !== null ? $this->tokenStorage->getToken()->getUser() : null;
            $data['following'] = $user instanceof User ? $user->follows($object) : false;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
