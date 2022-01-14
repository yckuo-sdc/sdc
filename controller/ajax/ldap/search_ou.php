<?php
$ld = new MyLDAP();
$timestamp_attribute_array = array("pwdlastset", "lastlogon", "badpasswordtime", "lastlogontimestamp", "lockouttime");

$search_ou = "ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw";
$move_ou = "ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw";
$filter = "(&(ou=" . $target . "*)(objectClass=organizationalUnit))";			

$data_array = array();
$data_array['base'] = $search_ou;
$data_array['filter'] = $filter;
$data = $ld->getData($data_array);

$data_array = array();
$data_array['base'] = $move_ou;
$data_array['filter'] = "(objectClass=organizationalUnit)";
$data_array['attributes'] = array("name", "description");
$data_ou = $ld->getData($data_array);

$input_array = array("description");
?>

<p><?=count($data)?> entries returned from <?=$search_ou?></p>

<?php foreach($data as $entry): ?>
<?php 
$value_array = array();
foreach($input_array as $input):
    $value_array[$input] = empty($entry[$input]) ? "" : $entry[$input];
endforeach; 

$sections = explode(",", $entry['distinguishedname']);

$data_array = array();
$data_array['base'] = $move_ou;
$data_array['filter'] = "(". $sections[1] . ")";
$data_array['attributes'] = array("name", "description");
$data_upperou = $ld->getData($data_array);
$upperou = empty($data_upperou) ? array("name" => "", "description" => "") : $data_upperou[0];
?>
<form id='form-ldap' class='ui form' action='javascript:void(0)'>
    <h4 class='ui dividing header'>Entry Information</h4>
    <div class='inline fields'>
        <div class='twelve wide field'>
            <i class="folder icon"></i>
            <?=$entry['name']?>
            <input type='hidden' name='type' value='editou' >
            <input type='hidden' name='name' value='<?=$entry['name']?>' >
        </div>
        <div class='two wide field'>
            <button type='button' id='ldap_clear_btn' class='ui button'>Cancel</button>
        </div>
        <div class='two wide field'>
            <button type='submit' id='ldap_save_btn' class='ui button'>Save</button>
        </div>
    </div>
    <div class='field'>
        <label>上層組織單位</label>
        <input list='brow' name='upperou' value='<?=$upperou['name']?>(<?=$upperou['description']?>)' placeholder='請選擇ou'>
        <datalist id='brow' name='upperou'>
            <?php foreach($data_ou as $ou): ?>
                <?php if(isset($ou['description'])): ?>
                    <option value='<?=$ou['name']?>(<?=$ou['description']?>)'>
                <?php else: ?>
                    <option value='<?=$ou['name']?>'>
                <?php endif ?>	
            <?php endforeach ?>
        </datalist>
    </div>
    <div class='field'>
        <label>
           描述<font color='red'>*</font>
        </label>
        <input type='text' name='description' value='<?=$value_array['description']?>' placeholder='描述' required>
    </div>
    <div class='ui ordered list'>
        <?php foreach($entry as $key => $val): ?>
            <?php if ($key == "distinguishedname") : ?>
                <?php $ou_description = $ld->getAllOUDescription($search_ou, $val); ?>
                <div class='item'><?=$key?>: <?=$val?> <br> <?=$ou_description?></div> 
            <?php elseif ($key == "dnshostname") : ?>
                <?php $output = shell_exec("/usr/bin/dig +short ".$val) ?>
                <div class='item'><?=$key?>: <?=$val?> | IP: <?=$output?></div>
            <?php elseif (in_array($key, $timestamp_attribute_array)) : ?>
                <div class='item'><?=$key?>: <?=WindowsTime2DateTime($val)?></div>
            <?php elseif ($key == "useraccountcontrol") : ?>
                <div class='item'><?=$key?>: <?=$val ." = (" . getUACDescription($val) . ")"?></div>
            <?php else : ?>
                <div class='item'><?=$key?>: <?=$val?></div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</form>
<p></p>
<?php endforeach ?>
