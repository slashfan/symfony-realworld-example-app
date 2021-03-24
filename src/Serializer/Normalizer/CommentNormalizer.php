<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Comment;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CommentNormalizer implements NormalizerInterface, NormalizerAwareInterface, CacheableSupportsMethodInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /* @var Comment $object */

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
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Comment;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
