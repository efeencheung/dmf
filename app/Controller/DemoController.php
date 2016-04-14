<?php

namespace Controller;

use DMF\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    public function indexAction(Request $request) 
    {
        return $this->render('demo/index.html');
    }

    public function userAction()
    {
        $conn = $this->container->get('db_connection');

        $userModel = new \Model\UserModel($conn);
        $users = $userModel->getUserList();

        return $this->render('demo/users.html', array('users'=>$users));
    }
}
