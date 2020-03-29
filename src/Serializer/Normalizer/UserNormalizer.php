<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Exception\NoCurrentUserException;
use App\Security\UserResolver;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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

    private UserResolver $userResolver;

    private JWTTokenManagerInterface $jwtManager;

    public function __construct(UserResolver $userResolver, JWTTokenManagerInterface $jwtManager)
    {
        $this->userResolver = $userResolver;
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
            try {
                $user = $this->userResolver->getCurrentUser();
                $data['following'] = $user->follows($object);
            } catch (NoCurrentUserException $exception) {
                $data['following'] = false;
            }
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
