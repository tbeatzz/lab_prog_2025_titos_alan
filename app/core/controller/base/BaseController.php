<?php

namespace app\core\controllers\base;

use app\libs\http\Request;
use app\libs\http\Response;

class BaseController{

    protected $view, $scripts, $styles;

    public function __construct($scripts = [], $styles = [])
    {
        $this->view = "";
        $this->scripts = $scripts;
        $this->styles = $styles;
    }
}