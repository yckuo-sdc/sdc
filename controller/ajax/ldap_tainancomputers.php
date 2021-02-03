<?php
$ld = new MyLDAP();
$base = "ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
?>

<div class='ui list'>
    <?=$ld->createTree($base, "TainanComputer", "永華及民治公務個人電腦") ?>
</div>
