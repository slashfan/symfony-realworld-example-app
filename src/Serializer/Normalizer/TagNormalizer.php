<?php

namespace App\Serializer\Normalizer;

use App\Entity\Tag;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * TagNormalizer.
 */
class TagNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var Tag $object */

        return $object->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Tag;
    }
}
