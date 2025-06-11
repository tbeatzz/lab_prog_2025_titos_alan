<?php


require_once "../../app/core/models/dao/base/InterfaceDao.php";
require_once "../../app/core/models/dao/base/BaseDao.php";
require_once "../../app/core/models/dao/CategoriaDao.php";

use app\core\models\dao\CategoriaDao;


$dao = new CategoriaDao(null);
print_r($dao->load(876));