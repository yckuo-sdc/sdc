<?php
foreach ($_GET as $key => $val) {
    ${$key} = $val; // transfer to local parameters
}

$ld = new MyLDAP();
echo $ld->createSingleLevelComputerTree($base, $ou, $description); 
