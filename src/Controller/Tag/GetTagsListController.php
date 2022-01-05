<?php

declare(strict_types=1);

namespace App\Controller\Tag;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tags", methods={"GET"}, name="api_tags_list")
 */
final class GetTagsListController extends AbstractController
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $repository)
    {
        $this->tagRepository = $repository;
    }

    public function __invoke(): array
    {
        return ['tags' => $this->tagRepository->findAll()];
    }
}
