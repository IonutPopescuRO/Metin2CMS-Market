		<?php
			$search = true;
			$q = isset($_GET['q']) ? $_GET['q'] : null;
			if (is_numeric($q))
			{
				$q=intval($q);
				if($q<1 || $q>2)
					$search=false;
			}
			else if($q==null || strlen($q)<3)
				$search=false;
		
			$get_pages = isset($_GET['n']) ? $_GET['n'] : 1;
			$remove = isset($_GET['remove']) ? $_GET['remove'] : null;
			if(is_loggedin()) {
		?>
		<div class="bs-component">
			<div class="alert alert-dismissible alert-warning">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Info</h4>
				<p><?php print $lang['info_1']; ?></p>
			</div>
			<div style="display: none;" id="source-button" class="btn btn-primary btn-xs">&lt; &gt;</div>
		</div>
		<?php 
		if($remove)
		{
			$datails_item = array();
			$datails_item = items_select_market($remove);
			if(count($datails_item))
				if($datails_item[0]['owner_id']==$_SESSION['id'])
				{
					if(buy_item($remove))
					{
						delete_item_market($remove);
		?>
		<div class="bs-component">
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Info</h4>
				<p><?php print $lang['info_remove']; ?></p>
			</div>
			<div style="display: none;" id="source-button" class="btn btn-primary btn-xs">&lt; &gt;</div>
		</div>
		<?php
					}
					else {
		?>
		<div class="bs-component">
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Info</h4>
				<?php print $lang['info_remove_fail']; ?>
			</div>
			<div style="display: none;" id="source-button" class="btn btn-primary btn-xs">&lt; &gt;</div>
		</div>
		<?php
					}
				}
		}
		
		if(!$search && $get_pages==1) { 
			$list = array();
			$list = market_items_on_sell();
			
			if(count($list)) {
		?>
		<p><?php print $lang['listings']; ?></p>
		<div class="well-items">
			<div class="row" style="height:20px;">
				<div class="col-sm-7 border-right" style="color: #AFAFAF;"><?php print $lang['name']; ?></div>
				<div class="col-sm-3" style="text-align: center; color: #AFAFAF;"><?php print $lang['price']; ?></div>
			</div>
		</div>
		<?php foreach($list as $row) { ?>
		<div class="well-items">
			<div class="row">
				<div class="col-sm-7">
					<div class="row">
						<div class="col-sm-2">
							<img src="images/items/<?php print get_item_image($row['vnum']); ?>.png" style="border-color: #D2D2D2;" class="item_img">
						</div>
						<div class="col-sm-10">
							<span class="item-name"><?php print get_item_name($row['vnum']); ?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-3 price">
					<?php print number_format($row['gold'], 0, '', '.'); ?> yang
				</div>
				<div class="col-sm-2 remove">
					<a href="?remove=<?php print $row['id']; ?>" class="btn btn-primary btn-sm"><?php print $lang['item_remove']; ?></a>
				</div>
			</div>
		</div>
		<?php } ?>
		<hr>
		<?php } } } ?>
		
        <div class="row">

            <div class="col-lg-8">
			<?php if($search) { ?>
				<div class="well well-sm">
					<?php print $lang['results_for']; ?>:
					<?php if(is_numeric($q)) { ?>
						<span class="label label-success"><?php if($q==1) print $lang['CATEGORY_EQUIPMENT_WEAPON']; else print $lang['CATEGORY_EQUIPMENT_ARMOR']; ?> <a href="index.php" class="label label-danger">X</a></span>
					<?php } else { ?>
						<span class="label label-success"><?php print $q; ?> <a href="index.php" class="label label-danger">X</a></span>
					<?php } ?>
				</div>
			<?php } ?>
				<div class="well-items">
					<div class="row" style="height:20px;">
						<div class="col-sm-7 border-right" style="color: #AFAFAF;"><?php print $lang['name']; ?></div>
						<div class="col-sm-2 border-right" style="text-align: center; color: #AFAFAF;"><?php print $lang['quantity']; ?></div>
						<div class="col-sm-3" style="text-align: center; color: #AFAFAF;"><?php print $lang['price']; ?></div>
					</div>
				</div>
				
			<?php
				if(!$search)
					$total = intval(total_market_no_duplicate_vnum());
				else if(is_numeric($q))
					$total = intval(total_market_no_duplicate_vnum_search_category($q));
				else
					$total = intval(total_market_no_duplicate_vnum_search($q));
				
				$pages  = ceil($total / $items_on_page);
								
				$data = array(
						'options' => array(
						'default'   => 1,
						'min_range' => 1,
						'max_range' => $pages
						)
				);
				
				$number = trim($get_pages);
				$number = filter_var($number, FILTER_VALIDATE_INT, $data);
				$range  = $items_on_page * ($number - 1);

				$prev = $number - 1;
				$next = $number + 1;
				
				if(!$search)
					$stmt = $sqlite->prepare("SELECT MAX(id) AS id, vnum FROM market GROUP BY vnum LIMIT :limit, :perpage");
				else if(is_numeric($q))
				{
					$stmt = $sqlite->prepare("SELECT MAX(A.id) AS id, A.vnum
								FROM market A WHERE A.vnum in (SELECT B.id FROM items_details B WHERE B.type = :type)
								GROUP BY A.vnum LIMIT :limit, :perpage");
					if($q==1)
						$stmt->bindValue(':type','ITEM_WEAPON');
					else
						$stmt->bindValue(':type','ITEM_ARMOR');
				}
				else
				{
					$stmt = $sqlite->prepare("SELECT MAX(A.id) AS id, A.vnum
								FROM market A WHERE A.vnum in (SELECT B.id FROM items_names B WHERE B.ro LIKE :keyword)
								GROUP BY A.vnum LIMIT :limit, :perpage");
					$stmt->bindValue(':keyword','%'.$q.'%');
				}
				$stmt->bindParam(':perpage', $items_on_page, PDO::PARAM_INT);
				$stmt->bindParam(':limit', $range, PDO::PARAM_INT);
				$stmt->execute();
					
				$result = $stmt->fetchAll();

	
				if($result && count($result) > 0)
				{
						foreach($result as $key => $row)
						{
			?>
			<a href="?p=item&id=<?php print $row['vnum']; ?>">
				<div class="well-items">
					<div class="row">
						<div class="col-sm-7">
							<div class="row">
								<div class="col-sm-2">
									<img src="images/items/<?php print get_item_image($row['vnum'])?>.png" style="border-color: #D2D2D2;" class="item_img">
								</div>
								<div class="col-sm-10">
									<span class="item-name"><?php print get_item_name($row['vnum']); ?></span>
								</div>
							</div>
						</div>
						<div class="col-sm-2 num"><?php print item_market_count($row['vnum']); ?></div>
						<div class="col-sm-3 price">
							<span class="start"><?php print $lang['starting_price']; ?>:</br></span>
							<?php print item_market_min_gold($row['vnum']); ?> yang
						</div>
					</div>
				</div>
			</a>
			<?php
						}

					print '<center><ul class="pagination pagination-lg">';

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
					
					print '</ul></center>';
				}

				else
				{
			?>
			<div class="alert alert-dismissible alert-info">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4><?php print $lang['nothing_found']; ?></h4>
				<p><?php print $lang['nothing_found_info']; ?></p>
			</div>
			<?php
				}
			?>
			
            </div>

            <div class="col-md-4">
			
                <div class="well">
                    <h4><?php print $lang['search_by_name']; ?></h4>
					<form action="" method="get">
						<div class="input-group">
							<input name="q" type="text" pattern=".{<?php print $minim_search_characters.','.$maxim_search_characters; ?>}" required title="<?php print $minim_search_characters.' to '.$maxim_search_characters; ?> characters" class="form-control">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default" type="button">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div>
					</form>
                </div>
				<?php if(is_loggedin()) { ?>
                <div class="well">
                    <h4><?php print $lang['sell']; ?></h4>
					<a href="?p=characters" class="btn btn-primary btn-block"><?php print $lang['view_inventory']; ?></a>
                </div>
				<?php } if(is_loggedin() && web_admin_level()>=$minim_web_admin_level) { ?>
                <div class="well">
                    <h4><?php print $lang['administration_panel']; ?></h4>
					<a href="?p=admin&a=forbidden" class="btn btn-success btn-block">
						<div class="row">
							<div class="col-sm-1">
								<img src="images/forbidden.png" width="25" height="25">
							</div>
							<div class="col-sm-1">
								<?php print $lang['prohibited_items']; ?>
							</div>
						</div>
					</a>
                    <h4>Item-Shop</h4>
					<a href="shop?p=categories" class="btn btn-success btn-block">
						<div class="row">
							<div class="col-sm-1">
								<img src="images/category.png" width="25" height="25">
							</div>
							<div class="col-sm-1">
								<?php print $lang['administration_categories']; ?>
							</div>
						</div>
					</a>
					<a href="shop?p=add_items" class="btn btn-success btn-block">
						<div class="row">
							<div class="col-sm-1">
								<img src="images/add.png" width="25" height="25">
							</div>
							<div class="col-sm-1">
								<?php print $lang['is_add_items']; ?>
							</div>
						</div>
					</a>
					<a href="shop?p=paypal" class="btn btn-success btn-block">
						<div class="row">
							<div class="col-sm-1">
								<img src="images/PayPal-icon.png" width="25" height="25">
							</div>
							<div class="col-sm-1">
								<?php print $lang['administration_pp']; ?>
							</div>
						</div>
					</a>
                </div>
				<?php } ?>
				
                <div class="well">
                    <h4><?php print $lang['search_by_category']; ?></h4>
                    <div class="row">
                        <a href="?q=1" class="btn btn-primary btn-block">
							<div class="row">
								<div class="col-sm-1">
									<img src="images/items/00290.png" height="30">
								</div>
								<div class="col-sm-1">
									<?php print $lang['CATEGORY_EQUIPMENT_WEAPON']; ?>
								</div>
							</div>
						</a>
                        <a href="?q=2" class="btn btn-primary btn-block">
							<div class="row">
								<div class="col-sm-1">
									<img src="images/items/11210.png" height="30">
								</div>
								<div class="col-sm-1">
									<?php print $lang['CATEGORY_EQUIPMENT_ARMOR']; ?>
								</div>
							</div>
						</a>
                    </div>
                </div>
				<?php if($item_shop) { ?>
                <div class="well">
                    <h4>Item-Shop <?php print $lang['official']; ?></h4>
                    <p><?php print $lang['is_description']; ?></p>
					<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#myModal"><?php print $lang['open']; ?></button>
                </div>
				<?php
				}
				if(is_loggedin())
				{
					$gold = market_amount($_SESSION['id']);
					if($gold) {
				?>
                <div class="well">
                    <h4><?php print $lang['money_claimed']; ?>: <font color="#67c1f5"><b><?php print number_format($gold, 0, '', '.'); ?></b></font></h4>
                    <p><a href="?p=claim" class="btn btn-success btn-block"><?php print $lang['claim']; ?></a></p>
                </div>
				<?php } }?>
            </div>

        </div>
		<?php if($item_shop) { ?>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Item-Shop</h4>
					</div>
					<div class="modal-body">
						<div style="left: 8px; width: 1016px; height: 655px; overflow: auto; top: 28px;" id="fancybox-inner">
							<iframe id="fancybox-frame" class="fancybox-frame" hspace="0" scrolling="yes" src="shop" frameborder="0"></iframe>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php print $lang['close']; ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>