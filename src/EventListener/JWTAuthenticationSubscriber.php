<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Serializer\Normalizer\UserNormalizer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events as JWTEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final class JWTAuthenticationSubscriber implements EventSubscriberInterface
{
    private UserNormalizer $normalizer;

    public function __construct(UserNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JWTEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        try {
            $userData = $this->normalizer->normalize($user, null, ['groups' => ['me']]);
            $eventData = $event->getData();
        } catch (ExceptionInterface $exception) {
            return;
        }

        $event->setData(['user' => \array_merge($userData, $eventData)]);
    }
}
