<!--query_fetch-->
<div id="page" class="container">
<div id="content">
	<div class="sub-content show">
		<div class="post">
			<div class="post_title">Fetch Google Sheets and GCB</div>
			<div class="post_cell">
				<button id="gs_event_btn" class="ui button">Fetch Event GS</button>
				<button id="gs_ncert_btn" class="ui button">Fetch Ncert GS</button>
				<button id="gcb_api_btn" class="ui button">Fetch GCB</button>
				<div class="retrieve_info"></div>
			</div>
		</div>
		<?php 
		if(isset($_SESSION['Level']) && $_SESSION['Level'] == 2){	// admin is given permission to edit this block	
		?>
		<div class="post">
			<div class="post_title">Fetch Ncert</div>
			<div class="post_cell">
				1.從Ncert下載資安人員列表，另存成csv檔且修改編碼為UTF-8。<br> 
				2.上傳此csv檔可更新「資安聯絡人」資料，並顯示已更新數量。
				<p><p>
				<form id="upload_Form" action="ajax/upload_contact.php" method="post">
				<div class="ui action input">
					<input type="text" placeholder="File" readonly>
					<input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
					<div class="ui icon button"><i class="attach icon"></i></div>
				</div>
				<p><input type="submit" value="Submit" class="ui button" name="submit" style="margin-top:1em"/></p>
				</form>
				<div class="retrieve_ncert"></div>
            </div><!--End of post_cell-->
        </div><!--End of post-->
		<?php } ?>
    </div><!--End of sub-content-->
	<div style="clear: both;"></div>
</div><!-- end #content -->
</div> <!--end #page-->
