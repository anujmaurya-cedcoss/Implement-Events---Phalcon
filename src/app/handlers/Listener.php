<?php
namespace handler\Listener;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;


class Listener extends injectable
{
    public function beforeProductAdd()
    {
        $settings = $this->db->fetchAll("SELECT * FROM settings", \Phalcon\Db\Enum::FETCH_ASSOC);
        if ($settings[0]['title'] == 'with-tag') {
            // change name to name+tag
            $_POST['name'] = $this->request->getPost('name') . '-' . $this->request->getPost('tags');
        }
        if ($this->request->getPost('price') == 0 || !isset($_POST['price'])) {
            $_POST['price'] = $settings[0]['price'];
        }
        if ($this->request->getPost('stock') == 0 || !isset($_POST['stock'])) {
            $_POST['stock'] = $settings[0]['stock'];
        }
    }

    public function beforeOrderAdd()
    {
        $settings = $this->db->fetchAll("SELECT * FROM settings", \Phalcon\Db\Enum::FETCH_ASSOC);
        if ($_POST['zip'] == '') {
            $_POST['zip'] = $settings[0]['zip'];
        }
    }

    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {
        $acl = new Memory();
        /*
         * Add the roles
         */
        if (!isset($this->session->roles)) {
            $this->session->roles = [];
        }
        foreach ($this->session->roles as $value) {
            $acl->addRole($value);
        }
        $acl->addRole('admin');
        $acl->addRole('manager');
        $acl->addRole('guest');
        /*
         * Add the Components
         */
        $actions = [];
        if (!isset($this->session->controllerList)) {
            $this->session->controllerList = [];
        }
        foreach ($this->session->controllerList as $controller => $controllerList) {
            foreach ($controllerList as $key => $action) {
                array_push($actions, $action);
            }
            $acl->addComponent(
                $controller,
                array_values($actions)
            );
            $actions = [];
        }

        $acl->addComponent('aclpage', ['index', 'acl',]);
        $acl->addComponent('addcomponent', ['index', 'add',]);
        $acl->addComponent('index', ['index',]);
        $acl->addComponent('order', ['index', 'add', 'show',]);
        $acl->addComponent('product', ['add', 'index', 'show']);
        $acl->addComponent('role', ['index', 'add',]);
        $acl->addComponent('setting', ['index', 'add',]);

        // access list
        if (!isset($this->session->accessList)) {
            $this->session->accessList = [];
        }
        foreach ($this->session->accessList as $key => $value) {
            if (!is_null($value['role']) && !is_null($value['controller']) && !is_null($value['action']))
                $acl->allow($value['role'], $value['controller'], $value['action']);
        }

        $acl->allow('admin', '*', '*');
        $acl->allow('manager', '*', '*');
        $acl->deny('manager', 'setting', '*');
        $acl->allow('guest', 'index', 'index');
        $acl->allow('guest', 'product', 'show');
        $role = "guest";
        $controller = "index";
        $action = "index";
        if (!empty($dis->getControllerName()))
            $controller = $dis->getControllerName();
        if (!empty($dis->getActionName()))
            $action = $dis->getActionName();
        if (!empty($this->request->get('role')))
            $role = $this->request->get('role');
        if (true === $acl->isAllowed($role, $controller, $action)) {
            if (file_exists(APP_PATH . "/$controller/")) {
                $this->response->redirect($controller / $action);
            } else {
                echo 'Access Granted :)';
            }
        } else {
            echo 'Access denied :(';
            die;
        }
    }
}