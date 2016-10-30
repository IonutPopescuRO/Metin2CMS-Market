<div class="well">
	<div class="row">
		<div class="col-md-8">
		
		<?php
			$img = get_item_image($vnum);
		
			$perpage = 12;
			$total = intval(item_market_count($vnum));
			
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
			
			$stmt = $sqlite->prepare("SELECT id, count, char_id, gold FROM market WHERE vnum = :vnum ORDER BY gold DESC LIMIT :limit, :perpage");
			$stmt->bindParam(':vnum', $vnum, PDO::PARAM_INT);
			$stmt->bindParam(':perpage', $perpage, PDO::PARAM_INT);
			$stmt->bindParam(':limit', $range, PDO::PARAM_INT);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			if($result && count($result) > 0)
			{
				foreach($result as $key => $row)
				{
		?>
			<a href="#<?php print $row['id']; ?>" role="tab" data-toggle="tab">
				<div class="col-md-4">
					<div class="panel panel-success">
						<div class="panel-heading">
							<?php print number_format($row['gold'], 0, '', '.'); ?> yang
						</div>
						<div class="panel-body" style="min-height: 128px;">
							<img class="image-item" src="images/items/<?php print $img; ?>.png">
						</div>
					</div>
				</div>
			</a>
		<?php
				}

			print '<div class="col-md-12"><center><ul class="pagination pagination-lg">';

			if($pages==1)
			{
				print '<li class="disabled"><a href="#">&laquo; '.$lang['previous_page'].'</a></li>';
				print '<li class="disabled"><a href="#">'.$lang['next_page'].' &raquo;</a></li>';
			}
			elseif($number <= 1)
			{
				print '<li class="disabled"><a href="#">&laquo; '.$lang['previous_page'].'</a></li>';
				print '<li><a href="?n='.$next.'">'.$lang['next_page'].' &raquo;</a></li>';
			}
			elseif($number >= $pages)
			{
				print '<li><a href="?n='.$prev.'">&laquo; '.$lang['previous_page'].'</a></li>';
				print '<li class="disabled"><a href="#">'.$lang['next_page'].' &raquo;</a></li>';
			}
			else
			{
				print '<li><a href="?n='.$prev.'">&laquo; '.$lang['previous_page'].'</a></li>';
				print '<li><a href="?n='.$next.'">'.$lang['next_page'].' &raquo;</a></li>';
			}
					
			print '</ul></center></div>';
		}
		?>

		</div>
		
		<div class="col-md-4">

			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><?php print $lang['info_object']; ?></h3>
				</div>
				<div class="panel-body">
					<div id="myTabContent" class="tab-content">
					<?php
						$i=0;
					if(isset($result)) {
						foreach($result as $key => $row) {
					?>
						<div role="tabpanel" class="tab-pane fade <?php if(!$i) print 'active in'; ?>" id="<?php print $row['id']; ?>">
							<center>
								<img src="images/items/<?php print $img; ?>.png">
								<h3><?php print $item_name; ?></h3>
								<hr>
								<?php print $lang['listed_by']; ?> <font color="#67c1f5"><b><?php print get_character_name($row['char_id']); ?></b></font>
								<hr>
								<p><font color="red"><strong><?php print number_format($row['gold'], 0, '', '.'); ?></strong></font> yang</p>
							</center>
							<?php if($row['count']>1) { ?>
							<hr>
							<center><p class="text-info"><b><?php print ucfirst(strtolower($lang['quantity'])).': '.$row['count']; ?></b></p></center>
							<?php } ?>
							<hr>
							<center><?php get_item_bonuses_market($row['id']); ?></center>
							<?php
								if(check_item_sash($vnum)) {
							?>
							<center><?php get_sash_bonuses_market($row['id']); ?></center>
							<?php
								}
								get_item_stones_market($row['id']);
								if($lvl) {
							?>
							<center><p class="text-danger"><?php print $lang['available_lvl']; ?> <b><?php print $lvl; ?></b></p></center>
							<hr>
							<?php } if(check_item_sash($vnum)) { ?>
							<hr>
							<center><p class="text-warning"><?php print $lang['bonus_absorption']; ?> <b><?php print get_sash_absorption_market($row['id']); ?></b>%</p></center>
							<hr>
							<?php } if(get_item_type($vnum)=="ITEM_UNIQUE") { ?>
							<center><p class="text-info"><?php print $lang['time_left']; ?> <b><?php get_item_time_market($row['id']); ?></b></p></center>
							<hr>
							<?php } ?>
							<a href="?p=buy&id=<?php print $row['id']; ?>" class="btn btn-success btn-block"><?php print $lang['buy']; ?></a>
						</div>
					<?php
							$i=1;
						}
					}
					?>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
</div>