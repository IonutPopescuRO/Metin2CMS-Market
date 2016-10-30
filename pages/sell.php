<div class="well">
	<div class="row">
		<div class="col-md-8">
			<?php
				if(!isset($_POST['gold'])) {
			?>
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Info:</strong> <?php print $lang['info_sell']; ?>
			</div>
			<?php } ?>
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title"><?php print $lang['sell_object']; ?></h3>
				</div>
				<div class="panel-body">

				<?php
					if(!isset($_POST['gold'])) {
				?>
					<form action="" method="post">
						<div class="form-group">
							<label class="control-label"><?php print $lang['price_object']; ?></label>
							<div class="input-group">
								<span class="input-group-addon">yang</span>
								<input name="gold" class="form-control" type="number" min="0"<?php if($yang_maxim) print ' max="'.$yang_maxim.'"'; ?>>
								<span class="input-group-btn">
								<input type="submit" class="btn btn-success" value="<?php print $lang['sell']; ?>">
							</span>
							</div>
						</div>
					</form>
				
				<?php } else {
					
					$check = false;
					if (check_game_time($character)) {
						if (is_numeric($_POST['gold']))
						{
							$limit = true;
							if(!$yang_maxim)
								$limit = true;
							else {
								if($_POST['gold']>$yang_maxim)
									$limit = false;
							}
							if ($_POST['gold']>0 && $limit)
								$check = sell_item($pos, $character, $_POST['gold']);
							else
							{
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Info:</strong> ERROR
					</div>
				<?php
							}
						}
						else {
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Info:</strong> ERROR
					</div>				
				<?php
						}
						if($check) {
				?>

					<div class="alert alert-dismissible alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Succes!</strong> <?php print $lang['waiting_sell']; ?>
					</div>
					<div class="alert alert-dismissible alert-info">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Info:</strong> <?php print $lang['info_sell2']; ?>
					</div>
				
				<?php } }
					else {
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Info:</strong> <?php print $lang['info_sell']; ?>
					</div>
				<?php
				} } ?>
				
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
							if(check_item_available($item[0]['vnum'])) {
					?>
						<div role="tabpanel" class="tab-pane fade active in" id="<?php print $item[0]['pos']; ?>">
							<center>
								<img src="images/items/<?php print get_item_image($item[0]['vnum']); ?>.png">
								<h3><?php print get_item_name($item[0]['vnum']); ?></h3>
							</center>
							<?php if($item[0]['count']>1) { ?>
							<hr>
							<center><p class="text-info"><b><?php print ucfirst(strtolower($lang['quantity'])).': '.$item[0]['count']; ?></b></p></center>
							<?php } ?>
							<hr>
							<center><?php get_item_bonuses($item[0]['pos'], $character); ?></center>
							<?php
								if(check_item_sash($item[0]['vnum'])) {
							?>
							<center><?php get_sash_bonuses($item[0]['pos'], $character); ?></center>
							<?php
								}
								get_item_stones($item[0]['pos'], $character);
								$lvl = get_item_lvl($item[0]['vnum']);
								if($lvl) {
							?>
							<center><p class="text-danger"><?php print $lang['available_lvl']; ?> <b><?php print $lvl; ?></b></p></center>
							<?php } if(check_item_sash($item[0]['vnum'])) { ?>
							<center><p class="text-warning"><?php print $lang['bonus_absorption']; ?> <b><?php print get_sash_absorption($item[0]['pos'], $character); ?></b>%</p></center>
							<?php } if(get_item_type($item[0]['vnum'])=="ITEM_UNIQUE") { ?>
							<center><p class="text-info"><?php print $lang['time_left']; ?>: <b><?php get_item_time($item[0]['pos'], $character); ?></b></p></center>
							<?php } ?>
						</div>
					<?php
							}
					?>
					</div>
				</div>
			</div>
			<?php 
					if(isset($_POST['gold'])) 
						if($check) 
							delete_item($pos, $character); 
			?>
		</div>
	</div>
	
</div>