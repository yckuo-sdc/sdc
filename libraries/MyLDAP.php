<?php
class MyLDAP {
  
	private $ldapconn ;

    public function __construct() {
        $ldapconn = ldap_connect(LDAP::HOST) or die("Could not connect to LDAP server.");
        $set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldap_bd = ldap_bind($ldapconn, LDAP::USERNAME . "@" . LDAP::DOMAIN, LDAP::PASSWORD);
        $this->ldapconn = $ldapconn;
    }

    public function __destruct() {
        ldap_unbind($this->ldapconn);
    }

	/**
	 * Get all result entries.
	 *
	 * @param array $data_array.
	 * @return array|false result array.
	 */
    public function getData($data_array = array()){
        if(empty($data_array)) return false;
        $base = $data_array['base'];
        $filter = $data_array['filter'];
        $attributes = empty($data_array['attributes']) ? array() : $data_array['attributes'];
        $sizelimit = empty($data_array['sizelimit']) ? -1 : $data_array['sizelimit'];

	    $result = ldap_search($this->ldapconn, $base, $filter, $attributes, $attributes_only = 0, $sizelimit);
        $entries = ldap_get_entries($this->ldapconn, $result);

        $data = array();
        for($i=0; $i<$entries["count"]; $i++) {
            for($j=0; $j<$entries[$i]["count"]; $j++) {
                if(empty($entries[$i][$entries[$i][$j]][0])){
                    continue;
                }
                $data[$i][$entries[$i][$j]] = $entries[$i][$entries[$i][$j]][0];
            }
        }

        return $data;
    }

    public function getList($data_array = array()){
        if(empty($data_array)) return false;
        $base = $data_array['base'];
        $filter = $data_array['filter'];
        $attributes = empty($data_array['attributes']) ? array() : $data_array['attributes'];

	    $result = ldap_list($this->ldapconn, $base, $filter, $attributes);
        $entries = ldap_get_entries($this->ldapconn, $result);

        $data = array();
        for($i=0; $i<$entries["count"]; $i++) {
            for($j=0; $j<$entries[$i]["count"]; $j++) {
                if(empty($entries[$i][$entries[$i][$j]][0])){
                    continue;
                }
                $data[$i][$entries[$i][$j]] = $entries[$i][$entries[$i][$j]][0];
            }
        }

        return $data;
    }

    public function createComputerTree($base, $ou ,$ou_description) {
        $html = "";
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=*)";
        $data_array['attributes'] = array("distinguishedname");
        $lists = $this->getList($data_array);

        if (empty($lists)) {
            $html .= "<div class='item'>";
                $html .= "<i class='folder icon'></i>";
                $html .= "<div class='content'>";
                    $html .= "<div class='header'>".$ou."(".$ou_description.")</div>";	
                $html .= "</div>";
            $html .= "</div>";
            return $html;
        }

        $html .= "<div class='item hide'>";
            $html .= "<i class='plus square outline icon'></i>";
            $html .= "<i class='folder icon'></i>";
            $html .= "<div class='content'>";
                    $html .= "<div class='header'>".$ou."(".$ou_description.")</div>";	

                    $data_array = array();
                    $data_array['base'] = $base;
                    $data_array['filter'] = "(objectCategory=computer)";
                    $data_array['attributes'] = array("cn", "useraccountcontrol");
                    $lists = $this->getList($data_array);

                    if(!empty($lists)) {
                        $html .= "<div class='list'>";
                            foreach($lists as $list) {
                                if(isDisable($list['useraccountcontrol'])){
                                    $uac = false;
                                    $uac_status = "__已停用";
                                    $computer_icon = "<i class='desktop icon'></i>";
                                }else{
                                    $uac = true;
                                    $uac_status = "";
                                    $computer_icon = "<i class='blue desktop icon'></i>";
                                }
                                $html .= "<div class='computer item' cn='".$list['cn']."' uac='".$uac."'>";
                                    $html .= $computer_icon . "&nbsp;";
                                    $html .= $list['cn'];
                                    $html .= $uac_status;
                                $html .= "</div>";
                            }
                        $html .= "</div>";
                    }

                    $data_array = array();
                    $data_array['base'] = $base;
                    $data_array['filter'] = "(objectCategory=organizationalUnit)";
                    $data_array['attributes'] = array("ou", "distinguishedname", "description");
                    $lists = $this->getList($data_array);

                    if(!empty($lists)) {
                        $html .= "<div class='list'>";
                            foreach($lists as $list) {
                                $sub_base = $list["distinguishedname"];
                                $sub_ou = $list["ou"];
                                $sub_ou_description = empty($list["description"]) ?  "" : $list["description"];
                                $html .= $this->createComputerTree($sub_base, $sub_ou, $sub_ou_description); 
                            }
                        $html .= "</div>";
                    }

            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    public function createSingleLevelComputerTree($base, $ou ,$description) {
        $html = "";

        // computer 
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=computer)";
        $data_array['attributes'] = array("cn", "useraccountcontrol", "description");
        $computer_list = $this->getList($data_array);
    
        if (!empty($computer_list)) {
            $computer_count = count($computer_list);
            $html .= "<div class='list'>";
                $html .= "<div class='item'>共 " . $computer_count . " 筆資料 !</div>";
                foreach($computer_list as $computer) {
                    if(isDisable($computer['useraccountcontrol'])){
                        $uac = false;
                        $uac_status = "__已停用";
                        $computer_icon = "<i class='desktop icon'></i>";
                    }else{
                        $uac = true;
                        $uac_status = "";
                        $computer_icon = "<i class='blue desktop icon'></i>";
                    }
                    $computer_description = empty($computer['description']) ? "" : "(" . $computer['description'] . ")";

                    $html .= "<div class='computer item' cn='" . $computer['cn'] . "' uac='" . $uac . "'>";
                        $html .= $computer_icon . "&nbsp;";
                        $html .= $computer['cn'];
                        $html .= $computer_description;
                        $html .= $uac_status;
                    $html .= "</div>";
                }
            $html .= "</div>";
        }

        // ou
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=organizationalUnit)";
        $data_array['attributes'] = array("ou", "distinguishedname", "description");
        $ou_list = $this->getList($data_array);

        if (!empty($ou_list)) {
            $html .= "<div class='list'>";
                foreach($ou_list as $entry) {
                    $base = $entry['distinguishedname'];
                    $ou = $entry['ou'];
                    $description = empty($entry["description"]) ?  "" : $entry["description"];
                    $text_description = empty($entry["description"]) ?  "" : "(" . $entry["description"] . ")";

                    $data_array = array();
                    $data_array['base'] = $base;
                    $data_array['filter'] = "(objectCategory=*)";
                    $data_array['attributes'] = array("distinguishedname");
                    $list = $this->getList($data_array);

                    if (empty($list)) {
                        $html .= "<div class='item'>";
                            $html .= "<i class='folder icon'></i>";
                            $html .= "<div class='content'>";
                                $html .= "<div class='header'>" . $ou . $text_description . "</div>";	
                            $html .= "</div>";
                        $html .= "</div>";
                    } else {
                        $html .= "<div class='item'>";
                            $html .= "<i class='plus square outline icon' base='" . $base . "' ou='" . $ou . "' description='" . $description . "'></i>";
                            $html .= "<i class='folder icon'></i>";
                            $html .= "<div class='content'>";
                                $html .= "<div class='header'>" . $ou . $text_description . "</div>";	
                            $html .= "</div>";
                        $html .= "</div>";
                    }
                }
                $html .= "</div>";
            }

        return $html;
    }

    public function createSingleLevelUserTree($base, $ou ,$description) {
        $html = "";

        // user
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=person)";
        $data_array['attributes'] = array("cn", "useraccountcontrol", "displayname");
        $user_list = $this->getList($data_array);
    
        if (!empty($user_list)) {
            $user_count = count($user_list);
            $html .= "<div class='list'>";
                $html .= "<div class='item'>共 " . $user_count . " 筆資料 !</div>";
                foreach($user_list as $user) {
                    if (isDisable($user['useraccountcontrol'])) {
                        $uac = false;
                        $uac_status = "__已停用";
                        $user_icon = "<i class='user icon'></i>";
                    } else {
                        $uac = true;
                        $uac_status = "";
                        $user_icon = "<i class='blue user icon'></i>";
                    }
                    $displayname = empty($user['displayname']) ? "" : "(" . $user['displayname'] . ")";
                    $html .= "<div class='user item' cn='" . $user['cn'] . "' uac='" . $uac . "'>";
                        $html .= $user_icon . "&nbsp;";
                        $html .= $user['cn'];
                        $html .= $displayname;
                        $html .= $uac_status;
                    $html .= "</div>";
                }
            $html .= "</div>";
        }

        // ou
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=organizationalUnit)";
        $data_array['attributes'] = array("ou", "distinguishedname", "description");
        $ou_list = $this->getList($data_array);

        if (!empty($ou_list)) {
            $html .= "<div class='list'>";
                foreach($ou_list as $entry) {
                    $base = $entry['distinguishedname'];
                    $ou = $entry['ou'];
                    $description = empty($entry["description"]) ?  "" : $entry["description"];
                    $text_description = empty($entry["description"]) ?  "" : "(" . $entry["description"] . ")";

                    $data_array = array();
                    $data_array['base'] = $base;
                    $data_array['filter'] = "(objectCategory=*)";
                    $data_array['attributes'] = array("distinguishedname");
                    $list = $this->getList($data_array);

                    if (empty($list)) {
                        $html .= "<div class='item'>";
                            $html .= "<i class='folder icon'></i>";
                            $html .= "<div class='content'>";
                                $html .= "<div class='header'>" . $ou . $text_description . "</div>";	
                            $html .= "</div>";
                        $html .= "</div>";
                    } else {
                        $html .= "<div class='item'>";
                            $html .= "<i class='plus square outline icon' base='" . $base . "' ou='" . $ou . "' description='" . $description . "'></i>";
                            $html .= "<i class='folder icon'></i>";
                            $html .= "<div class='content'>";
                                $html .= "<div class='header'>" . $ou . $text_description . "</div>";	
                            $html .= "</div>";
                        $html .= "</div>";
                    }
                }
                $html .= "</div>";
            }

        return $html;
    }

    public function getSingleOUDescription($base, $distinguishedname){
        $description = "";
        $sections = explode(",", $distinguishedname);
        for($i=0; $i<count($sections); $i++){
            if(substr_compare($sections[$i], "OU", 0, 2) == 0) {
                $data_array = array();
                $data_array['base'] = $base;
                $data_array['filter'] = "(" . $sections[$i] . ")";
                $data_array['attributes'] = array("description");
                $data = $this->getData($data_array);
                $description .= empty($data[0]['description']) ? "" : $data[0]['description'];  
                break;
            }
        }
        return $description;
    }

    public function getAllOUDescription($base, $distinguishedname){
        $description = "";
        $sections = explode(",", $distinguishedname);
        for($i=0; $i<count($sections); $i++){
            if(substr_compare($sections[$i], "OU", 0, 2) == 0) {
                $data_array = array();
                $data_array['base'] = $base;
                $data_array['filter'] = "(" . $sections[$i] . ")";
                $data_array['attributes'] = array("description");
                $data = $this->getData($data_array);
                $description .= empty($data[0]['description']) ? "" : $data[0]['description'];  
                $description .= "/";  
            }
        }
        $description = substr($description, 0, -1);
        return $description;
    }

    public function loginVerification($data_array = array(), &$user_attributes){
        if(empty($data_array)) return false;
        $base = $data_array['base'];
        $account = $data_array['account'];
        $password = $data_array['password'];
        $ldaprdn = $account . "@" . LDAP::DOMAIN;

        try {
			$ldapbind = @ldap_bind($this->ldapconn, $ldaprdn, $password);
            if ($ldapbind) {
                $data_array = array();
                $data_array['base'] = $base;
                $data_array['filter'] = "(cn=" . $account . ")";
                $data_array['attributes'] = array("cn", "displayname");
                $data = $this->getData($data_array)[0];
                $user_attributes = array('username' => $data['displayname']);

                return true;
            }
		} catch(Exception $e) {

		}
        echo "<div class='ui error message'>" . ldap_error($this->ldapconn) . "</div>";

        return false;	
    }


}
