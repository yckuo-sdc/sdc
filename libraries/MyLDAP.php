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

	    $result = ldap_search($this->ldapconn, $base, $filter, $attributes);
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

    public function createTree($base, $ou ,$ou_description) {
        $html = "";
        $data_array = array();
        $data_array['base'] = $base;
        $data_array['filter'] = "(objectCategory=*)";
        $data_array['attributes'] = array("cn");
        $data = $this->getData($data_array);

        if(empty($data)){
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
                                }else{
                                    $uac = true;
                                    $uac_status = "";
                                }
                                $html .= "<div class='computer item' cn='".$list['cn']."' uac='".$uac."'>";
                                    $html .= "<i class='desktop icon'></i> ";
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
                                $html .= $this->createTree($sub_base, $sub_ou, $sub_ou_description); 
                            }
                        $html .= "</div>";
                    }

            $html .= "</div>";
        $html .= "</div>";

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
