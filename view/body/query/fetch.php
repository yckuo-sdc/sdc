<div id="page" class="container">
    <div id="content">
        <div class="sub-content show">
            <div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">GS & Contact 擷取</h2>
                <div class="post_title">Google Sheets and GCB</div>
                <div class="post_cell">
                    <button id="gs_event_btn" class="ui button">Fetch Event GS</button>
                    <button id="gs_ncert_btn" class="ui button">Fetch Ncert GS</button>
                    <button id="gcb_api_btn" class="ui button">Fetch GCB</button>
                    <div class="ui centered inline loader"></div>
                    <div class="fetch_status"></div>
                </div>
                <?php if($_SESSION['level'] == 2):	?>
                    <div class="post_title">Ncert contacts</div>
                    <div class="post_cell upload_contact">
                        1.從<a href="https://www.ncert.nat.gov.tw/" target="_blank">Ncert</a>下載資安人員列表檔並上傳，即可更新「資安聯絡人」資料，並顯示已更新數量。<br> 
                        2.副檔名支援格式：xls、xlsx或csv 。
                        <p><p>
                        <form id="upload_Form" action="ajax/upload_contact.php" method="post">
                        <div class="ui action input">
                            <input type="text" placeholder="File" readonly>
                            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
                            <div class="ui icon button"><i class="attach icon"></i></div>
                        </div>
                        <p><input type="submit" value="Upload" class="ui button" name="submit" style="margin-top:1em"/></p>
                        </form>
                        <div class="record_content"></div>
                    </div><!--End of post_cell-->
                <?php endif ?>
                <?php if($_SESSION['level'] == 2):	?>
                    <!--
                    <div class="post_title">Edr fireeyes</div>
                    <div class="post_cell upload_fireeye">
                        從<a href="https://srm.chtsecurity.com" target="_blank">SRM</a>下載端點列表並上傳，即可更新「fireeye」資料，並顯示已更新數量。
                        <p><p>
                        <form id="upload_Form" action="ajax/upload_fireeye.php" method="post">
                        <div class="ui action input">
                            <input type="text" placeholder="File" readonly>
                            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
                            <div class="ui icon button"><i class="attach icon"></i></div>
                        </div>
                        <p><input type="submit" value="Upload" class="ui button" name="submit" style="margin-top:1em"/></p>
                        </form>
                        <div class="record_content"></div>
                    </div>
                    -->
                <?php endif ?>
            </div><!--End of post-->
        </div><!--End of sub-content-->
        <div style="clear: both;"></div>
    </div><!-- end #content -->
</div> <!--end #page-->
