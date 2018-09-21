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
class JWTAuthenticationSubscriber implements EventSubscriberInterface
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
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data = array_merge(
            $this->normalizer->normalize($user, null, ['groups' => ['me']]),
            $data
        );

        $event->setData(['user' => $data]);
    }
}
