<?php
if(empty($_GET['target']) || empty($_GET['type'])){
	return ;
}

foreach($_GET as $getkey => $val){
	$$getkey = escapeshellcmd($val);
}

$ld = new MyLDAP();

switch($type){
	case "search":
        $search_ou = ["ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw",
                      "ou=TainanComputer, dc=tainan, dc=gov, dc=tw"];
        $move_ou = ["ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw",
                    "ou=YongHua, ou=TainanComputer, dc=tainan, dc=gov, dc=tw"];
        $filter = "(cn=".$target."*)";			

        $labelArr = ['新密碼','確認密碼','姓名','職稱','mail','電話','分機'];
        $nameArr = ['new_password','confirm_password','displayname','title','mail','telephonenumber','physicaldeliveryofficename'];
        $rArr = ['','','required','required','required','',''];

        for($k=0; $k<count($search_ou); $k++){
            $data_array = array();
            $data_array['base'] = $search_ou[$k];
            $data_array['filter'] = $filter;
            $data = $ld->getData($data_array);

            $data_array = array();
            $data_array['base'] = $move_ou[$k];
            $data_array['filter'] = "(objectClass=organizationalUnit)";
            $data_array['attributes'] = array("name", "description");
            $data_ou = $ld->getData($data_array);

            ?>

            <p><?=count($data)?> entries returned from <?=$search_ou[$k]?></p>

            <?php foreach($data as $entry): ?>
                <form id='form-ldap' class='ui form' action='javascript:void(0)'>
                    <h4 class='ui dividing header'>Entry Information</h4>
                    <div class='fields'>
                        <div class='six wide field'>
                            <?php if($k==0): ?>
                                <?php if(isDisable($entry['useraccountcontrol'])): ?>
                                    <i class='user icon'></i>
                                    <?=$entry['cn']."__已停用"?>
                                <?php else: ?>
                                    <i class='user blue icon'></i>
                                    <?=$entry['cn']?>
                                <?php endif ?>
                                <input type='hidden' name='type' value='edituser' >
                            <?php elseif($k==1): ?>
                                <?php if(isDisable($entry['useraccountcontrol'])): ?>
                                    <i class='desktop icon'></i>
                                    <?=$entry['cn']."__已停用"?>
                                <?php else: ?>
                                    <i class='desktop blue icon'></i>
                                    <?=$entry['cn']?>
                                <?php endif ?>
                                <input type='hidden' name='type' value='changecomputer' >
                            <?php endif ?>
                            <input type='hidden' name='cn' value='<?=$entry['cn']?>' >
                        </div>

                        <div class='six wide field'>
                            <div class='ui toggle checkbox'>
                                <?php if(isDisable($entry['useraccountcontrol'])): ?>
                                    <input type='checkbox' name='isActive' value="true">
                                <?php else: ?>
                                    <input type='checkbox' name='isActive' value="true" checked>
                                <?php endif ?>
                                <label>是否啟用</label>
                            </div>
                        </div>
                        <div class='two wide field'>
                            <button id='ldap_clear_btn' class='ui button'>Cancel</button>
                        </div>
                        <div class='two wide field'>
                            <button id='ldap_edit_btn' class='ui button'>Save</button>
                        </div>
                    </div>
                    <?php if($k==0): ?>
                        <div class='field'>
                            <label>移動單位</label>
                            <input list='brow' name='organizationalUnit' placeholder='請選擇ou'>
                            <datalist id='brow' name='organizationalUnit'>
                                <?php foreach($data_ou as $ou): ?> 
                                    <?php if(isset($ou['description'])): ?>
                                        <option value='<?=$ou['name']?>(<?=$ou['description']?>)'>
                                    <?php else: ?>
                                        <option value='<?=$ou['name']?>'>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <?php foreach($nameArr as $key => $val): ?>
                            <div class='field'>
                                <label>
                                    <?=$labelArr[$key]?><?php echo $r = ($rArr[$key]=='required') ? "<font color='red'>*</font>" : "" ?>
                                </label>
                                <?php if(isset($entry[$val])): ?>
                                    <input type='text' name='<?=$val?>' value='<?=$entry[$val]?>'>
                                <?php else: ?>
                                    <?php if($val == "new_password" || $val == "confirm_password"): ?>
                                        <input type='password' name='<?=$val?>' value='' placeholder='<?=$labelArr[$key]?>' >
                                    <?php else: ?>
                                        <input type='text' name='<?=$val?>' value='' placeholder='<?=$labelArr[$key]?>' >
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                        <?php endforeach ?>
                    <?php elseif($k==1): ?>
                        <div class='inline fields'>
                            <label for='isYonghua'>市政中心</label>
                            <div class='field'>
                                <div class='ui radio checkbox'>
                                    <input type='radio' name='isYonghua' value='true' checked='checked'>
                                    <label>永華</label>
                                </div>
                            </div>
                            <div class='field'>
                                <div class='ui radio checkbox'>
                                    <input type='radio' name='isYonghua' value='false'>
                                    <label>民治</label>
                                </div>
                            </div>
                        </div>
                        <div class='field'>
                            <label>移動單位</label>
                            <input list='brow' name='organizationalUnit' placeholder='請選擇ou'>
                            <datalist id='brow' name='organizationalUnit'>
                                <?php foreach($data_ou as $ou): ?>
                                    <?php if(isset($ou['description'])): ?>
                                        <option value='<?=$ou['name']?>(<?=$ou['description']?>)'>
                                    <?php else: ?>
                                        <option value='<?=$ou['name']?>'>
                                    <?php endif ?>	
                                <?php endforeach ?>
                            </datalist>
                        </div>
                    <?php endif ?>
                    <div class='ui ordered list'>
                        <?php foreach($entry as $key => $val): ?>
                            <?php if($key == "distinguishedname"): ?>
                                <?php $ou_description = $ld->getAllOUDescription($search_ou[$k], $val); ?>
                                <div class='item'><?=$key?>: <?=$val?> <br> <?=$ou_description?></div> 
                            <?php elseif($key == "dnshostname"): ?>
                                <?php $output = shell_exec("/usr/bin/dig +short ".$val) ?>
                                <div class='item'><?=$key?>: <?=$val?> | IP: <?=$output?></div>
                            <?php elseif($key == "pwdlastset" || $key == "lastlogon" || $key == "badpasswordtime"): ?>
                                <div class='item'><?=$key?>: <?=WindowsTime2DateTime($val)?></div>
                            <?php else: ?>
                                <div class='item'><?=$key?>: <?=$val?></div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                    <p></p>
                </div>
            </form>
            <?php endforeach ?>
        <?php }
		break;
	case "newuser":
        $data_array = array();
        $data_array['base'] = "ou=395000000A,ou=TainanLocalUser,dc=tainan,dc=gov,dc=tw"; 
        $data_array['filter'] = "(objectClass=organizationalUnit)";
        $data_array['attributes'] = array("name", "description");
        $data_ou = $ld->getData($data_array);

        $labelArr = ['帳號','新密碼','確認密碼','姓名','職稱','mail','電話','分機'];
        $nameArr = ['cn','new_password','confirm_password','displayname','title','mail','telephonenumber','physicaldeliveryofficename'];
        $rArr = ['required','required','required','required','required','required','',''];
    ?>
        <form id='form-ldap' class='ui form' action='javascript:void(0)'>
        <h4 class='ui dividing header'>New User Information</h4>
        <div class='inline fields'>
            <div class='twelve wide field'>
            Setting
            <input type='hidden' name='type' value='newuser' >
            </div>
            <div class='two wide field'>
                <button id='ldap_clear_btn' class='ui button'>Cancel</button>
            </div>
            <div class='two wide field'>
                <button id='ldap_edit_btn' class='ui button'>Save</button>
            </div>
        </div>
        <div>
        <div class='field'>
            <label>單位<font color='red'>*</font></label>
            <input list='brow' name='organizationalUnit' placeholder='請選擇ou'>
            <datalist id='brow' name='organizationalUnit'>
                <?php foreach($data_ou as $ou): ?>
                    <?php if(isset($ou['description'])): ?>
                        <option value='<?=$ou['name']?>(<?=$ou['description']?>)'>
                    <?php else: ?>
                        <option value='<?=$ou['name']?>'>
                    <?php endif ?>	
                <?php endforeach ?>
            </datalist>
        </div>
        <?php foreach($nameArr as $key => $val): ?>
            <div class='field'>
                <label>
                    <?=$labelArr[$key]?><?php echo $r = ($rArr[$key]=='required') ? "<font color='red'>*</font>" : "" ?>
                </label>
                <?php if($val == "new_password" || $val == "confirm_password"): ?>
                    <input type='password' name='<?=$val?>' value='' placeholder='<?=$labelArr[$key]?>' >
                <?php else: ?>
                    <input type='text' name='<?=$val?>' value='' placeholder='<?=$labelArr[$key]?>' >
                <?php endif ?>
            </div>
        <?php endforeach ?>
        </div>
        </form>
    <?php
    break;
}
