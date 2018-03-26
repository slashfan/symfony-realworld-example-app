<?php

namespace App\Controller\Tag;

use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tags", name="api_tags_list")
 * @Method("GET")
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
