<?php
namespace Users\Notification\Message;

use BadMethodCallException;
use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Network\Email\Email;
use Cake\Routing\Router;
use Cake\ORM\Entity;

/**
 * Base class for user messages, all message types should extend this class.
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class BaseMessage
{

    use InstanceConfigTrait;
    use ModelAwareTrait;

    /**
     * Default configuration.
     *
     * ### Options:
     *
     * - updateToken: Whether to update user's token before any message is sent,
     *   defaults to true.
     *
     * - emailConfig: Name of the transport configuration to use when sending
     *   emails. This can be define in site's "settings.php" file. Defaults to
     *   `default`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'updateToken' => true,
        'emailConfig' => 'default',
    ];

    /**
     * User for which this message will be sent.
     *
     * @var \User\Model\Entity\User
     */
    protected $_user = null;

    /**
     * Message's subject.
     *
     * @var string
     */
    protected $_subject = '';

    /**
     * Message's body.
     *
     * @var string
     */
    protected $_body = '';

    /**
     * Message constructor.
     *
     * @param \User\Model\Entity\User $user The user for which send this message
     * @param array $config Options for message sender
     */
    public function __construct(Entity $user, array $config = [])
    {
        $this->_user = $user;
        $this->config($config);
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
    }

    /**
     * Gets or sets message's subject.
     *
     * @param string $subject Subject
     * @return $this|string When new value is set, $this is returned for allowing
     *  method chaining. When getting value a string will be returned
     */
    public function subject($subject = null)
    {
        if ($subject !== null) {
            $this->_subject = $subject;
            return $this;
        }
        return $this->_subject;
    }

    /**
     * Gets or sets message's body.
     *
     * @param string $body Body
     * @return $this|string When new value is set, $this is returned for allowing
     *  method chaining. When getting value a string will be returned
     */
    public function body($body = null)
    {
        if ($body !== null) {
            $this->_body = $body;
            return $this;
        }
        return $this->_body;
    }

    /**
     * Sends email message to user.
     *
     * @return bool True on success, false otherwise
     * @throws \BadMethodCallException When "name" or "email" properties are missing
     *  for the provided User entity
     */
    public function send()
    {
        if (!$this->_user->has('email') || !$this->_user->has('name')) {
            throw new BadMethodCallException(__d('user', 'Missing "name" or "email" property when trying to send the email.'));
        }

//        if ($this->config('updateToken') === true) {
//            $this->loadModel('Users.Users');
//            $this->_user->updateToken();
//        }

        $subject = $this->_parseVariables((string)$this->subject());
        $body = $this->_parseVariables((string)$this->body());

        if (empty($subject) || empty($body)) {
            return false;
        }

        $sender = new Email($this->config('emailConfig'));
        $sent = false;
        try {
            $sent = $sender
                ->to($this->_user->get('email'), $this->_user->get('name'))
                ->subject($subject)
                ->send($body);
        } catch (\Exception $e) {
            return false;
        }

        return $sent;
    }

    /**
     * Looks for variables tags in the given message and replaces with their
     * corresponding values. For example, "{{site:name}} will be replaced with
     * user's real name.
     *
     * Message classes can overwrite this method and add their own logic for parsing
     * variables.
     *
     * @param string $text Message where to look for tags.
     * @return string
     */
    protected function _parseVariables($text)
    {
        $user = $this->_user;
        return str_replace([
            '{{user:name}}',
            '{{user:username}}',
            '{{user:email}}',
            '{{user:activation-url}}',
            '{{user:one-time-login-url}}',
            '{{user:cancel-url}}',
            '{{site:name}}',
            '{{site:url}}',
            '{{site:description}}',
            '{{site:slogan}}',
            '{{site:login-url}}',
        ], [
            $user->get('name'),
            '',
//            $user->get('username'),
            $user->get('email'),
            '','','','','','','',''
//            Router::url(['plugin' => 'Users', 'controller' => 'Gateway', 'action' => 'activate', 'prefix' => false, $user->get('token')], true),
//            Router::url(['plugin' => 'Users', 'controller' => 'Gateway', 'action' => 'me', 'prefix' => false, 'token' => $user->get('token')], true),
//            Router::url(['plugin' => 'Users', 'controller' => 'Gateway', 'action' => 'cancel', 'prefix' => false, $user->id, $user->get('cancelCode')], true),
//            option('site_title'),
//            Router::url('/', true),
//            option('site_description'),
//            option('site_slogan'),
//            Router::url(['plugin' => 'User', 'controller' => 'Gateway', 'action' => 'login', 'prefix' => false], true),
        ], $text);
    }
}
