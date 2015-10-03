<?php
namespace Users\Notification\Message;

use Users\Model\Entity\User;
use Users\Notification\Message\BaseMessage;

/**
 * Notifies user when account is activated.
 *
 */
class ActivatedMessage extends BaseMessage
{

    /**
     * {@inheritDoc}
     */
    public function send()
    {
//        $this
//            ->subject(plugin('User')->settings['message_activation_subject'])
//            ->body(plugin('User')->settings['message_activation_body']);
//
//        if (plugin('User')->settings['message_activation']) {
//            return parent::send();
//        }
        return true;
    }
}
