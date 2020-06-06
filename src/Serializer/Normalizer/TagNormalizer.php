<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Tag;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * TagNormalizer.
 */
final class TagNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): string
    {
        /* @var Tag $object */
        return $object->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Tag;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
