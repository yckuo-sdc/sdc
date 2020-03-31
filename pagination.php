<?php				   
function pagination($lower_bound,$upper_bound,$Totalpages,$page,$mainpage,$subpage,$tab,$page) {
	$result ="";
	//The href-link of bottom pages
	$result .="<div class='ui pagination menu'>";	
	$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=1'>首頁</a>";
	for ($j = $lower_bound; $j <= $upper_bound ;$j++){
		if($j == $pages){
			$result .="<a class='active item bold' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=".$j."'>".$j."</a>";
		}else{
			$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=".$j."'>".$j."</a>";
		}
	}
	$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpate."&tab=".$tab."&page=".$next_page."'> → </a>";		
	//last page
	$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpate."&tab=".$tab."&page=".$Totalpages."'>末頁 </a>";		
	$result .="</div>";
   
	//The mobile href-link of bottom pages
	$result .="<div class='ui pagination menu mobile'>";	
		$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=".$prev_page."'> ← </a>";
		$result .="<a class='active item bold' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=".$pages."'>(".$pages."/".$Totalpages.")</a>";
		$result .="<a class='item test' href='?mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=".$next_page."'> → </a>";		
	$result .="</div>";
	return $result;
}
?>
