<?php declare(strict_types=1);

namespace Shopware\Framework\Write\Resource;

use Shopware\Framework\Write\Field\IntField;
use Shopware\Framework\Write\Field\StringField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\Resource;

class SessionsResource extends Resource
{
    protected const SESS_ID_FIELD = 'sessId';
    protected const SESS_TIME_FIELD = 'sessTime';
    protected const SESS_LIFETIME_FIELD = 'sessLifetime';

    public function __construct()
    {
        parent::__construct('sessions');

        $this->primaryKeyFields[self::SESS_ID_FIELD] = (new StringField('sess_id'))->setFlags(new Required());
        $this->fields[self::SESS_TIME_FIELD] = (new IntField('sess_time'))->setFlags(new Required());
        $this->fields[self::SESS_LIFETIME_FIELD] = (new IntField('sess_lifetime'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            \Shopware\Framework\Write\Resource\SessionsResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, array $errors = []): \Shopware\Framework\Event\SessionsWrittenEvent
    {
        $event = new \Shopware\Framework\Event\SessionsWrittenEvent($updates[self::class] ?? [], $errors);

        unset($updates[self::class]);

        if (!empty($updates[\Shopware\Framework\Write\Resource\SessionsResource::class])) {
            $event->addEvent(\Shopware\Framework\Write\Resource\SessionsResource::createWrittenEvent($updates));
        }

        return $event;
    }
}