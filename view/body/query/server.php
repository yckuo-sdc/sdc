<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">伺服器資安清單</h2>
				<div class="post_cell">
                    <input type="checkbox" name="include_shutdown_checkbox" checked="checked">
                    <label for="include_shutdown_checkbox">Shutdown</label>&nbsp
                    <input type="checkbox" name="server_only_checkbox">
                    <label for="server_only_checkbox">ServerOnly</label>
                    <table id="example_table" class="ui celled table" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>IP</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Owner</th>
                                <th>Antivirus</th>
                                <th>EDR_CS</th>
                                <th>GCB</th>
                                <th>Shut_at</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>IP</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Owner</th>
                                <th>Antivirus</th>
                                <th>EDR_CS</th>
                                <th>GCB</th>
                                <th>Shut_at</th>
                            </tr>
                        </tfoot>
                    </table>
				</div><!--end of .post_cell-->
                <div class="see_more" style="text-align:right"><a href="https://tndev.tainan.gov.tw/" target="_blank">See More...</a></div>
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
