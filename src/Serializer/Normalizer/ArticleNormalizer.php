<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ArticleNormalizer implements NormalizerInterface, NormalizerAwareInterface, CacheableSupportsMethodInterface
{
    use NormalizerAwareTrait;

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var Article $object */

        $data = [
            'slug' => $object->getSlug(),
            'title' => $object->getTitle(),
            'description' => $object->getDescription(),
            'body' => $object->getBody(),
            'tagList' => array_map(fn (Tag $tag) => $this->normalizer->normalize($tag), $object->getTags()->toArray()),
            'createdAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'updatedAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'favorited' => false,
            'favoritesCount' => $object->getFavoritedByCount(),
            'author' => $this->normalizer->normalize($object->getAuthor()),
        ];

        $token = $this->tokenStorage->getToken();
        $user = $token !== null ? $token->getUser() : null;

        if ($user instanceof User) {
            $data['favorited'] = $user->hasFavorite($object);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Article;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
