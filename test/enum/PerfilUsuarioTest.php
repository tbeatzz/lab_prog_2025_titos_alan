<?php
require_once "../../app/core/models/enums/PerfilUsuarioEnum.php";

use app\core\models\enums\PerfilUsuarioEnum;

?>

<select name="" id="">
    <option value="">Select</option>

    <?php 
        foreach(PerfilUsuarioEnum::cases() as $perfil){
            echo "<option value=\"{$perfil->value}\">{$perfil->value}</option>";
        }
    ?>

</select>