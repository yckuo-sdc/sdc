<?php session_start(); ?>

<?php


//連接資料庫
//只要此頁面上有用到連接MySQL就要include它
include("mysql_connect.inc.php");
$account = $_POST['account'];
$password = $_POST['password'];

//搜尋資料庫資料
$sql = "SELECT * FROM Member_Table where account = '$account'";
$result = mysql_query($sql);
$row = @mysql_fetch_row($result);

//判斷帳號與密碼是否為空白
//以及MySQL資料庫裡是否有這個會員
if($account != null && $password   != null && $row[2] == $account && $row[3] == $password)
{
        //將帳號寫入session，方便驗證使用者身份
        $_SESSION['account'] = $account;
		$_SESSION['name']  = $row[1];
		$_SESSION['level'] = $row[6];
        echo '登入成功!';
		
		//update login information
		$sql = "UPDATE Member_Table SET last_login ='".date('Y-m-d H:i:s')."' where account = '".$account."'";
		$result = mysql_query($sql);
		echo "<meta http-equiv=REFRESH CONTENT=1;url=menu.php>";
			
}
else
{
        echo '登入失敗!';
        echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>';
}

?>
