<?php
	if(isset($_POST['prohibit'])) {
		if(is_numeric($_POST['value1']) && ($_POST['value2']=='' || is_numeric($_POST['value2'])))
			if($_POST['value2']=='')
				add_item_off($_POST['value1'], 0);
			else if($_POST['value1'] <= $_POST['value2'])
				add_item_off($_POST['value1'], $_POST['value2']);
	}
	if(isset($_POST['active'])) {
		if(is_numeric($_POST['value1']) && ($_POST['value2']=='' || is_numeric($_POST['value2'])))
			if($_POST['value2']=='')
				delete_multiple_item_off($_POST['value1'], 0);
			else if($_POST['value1'] <= $_POST['value2'])
				delete_multiple_item_off($_POST['value1'], $_POST['value2']);
	}
	$remove = isset($_GET['remove']) ? $_GET['remove'] : null;
	if($remove)
		delete_item_off($remove);
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<ul class="panel-title nav nav-tabs">
			<li class="active"><a  href="#1" data-toggle="tab"><?php print $lang['f_tab1']; ?></a></li>
			<li><a href="#2" data-toggle="tab"><?php print $lang['f_tab2']; ?></a></li>
			<li><a href="#3" data-toggle="tab"><?php print $lang['f_tab3']; ?></a></li>
		</ul>
	</div>
	<div class="panel-body">


		<div class="tab-content ">
			<div class="tab-pane active" id="1">

				<table class="table table-striped table-hover ">
					<thead>
						<tr>
							<th>img</th>
							<th><?php print $lang['name']; ?></th>
							<th>#</th>
						</tr>
					</thead>
					<tbody>				
				<?php
					$perpage = 30;
					$total = intval(count_items_off());
					
					$pages  = ceil($total / $perpage);
					
					$get_pages = isset($_GET['n']) ? $_GET['n'] : 1;
					
					$data = array(
							'options' => array(
							'default'   => 1,
							'min_range' => 1,
							'max_range' => $pages
							)
					);
					
					$number = trim($get_pages);
					$number = filter_var($number, FILTER_VALIDATE_INT, $data);
					$range  = $perpage * ($number - 1);

					$prev = $number - 1;
					$next = $number + 1;
						
					$stmt = $sqlite->prepare("SELECT id, item FROM items_off ORDER BY id DESC LIMIT :limit, :perpage");
					$stmt->bindParam(':perpage', $perpage, PDO::PARAM_INT);
					$stmt->bindParam(':limit', $range, PDO::PARAM_INT);
					$stmt->execute();
						
					$result = $stmt->fetchAll();

		
					if($result && count($result) > 0)
					{
							foreach($result as $key => $row)
							{
				?>
						<tr>
							<td><img src="images/items/<?php print get_item_image($row['item']); ?>.png"></td>
							<td><?php print get_item_name($row['item']); ?></td>
							<td><a href="?p=admin&a=forbidden&remove=<?php print $row['item']; ?>" class="btn btn-primary btn-sm"><?php print $lang['item_remove']; ?></a></td>
						</tr>
				<?php
							}
				?>
				<?php
						print '<center><ul class="pagination pagination-lg">';

						if($pages==1)
						{
							print '<li class="disabled"><a href="#">&laquo;</a></li>';
							print '<li class="disabled"><a href="#">&raquo;</a></li>';
						}
						elseif($number <= 1)
						{
							print '<li class="disabled"><a href="#">&laquo;</a></li>';
							print '<li><a href="?p=admin&a=forbidden&n='.$next.'">&raquo;</a></li>';
						}
						elseif($number >= $pages)
						{
							print '<li><a href="?p=admin&a=forbidden&n='.$prev.'">&laquo;</a></li>';
							print '<li class="disabled"><a href="#">&raquo;</a></li>';
						}
						else
						{
							print '<li><a href="?p=admin&a=forbidden&n='.$prev.'">&laquo;</a></li>';
							print '<li><a href="?p=admin&a=forbidden&n='.$next.'">&raquo;</a></li>';
						}
						
						print '</ul></center>';
					}

					else
					{
				?>
				<div class="alert alert-dismissible alert-info">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<p>Nothing found.</p>
				</div>
				<?php
					}
					
				?>
					</tbody>
				</table> 
			
			</div>
			<div class="tab-pane" id="2">
				<div class="alert alert-dismissible alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php print $lang['f_info_tab2']; ?>
				</div>
				<form action="" method="post" class="form-horizontal">
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print $lang['from']; ?></label>
								<input class="form-control" name="value1" type="number">
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print $lang['to']; ?></label>
								<input class="form-control" name="value2" type="number">
							</div>
						</div>
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<input class="btn btn-danger btn-block" name="prohibit" value="<?php print $lang['prohibit_trade']; ?>" type="submit">
							</div>
						</div>
						<div class="col-md-1"></div>
					</div>

				</form>
			</div>
			<div class="tab-pane" id="3">
				<div class="alert alert-dismissible alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php print $lang['f_info_tab2']; ?>
				</div>
				<form action="" method="post" class="form-horizontal">
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print $lang['from']; ?></label>
								<input class="form-control" name="value1" type="number">
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print $lang['to']; ?></label>
								<input class="form-control" name="value2" type="number">
							</div>
						</div>
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<input class="btn btn-danger btn-block" name="active" value="<?php print $lang['prohibit_trade_off']; ?>" type="submit">
							</div>
						</div>
						<div class="col-md-1"></div>
					</div>

				</form>
			</div>
		</div>



	</div>
</div>