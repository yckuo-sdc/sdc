<!--siderbar-->
<div class="item">
  <a class="ui logo icon image" href="/"><img src="/images/logo.png"></a>
  <a href="/"><b>SDC ISS</b></a>
</div>
<div class="item">
  <div class="header">About</div>
  <div class="menu">
	  <a class="item" href="/about/data/">Data</a>
	  <a class="item" href="/about/structure/">Structure</a>
  </div>
</div>
<div class="item">
  <div class="header">視覺化資訊</div>
  <div class="menu">
      <a class="item" href="/info/enews/">Enews</a>
      <a class="item" href="/info/ranking/">Ranking Data</a>
      <a class="item" href="/info/vul/">VUL</a>
      <a class="item" href="/info/client/">Client</a>
      <a class="item" href="/info/network/">Network</a>
      <a class="item" href="/info/directory/">Directory</a>
  </div>
</div>
<div class="item">
  <div class=" header">資安查詢</div>
  <div class="menu">
		<a class="item" href="/query/event/">市府資安事件</a> 
		<a class="item" href="/query/ncert/">技服資安通報</a> 
		<a class="item" href="/query/contact/">資安聯絡人</a> 
		<a class="item" href="/query/client/">端點資安清單</a> 
		<a class="item" href="/query/fetch/">Fetch GS & Contact</a> 
  </div>
</div>
<div class="item">
  <div class=" header">弱點掃描</div>
  <div class="menu">
      <a class="item" href="/vul/overview/">整體數據</a>
      <a class="item" href="/vul/search/">弱點查詢</a>
      <a class="item" href="/vul/target/">掃描資產</a>
      <a class="item" href="/vul/fetch/">Fetch VUL</a>
  </div>
</div>
<div class="item">
  <div class=" header">網路防護</div>
  <div class="menu">
      <a class="item" href="/network/search/">網路流量日誌</a>
      <a class="item" href="/network/malware/">惡意中繼站</a>
      <a class="item" href="/network/allowlist/">應用程式核可清單</a>
      <a class="item" href="/network/top100/">Top 100流量排名</a>
  </div>
</div>
<div class="item">
  <div class=" header">工具</div>
  <div class="menu">
      <a class="item" href="/tool/nmap/">Nmap</a>
      <a class="item" href="/tool/nslookup/">Nslookup</a>
      <a class="item" href="/tool/ldap/">LDAP</a>
      <a class="item" href="/tool/hydra/">Hydra</a>
  </div>
</div>
<div class="item">
  <div class=" header">相關連結</div>
  <div class="menu">
	<a class="item" href="http://vision.tainan.gov.tw" target="_blank">推動友善資訊服務平台</a>
	<a class="item" href="https://tndev.tainan.gov.tw" target="_blank">MIS網管系統</a>
	<a class="item" href="https://sdc-mrbs.tainan.gov.tw" target="_blank">SDC會議室預約系統</a>
	<a class="item" href="https://sdc-mrbs.tainan.gov.tw/callcenter/login.php" target="_blank">Call Center預約</a>
	<a class="item" href="https://tainan-vsms.chtsecurity.com/cgi-bin/login.pl" target="_blank">弱點掃描管理平台</a>	
	<a class="item" href="http://webad2019.tainan.gov.tw" target="_blank">WebAD</a>
	<a class="item" href="http://eisms.tainan.gov.tw" target="_blank">eISMS</a>
	<a class="item" href="https://sdc-iss.tainan.gov.tw:4000/" target="_blank">OpenVas(開源漏洞掃描工具)</a>	
  </div>
</div>

<a class="item" href="/logout"><b><?php echo $_SESSION['username']."(登出)";?></b></a>

