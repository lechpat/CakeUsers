<?php
namespace Users\Notification\Message;

use Users\Model\Entity\User;
use Users\Notification\Message\BaseMessage;

/**
 * For sending "welcome" message to user.
 *
 */
class WelcomeMessage extends BaseMessage
{

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        $this
         //   ->subject(plugin('User')->settings['message_welcome_subject'])
        //    ->body(plugin('User')->settings['message_welcome_body']);
        ->subject('Test')
        ->body('Test Body');
        return parent::send();
    }
}
