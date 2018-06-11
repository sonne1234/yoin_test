<?php

namespace App\Domain\NotificationGateway;

use JMS\Serializer\Annotation as JMS;

class Message
{
    public const ENTRY_INSTRUCTION_CREATED = 'ENTRY_INSTRUCTION_CREATED';
    public const ENTRY_INSTRUCTION_CANCELED = 'ENTRY_INSTRUCTION_CANCELED';
    public const VISITOR_ARRIVED = 'VISITOR_ARRIVED';
    public const BOOKING_APPROVED = 'BOOKING_APPROVED';
    public const BOOKING_DECLINED = 'BOOKING_DECLINED';
    public const BOOKING_CANCELED = 'BOOKING_CANCELED';
    public const BOOKING_WAITING_FOR_PAYMENT = 'BOOKING_WAITING_FOR_PAYMENT';
    public const AMENITY_ACTIVATED = 'AMENITY_ACTIVATED';
    public const AMENITY_DEACTIVATED = 'AMENITY_DEACTIVATED';
    public const NEW_COMMENT_FOR_SERVICE_REQUEST = 'NEW_COMMENT_FOR_SERVICE_REQUEST';
    public const NEW_COMMENT_FOR_SUPPORT_TICKET = 'NEW_COMMENT_FOR_SUPPORT_TICKET';
    public const NEW_ANNOUNCEMENT = 'NEW_ANNOUNCEMENT';
    public const MAINTENANCE_FEE_CHANGED = 'MAINTENANCE_FEE_CHANGED';
    public const MAINTENANCE_FEE_PENDING = 'MAINTENANCE_FEE_PENDING';
    public const MAINTENANCE_FEE_PAID = 'MAINTENANCE_FEE_PAID';
    public const MAINTENANCE_FEE_OVERDUE = 'MAINTENANCE_FEE_OVERDUE';

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $key;

    /**
     * @var array
     * @JMS\Type("array<string>")
     */
    private $args;

    /**
     * Message constructor.
     *
     * @param string $key
     * @param array  $args
     */
    public function __construct(string $key, array $args)
    {
        $this->key = $key;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
