<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

final class TagArrayToStringTransformer implements DataTransformerInterface
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tags)
    {
        $this->tagRepository = $tags;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): array
    {
        if (!\is_array($value)) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', $value)));
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
