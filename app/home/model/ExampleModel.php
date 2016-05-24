<?php
namespace home\model;

use pandaphp\mvc\Model;

class ExampleModel extends Model
{
    public function getWelcomeContent()
    {
        return '欢迎使用PandaPHP, <a href="https://github.com/songdent/PandaPHP/">PandaPHP\'s Github</a>';
    }
}