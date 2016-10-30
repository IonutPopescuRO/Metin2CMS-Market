<div class="well">

	<div class="row">
		<div class="col-md-9">
			<div class="jumbotron">
				<center><h2><?php print $lang['owned_chars']; ?></h2></center>
				
				<div class="row">
					<div class="col-md-2"></div>
					
					<?php if(characters_number()) { ?>
					<div class="col-md-10">

						<?php 
							$list = array();
							$list = characters_list();
							
							foreach($list as $row) {
						?>
						<a href="?p=inventory&character=<?php print $row['id']; ?>">
							<div class="dynamiclink_box">
								<div class="row">
									<div class="col-md-3">
										<img src="images/misc/<?php print $row['job']; ?>.png" width="88px" height="88px">
									</div>
									<div class="col-md-9">
										<div class="dynamiclink_content">
											<div><span class="dynamiclink_type"><?php print $row['name']; ?> </span><font color="#67c1f5"><b>[Lv. <?php print $row['level']; ?>]</b></font></div>
											<div><?php print $lang['balance'].': '.number_format($row['gold'], 0, '', '.'); ?> yang</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<?php } ?>
						
					</div>
					<?php } else print '<div class="col-md-8">
											<div class="alert alert-dismissible alert-info">
												<button type="button" class="close" data-dismiss="alert">×</button>
												'.$lang['no_chars'].';
											</div>
										</div>'; ?>
					
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
                        <?php print $lang['my_chars']; ?> </div>
                    <div style="clear: left"></div>
                </div>
            </div>
			
			</br></br>
			
			<div class="jumbotron">
				<div class="rightSectionTopTitle"><?php print $lang['inventory']; ?></div>
				<div class="rightSectionText">
					<?php print $lang['inventory_info']; ?>
				</div>
			</div>
			
			<div class="jumbotron">
				<div class="row">
					<div class="col-md-2">
                        <div class="followStat"><?php print characters_number(); ?></div>
					</div>
					<div class="col-md-10">
                        <div class="followStat"><div class="followStatTitle"><?php print $lang['my_chars2']; ?></div></div>
					</div>
				</div>
				<div class="rightSectionText">
					<?php print $lang['pay_info']; ?>
				</div>
			</div>
			
			<div class="jumbotron">
				<?php $gold = market_amount($_SESSION['id']); ?>
				<div class="rightSectionTopTitle"><?php print $lang['money_claimed']; ?>: <font color="#67c1f5"><b><?php print number_format(market_amount($_SESSION['id']), 0, '', '.'); ?></b></font></div>
				<div class="rightSectionText">
					<a href="?p=claim" class="btn btn-success btn-lg btn-block<?php if(!$gold) print ' disabled'; ?>"><?php print $lang['claim']; ?></a>
				</div>
			</div>
			
		</div>
	</div>
	
</div>