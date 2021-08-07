<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Security\UserResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\Form\FormInterface;

/**
 * Intermediate controller allowing to provide additional shorthand methods to every child controller.
 */
class AbstractController extends BaseAbstractController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            // injecting the service here allows it to be used in the self::getCurrentUser() method
            // without having to inject it in every child controller __construct() method
            UserResolver::class,
        ]);
    }

    /**
     * Shorthand method to create named forms.
     */
    protected function createNamedForm(string $name, string $type, $data = null, array $options = []): FormInterface
    {
        return $this->get('form.factory')->createNamed($name, $type, $data, $options);
    }

    /**
     * Shorthand method to fetch the authenticated user, it also allows to forget about testing the user class
     * everywhere in the child controllers.
     */
    protected function getCurrentUser(): User
    {
        return $this->get(UserResolver::class)->getCurrentUser();
    }
}
