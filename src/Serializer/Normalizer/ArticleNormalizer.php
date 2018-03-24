<?php

namespace App\Serializer\Normalizer;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * ArticleNormalizer.
 */
class ArticleNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var Article $object */

        $data = [
            'slug' => $object->getSlug(),
            'title' => $object->getTitle(),
            'description' => $object->getDescription(),
            'body' => $object->getBody(),
            'tagList' => array_map(function (Tag $tag) {
                return $this->normalizer->normalize($tag);
            }, $object->getTags()->toArray()),
            'createdAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'updatedAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'favorited' => false,
            'favoritesCount' => $object->getFavoritedByCount(),
            'author' => $this->normalizer->normalize($object->getAuthor()),
        ];

        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if ($user instanceof User) {
            $data['favorited'] = $user->hasFavorite($object);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Article;
    }
}
