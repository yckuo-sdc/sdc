<?php
$ld = new MyLDAP();

$data_array = array();
$data_array['base'] = "ou=395000000A,ou=TainanLocalUser,dc=tainan,dc=gov,dc=tw"; 
$data_array['filter'] = "(objectClass=organizationalUnit)";
$data_array['attributes'] = array("name", "description");
$data_ou = $ld->getData($data_array);
?>
<form id='form-ldap' class='ui form' action='javascript:void(0)'>
<h4 class='ui dividing header'>New OU Information</h4>
<div class='inline fields'>
    <div class='twelve wide field'>
    Setting
    <input type='hidden' name='type' value='newou' >
    </div>
    <div class='two wide field'>
        <button id='ldap_clear_btn' class='ui button'>Cancel</button>
    </div>
    <div class='two wide field'>
        <button id='ldap_edit_btn' class='ui button'>Save</button>
    </div>
</div>
<div class='field'>
    <label>上層組織單位<font color='red'>*</font></label>
    <input list='brow' name='upperou' placeholder='請選擇ou'>
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
        名稱<font color='red'>*</font>
    </label>
    <input type='text' name='name' placeholder='名稱' required>
</div>
<div class='field'>
    <label>
        描述<font color='red'>*</font>
    </label>
    <input type='text' name='description' placeholder='描述' required>
</div>
</form>
