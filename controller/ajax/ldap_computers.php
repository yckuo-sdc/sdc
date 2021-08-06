<?php
$ld = new MyLDAP();
$base = "cn=Computers, dc=tainan, dc=gov, dc=tw";
?>

<div class='ui list'>
    <?=$ld->createComputerTree($base, "Computers", "網域新增電腦帳戶的預設容器") ?>
</div>
