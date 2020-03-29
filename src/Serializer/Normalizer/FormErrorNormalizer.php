<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use FOS\RestBundle\Serializer\Normalizer\FormErrorNormalizer as FOSRestFormErrorNormalizer;

/**
 * FormErrorNormalizer.
 */
final class FormErrorNormalizer extends FOSRestFormErrorNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $data = parent::normalize($object, $format, $context);

        if (!\is_array($data)) {
            throw new \RuntimeException('Normalized form data should be of type array.');
        }

        /** @var array $data */
        $data = $data['errors']['children'];
        $data = \array_filter($data, fn (array $child) => isset($child['errors']) && \count($child['errors']) > 0);

        return \array_map(fn (array $child) => $child['errors'] ?? [], $data);
    }
}
