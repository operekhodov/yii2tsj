<?php

use app\models\User;
echo "Данный юзер:<br>";
var_dump(User::getDataThisUser());
echo "<br>Сотрудники организации к которой принадлежит юзер:<br>";
var_dump(User::getDataThisOrg());
echo "<br>Все жители Мкд в котором живет юзер:<br>";
var_dump(User::getDataThisMkd());

?>

