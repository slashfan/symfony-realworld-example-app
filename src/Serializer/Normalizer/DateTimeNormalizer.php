<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Carbon\Carbon;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * DateTimeNormalizer.
 */
final class DateTimeNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): ?string
    {
        return Carbon::instance($object)->toISOString();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof \DateTime;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
