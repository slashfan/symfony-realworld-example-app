<?php

namespace App\Serializer\Normalizer;

use App\Entity\Article;
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
            'tagList' => [],
            'createdAt' => $object->getCreatedAt(),
            'updatedAt' => $object->getCreatedAt(),
            'favorited' => false,
            'favoritesCount' => 0,
            'author' => $this->normalizer->normalize($object->getAuthor()),
        ];

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
