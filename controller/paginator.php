<?php
require 'view/header/default.php'; 

$limit = 10;
$links = 4;
$page = isset( $_GET['page']) ? $_GET['page'] : 1;
$query = "SELECT * FROM security_event ORDER BY EventID DESC";

$Paginator = new Paginator($query);
$results = $Paginator->getData($limit, $page);

?>
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
			<table>
			<?php for( $i = 0; $i < count( $results->data ); $i++ ) { ?>
					<tr>
						<td><?php echo $results->data[$i]['EventID']; ?></td>
						<td><?php echo $results->data[$i]['OccurrenceTime']; ?></td>
						<td><?php echo $results->data[$i]['BlockReason']; ?></td>
					</tr>
			<?php } ?>
			</table>
			<p>
			<?php echo $Paginator->createLinks( $links, 'ui pagination menu' ); ?>
			<!--<?php echo $Paginator->createLinks( $links, 'ui pagination menu', ['key'=>'1', 'keyword'=>'kkc'] ); ?>-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->

<?php require 'view/footer/default.php'; ?>

