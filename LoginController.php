<?php
use Website\Controller\Action;

class LoginController extends Action {

    /**
     * @param $u (string) - username
     * @param $p (string) - password
     * @return true|false
     */
    public function defaultAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($this->getParam('u') && $this->getParam('u') != '') {
            $user = Object_User::getByUsername($this->getParam('u'), 1);

            if ($user) {
                /**
                 * Checks username and password of an instance of a pimcore-object-class 'user' placed in the path '/users/'.
                 * You can find the adapter here: http://pastebin.com/tgdXpx3s
                 */
                $authAdapter = new Website_Auth_ObjectAdapter('Object_User', 'username', 'password', '/users/');
                $authAdapter->setIdentity($this->getParam('u'))->setCredential($this->getParam('p'));

                $auth = Zend_Auth::getInstance();
                $auth->authenticate($authAdapter);
            }
            
            if ($auth->hasIdentity()) {
                $identity = $auth->getIdentity();
                $this->_helper->json(array('success' => true, 'msg' => $identity));
            }
            else {
                $this->_helper->json(array('success' => false, 'msg' => 'Username and/or password wrong.'));
            }
        }
        else {
            $this->_helper->json(array('success' => false, 'msg' => 'No username given.'));
        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();

        $this->_helper->json(array('success' => true, 'msg' => 'Logged out.'));
    }
}
