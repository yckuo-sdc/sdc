<?php
$TopOU = $_GET['TopOU'];

$ld = new MyLDAP();

switch ($TopOU) {
    case "YongHua":
        $move_ou = "ou=YongHua, ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
        break;
    case "MinJhih":
        $move_ou = "ou=MinJhih, ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
        break;
    case "District":
        $move_ou = "ou=District, ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
        break;
}

$data_array = array();
$data_array['base'] = $move_ou;
$data_array['filter'] = "(objectClass=organizationalUnit)";
$data_array['attributes'] = array("name", "description");
$data_ou = $ld->getData($data_array);
?>

<?php foreach($data_ou as $ou): ?>
    <?php if(isset($ou['description'])): ?>
        <option value='<?=$ou['name']?>(<?=$ou['description']?>)'>
    <?php else: ?>
        <option value='<?=$ou['name']?>'>
    <?php endif ?>	
<?php endforeach ?>



