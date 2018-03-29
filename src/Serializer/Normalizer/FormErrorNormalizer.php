<?php

namespace App\Serializer\Normalizer;

/**
 * FormErrorNormalizer.
 */
class FormErrorNormalizer extends \FOS\RestBundle\Serializer\Normalizer\FormErrorNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);
        $data = $data['errors']['children'];

        $data = array_filter($data, function ($child) {
            return isset($child['errors']) && count($child['errors']) > 0;
        });

        $data = array_map(function ($child) {
            return isset($child['errors']) ? $child['errors'] : [];
        }, $data);

        return $data;
    }
}
