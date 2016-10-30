<div class="well">
	<div class="row">
		<div class="col-md-8">
			<?php
				$list = array();
				$list = characters_list();
				if(!isset($_POST['buy'])) {
			?>
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Info:</strong> <?php print $lang['info_buy']; ?>
			</div>
			<?php } ?>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title"><?php print $lang['buy_object']; ?></h3>
				</div>
				<div class="panel-body">

				<?php
					if(!isset($_POST['buy'])) {
				?>
					<form action="" method="post">
						<div class="form-group">
							<label class="control-label"><?php print $lang['price_object']; ?></label>
							<div class="input-group">
								<span class="input-group-addon">yang</span>
								<input name="gold" class="form-control" disabled="" value="<?php print number_format($item[0]['gold'], 0, '', '.'); ?>">
								<span class="input-group-btn">
							</span>
							</div>
							
						</div>
							
						<div class="form-group">

							<label class="control-label"><?php print $lang['payment']; ?></label>

							<select class="form-control" id="select" name="method_pay">
								<option value="all"><?php print $lang['auto_pay']; ?></option>
								<?php
									$list = array();
									$list = characters_list();
									
									foreach($list as $row) {
										print '<option ';
										if($row['gold']<$item[0]['gold'] || !check_game_time($row['id']))
											print 'disabled="" ';
										print 'value="'.$row['id'].'">'.$lang['pay_char'] .' '.$row['name'].' [ '.number_format($row['gold'], 0, '', '.').' yang ] ';
										if(!check_game_time($row['id']))
											print '- '.$lang['need_off'];
										print '</option>';
									}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<input type="submit" class="btn btn-danger btn-block" name="buy" value="<?php print $lang['buy']; ?>">
						</div>
					</form>
				
				<?php } else {
					$check_gold = true;
					$offline = true;
					$success = false;
					
					if($item[0]['gold']>total_gold()) {
						$check_gold = false;
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php print $lang['yang_low']; ?>
					</div>
				<?php
					}

					if ($check_gold) {

						if(!is_numeric($_POST['method_pay']))
						{
							foreach($list as $row) {
								if(!check_game_time($row['id']))
									$offline=false;
							}
							
							if($offline)
							{
								if(pay_gold_all($item[0]['gold']))
									$success = true;
							}
							else
							{
								
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php print $lang['need_off2']; ?>
					</div>
				<?php
							}
						}
						else
						{
							$id_char = intval($_POST['method_pay']);
							if(character_check($id_char))
							{
								if(check_game_time($id_char))
								{
									if(pay_gold($id_char, $item[0]['gold']))
									{
										$success = true;
									}
								}
								else 
								{
					?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php print $lang['need_off2']; ?>
					</div>
					<?php
								}
							}
						}
													
						if($success) {
							if(buy_item($id))
							{
								
							
				?>

					<div class="alert alert-dismissible alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php print $lang['successfully_bought']; ?>
					</div>
				
				<?php 
							} else { $success=false;
				?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php print $lang['no_space']; ?>
					</div>
				<?php
							}
						}
				}
			} 
				?>
				
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
						<div role="tabpanel" class="tab-pane fade active in" id="<?php print $id; ?>">
							<center>
								<img src="images/items/<?php print get_item_image($item[0]['vnum']); ?>.png">
								<h3><?php print get_item_name($item[0]['vnum']); ?></h3>
							<hr>
							<?php print $lang['listed_by']; ?> <font color="#67c1f5"><b><?php print get_character_name($item[0]['char_id']); ?></b></font>
							</center>
							<?php if($item[0]['count']>1) { ?>
							<hr>
							<center><p class="text-info"><b><?php print ucfirst(strtolower($lang['quantity'])).': '.$item[0]['count']; ?></b></p></center>
							<?php } ?>
							<hr>
							<center><?php get_item_bonuses_market($id); ?></center>
							<?php
								if(check_item_sash($item[0]['vnum'])) {
							?>
							<center><?php get_sash_bonuses_market($id); ?></center>
							<?php
								}
								get_item_stones_market($id);
								$lvl = get_item_lvl($item[0]['vnum']);
								if($lvl) {
							?>
							<center><p class="text-danger"><?php print $lang['available_lvl']; ?> <b><?php print $lvl; ?></b></p></center>
							<?php } if(check_item_sash($item[0]['vnum'])) { ?>
							<center><p class="text-warning"><?php print $lang['bonus_absorption']; ?> <b><?php print get_sash_absorption_market($id); ?></b>%</p></center>
							<?php } if(get_item_type($item[0]['vnum'])=="ITEM_UNIQUE") { ?>
							<center><p class="text-info"><?php print $lang['time_left']; ?> <b><?php get_item_time_market($id); ?></b></p></center>
							<?php } ?>
						</div>
					</div>
	
	
	
				</div>
			</div>
			<?php 
				if(isset($_POST['buy'])) 
					if($success)
					{
						pay_item_owner($item[0]['owner_id'], $item[0]['gold']);
						delete_item_market($id);
					}
			?>
		</div>
	</div>
</div>

<div style="padding-bottom: 16px;"></div>