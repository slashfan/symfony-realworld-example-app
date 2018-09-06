<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * TagArrayToStringTransformer.
 */
class TagArrayToStringTransformer implements DataTransformerInterface
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @param TagRepository $tags
     */
    public function __construct(TagRepository $tags)
    {
        $this->tagRepository = $tags;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($tags): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string): array
    {
        if (false === \is_array($string)) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', $string)));
        $tags = $this->tagRepository->findBy(['name' => $names]);
        $newNames = array_diff($names, $tags);

        foreach ($newNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }

        return $tags;
    }
}
