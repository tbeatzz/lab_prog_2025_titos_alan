<?php

namespace app\core\controllers\base;

use app\libs\http\Request;
use app\libs\http\Response;

class BaseController{

    protected $view, $scripts;

    public function __construct($scripts = [])
    {
        $this->view = "";
        $this->scripts = $scripts;
    }
}