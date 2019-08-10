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
    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);

        if (\is_array($data) === false) {
            return $data;
        }

        /** @var array $data */
        $data = $data['errors']['children'];
        $data = \array_filter($data, function ($child) {
            return isset($child['errors']) && \count($child['errors']) > 0;
        });

        return \array_map(function ($child) {
            return $child['errors'] ?? [];
        }, $data);
    }
}
