<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Serializer\Normalizer\UserNormalizer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events as JWTEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * JWTAuthenticationSubscriber.
 */
final class JWTAuthenticationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserNormalizer
     */
    private $normalizer;

    /**
     * @param UserNormalizer $normalizer
     */
    public function __construct(UserNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            JWTEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $userData = $this->normalizer->normalize($user, null, ['groups' => ['me']]);
        $eventData = $event->getData();

        $event->setData(['user' => \array_merge($userData, $eventData)]);
    }
}
