<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * TagsListController.
 *
 * @Route("/api/tags", name="api_tags_list")
 * @Method("GET")
 */
class TagsListController
{
    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return ['tags' => $this->repository->findAll()];
    }
}
