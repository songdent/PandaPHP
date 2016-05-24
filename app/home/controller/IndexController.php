<?php
namespace home\controller;

use home\model\ExampleModel;
use pandaphp\mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $welcomeContent = (new ExampleModel())->getWelcomeContent();
        $this->assign('welcome', $welcomeContent);
        $this->display();
    }
}