<?php

declare(strict_types=1);

namespace Kerox\Messenger\Request;

use Kerox\Messenger\Model\Message;
use Kerox\Messenger\SendInterface;

class SendRequest extends AbstractRequest
{
    public const REQUEST_TYPE_MESSAGE = 'message';
    public const REQUEST_TYPE_ACTION = 'action';

    /**
     * @var array|null
     */
    protected $recipient;

    /**
     * @var string|\Kerox\Messenger\Model\Message|null
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $senderAction;

    /**
     * @var string|null
     */
    protected $notificationType;

    /**
     * @var string|null
     */
    protected $tag;

    /**
     * @var string|null
     */
    protected $personaId;

    /**
     * @var string
     */
    protected $messagingType;

    /**
     * Request constructor.
     *
     * @param string|\Kerox\Messenger\Model\Message $content
     * @param string|array $recipient
     */
    public function __construct(
        string $pageToken,
        $content,
        $recipient = null,
        array $options = [],
        string $requestType = self::REQUEST_TYPE_MESSAGE
    ) {
        parent::__construct($pageToken);

        if ($content instanceof Message || $requestType === self::REQUEST_TYPE_MESSAGE) {
            $this->message = $content;
        } else {
            $this->senderAction = $content;
        }

        $this->recipient = \is_string($recipient) ? ['id' => $recipient] : $recipient;
        $this->messagingType = $options[SendInterface::OPTION_MESSAGING_TYPE] ?? null;
        $this->notificationType = $options[SendInterface::OPTION_NOTIFICATION_TYPE] ?? null;
        $this->tag = $options[SendInterface::OPTION_TAG] ?? null;
        $this->personaId = $options[SendInterface::OPTION_PERSONA_ID] ?? null;
    }

    protected function buildHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function buildBody(): array
    {
        $body = [
            'messaging_type' => $this->messagingType,
            'recipient' => $this->recipient,
            'message' => $this->message,
            'sender_action' => $this->senderAction,
            'notification_type' => $this->notificationType,
            'tag' => $this->tag,
            'persona_id' => $this->personaId,
        ];

        return array_filter($body);
    }
}
