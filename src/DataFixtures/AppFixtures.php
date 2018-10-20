<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Loader\PurgerLoader;

/**
 * AppFixtures.
 */
final class AppFixtures extends Fixture
{
    /**
     * @var PurgerLoader
     */
    private $loader;

    /**
     * @param PurgerLoader $loader
     */
    public function __construct(PurgerLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loader->load([__DIR__ . '/../../fixtures/data.yaml']);
    }
}
