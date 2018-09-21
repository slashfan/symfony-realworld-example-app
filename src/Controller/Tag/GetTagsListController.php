<?php

declare(strict_types=1);

namespace App\Controller\Tag;

use App\Repository\TagRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tags", methods={"GET"}, name="api_tags_list")
 */
final class GetTagsListController
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->tagRepository = $repository;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return ['tags' => $this->tagRepository->findAll()];
    }
}
