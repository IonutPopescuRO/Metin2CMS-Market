<div class="well">
	<div class="row">
		<div class="col-md-9">
			<?php
				$gold = market_amount($_SESSION['id']);
				$list = array();
				$list = characters_list();
				if(!isset($_POST['claim'])) {
			?>
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Info:</strong> <?php print $lang['claim_info']; ?>
			</div>
			<?php } ?>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title"><?php print $lang['claim_yang']; ?></h3>
				</div>
				<div class="panel-body">

				<?php
					if(!isset($_POST['claim'])) {
				?>
					<form action="" method="post">
						<div class="form-group">
							<label class="control-label"><?php print $lang['wallet_market']; ?></label>
							<div class="input-group">
								<span class="input-group-addon">yang</span>
								<input name="gold" class="form-control" disabled="" value="<?php print number_format($gold, 0, '', '.'); ?>">
								<span class="input-group-btn">
							</span>
							</div>
							
						</div>
							
						<div class="form-group">

							<label class="control-label"><?php print $lang['char_claim']; ?></label>

							<select class="form-control" id="select" name="character">
								<?php
									$list = array();
									$list = characters_list();
									
									foreach($list as $row) {
										print '<option ';
										print 'value="'.$row['id'].'">'.$row['name'].' [ Lv. '.$row['level'].' ] ';
										if(!check_game_time($row['id']))
											print '- '.$lang['need_off'];
										print '</option>';
									}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<input type="submit" class="btn btn-warning btn-block<?php if(!$gold) print ' disabled'; ?>" name="claim" value="<?php print $lang['claim']; ?>">
						</div>
					</form>
				
				<?php } else {
					$success = false;

					$id_char = intval($_POST['character']);
					if(character_check($id_char))
					{
						if(check_game_time($id_char))
						{
							if($gold)
							{
								if(gold_update($id_char))
									$success = true;
							}
							else $success = true;
						}
						else 
						{
					?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Info:</strong> <?php print $lang['claim_info']; ?>
					</div>
					<?php
						}
					}
													
					if($success) {
				?>

					<div class="alert alert-dismissible alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Succes!</strong> <?php print $lang['claim_success']; ?>
					</div>
				
				<?php } 
				else {
				?>
					<div class="alert alert-dismissible alert-danger">
						<strong>ERROR</strong>
					</div>
				<?php
					}
			} 
				?>
				
				</div>
				
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="primary_panel_border panel_margin_none">
				<div class="primary_panel">
					<div class="avatar_ctn">
						<img width="84px" height="84px" src="images/misc/<?php char_big_lvl(); ?>.png">
                        <div class="avatar_workshop_overlay"></div>
                    </div>
                    <div class="player_accounts">
                        Revendicări </div>
                    <div style="clear: left"></div>
                </div>
            </div>
		</div>
	</div>
</div>

<div style="padding-bottom: 16px;"></div>