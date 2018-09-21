<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * CommentNormalizer.
 */
class CommentNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var Article $object */

        return [
            'id' => $object->getId(),
            'createdAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'updatedAt' => $this->normalizer->normalize($object->getCreatedAt()),
            'body' => $object->getBody(),
            'author' => $this->normalizer->normalize($object->getAuthor()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Comment;
    }
}
