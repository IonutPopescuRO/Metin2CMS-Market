<div class="well">
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title"><?php print $lang['inventory']; ?></h3>
				</div>
				<div class="panel-body">
				<?php
					$one_item = 0;
					if(items_number($character)) {
						$list = array();
						$list = items_list($character);
						if(isset($list))
						foreach($list as $row) {
							if(check_item_available($row['vnum'])) {
								$one_item = 1;
				?>
					<a href="#<?php print $row['pos']; ?>" role="tab" data-toggle="tab">
						<div class="col-md-2">
							<div class="panel panel-default" style="min-height: 128px;">
								<div class="panel-body ">
									<img class="image-item" src="images/items/<?php print get_item_image($row['vnum']); ?>.png">
								</div>
							</div>
						</div>
					</a>
				<?php }
					}
				} if(!$one_item) print $lang['no_objects']; ?>
						
				</div>
				
			</div>
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
					if(isset($list)) {
						foreach($list as $row) {
							if(check_item_available($row['vnum'])) {
					?>
						<div role="tabpanel" class="tab-pane fade <?php if(!$i) print 'active in'; ?>" id="<?php print $row['pos']; ?>">
							<center>
								<img src="images/items/<?php print get_item_image($row['vnum']); ?>.png">
								<h3><?php print get_item_name($row['vnum']); ?></h3>
							</center>
							<?php if($row['count']>1) { ?>
							<hr>
							<center><p class="text-info"><b><?php print ucfirst(strtolower($lang['quantity'])).': '.$row['count']; ?></b></p></center>
							<?php } ?>
							<hr>
							<center><?php get_item_bonuses($row['pos'], $character); ?></center>
							<?php
								if(check_item_sash($row['vnum'])) {
							?>
							<center><?php get_sash_bonuses($row['pos'], $character); ?></center>
							<?php
								}
								get_item_stones($row['pos'], $character);
								$lvl = get_item_lvl($row['vnum']);
								if($lvl) {
							?>
							<center><p class="text-danger"><?php print $lang['available_lvl']; ?> <b><?php print $lvl; ?></b></p></center>
							<hr>
							<?php } if(check_item_sash($row['vnum'])) { ?>
							<hr>
							<center><p class="text-warning"><?php print $lang['bonus_absorption']; ?> <b><?php print get_sash_absorption($row['pos'], $character); ?></b>%</p></center>
							<hr>
							<?php } if(get_item_type($row['vnum'])=="ITEM_UNIQUE") { ?>
							<center><p class="text-info"><?php print $lang['time_left']; ?>: <b><?php get_item_time($row['pos'], $character); ?></b></p></center>
							<hr>
							<?php } ?>
							<a href="?p=sell&i=<?php print $row['pos']; ?>&character=<?php print $character; ?>" class="btn btn-danger btn-block"><?php print $lang['sell']; ?></a>
						</div>
					<?php
							$i=1;
							}
						}
					} if(!$one_item) print $lang['no_objects'];;
					?>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
</div>