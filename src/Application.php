<?php
namespace LinkORB\Realtime;

use Radvance\Framework\BaseWebApplication;
use Radvance\Framework\FrameworkApplicationInterface;
use Silex\Provider\SecurityServiceProvider as SilexSecurityServiceProvider;
use RuntimeException;

use LinkORB\Realtime\Repository;

use LinkORB\Realtime\Util\Form;
use LinkORB\Realtime\Util\JsonHandler;

class Application extends BaseWebApplication implements FrameworkApplicationInterface
{
    private $zmqsocket;

    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->configureZmq();
    }

    public function getRootPath()
    {
        return realpath(__DIR__ . '/../');
    }

    public function getZmqSocket()
    {
        return $this->zmqsocket;
    }

    protected function configureZmq()
    {

        $zmqurl = "tcp://".$this['zmq']['server'].":" . $this['zmq']['port'];
        $context = new \ZMQContext();
        $this->zmqsocket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'socket-websocket');
        $this->zmqsocket->connect($zmqurl);
    }

    protected function configureRepositories()
    {
        $repo = new Repository($this->pdo);
        foreach ($repo->getRepositories() as $repository) {
            $this->addRepository($repository);
        }
    }

    protected function configureSecurity()
    {
        $this->register(new SilexSecurityServiceProvider(), array());

        $security = $this['security'];

        if (isset($security['encoder'])) {
            $digest = sprintf('\\Symfony\\Component\\Security\\Core\\Encoder\\%s', $security['encoder']);
            $this['security.encoder.digest'] = new $digest(true);
        }

        $this['security.firewalls'] = array(
            'assets' => array(
                'security' => false,
                'pattern' => '^/assets',
            ),
            'default' => array(
                'anonymous' => true,
                'pattern' => '^/',
                'form' => array(
                    'login_path' => isset($security['paths']['login']) ?
                                        $security['paths']['login'] : '/login',
                    'check_path' => isset($security['paths']['check']) ?
                                        $security['paths']['check'] : '/authentication/login_check'
                ),
                'logout' => array(
                    'logout_path' => isset($security['paths']['logout']) ? $security['paths']['logout'] : '/logout'
                ),
                'users' => $this->getUserSecurityProvider(),
            ),
        );

        $this['security.access_rules'] = array(
            array('^/auth', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/api', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/', 'IS_AUTHENTICATED_FULLY'),
        );
    }

    public function getForm()
    {
        return new Form($this);
    }

    public function getJsonHandler()
    {
        return new JsonHandler( $this );
    }

    public function verfiySecret($params)
    {
        $rt_app = $this->getRepository('app')->findOneOrNullBy([ 'id' => $params['app_id']]);
        if ($rt_app) {
            if ($rt_app->getSecret() == $params['secret']) {
                return $rt_app;
            }
        }
        return false;
    }
}
