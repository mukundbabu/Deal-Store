	<?php
	$user = "";
	if(!isset($_SESSION)){
		session_start();
	}
	if(isset($_SESSION['user_id']))
		$user = $_SESSION['user_id'];
	?>
	<!-- Deals Page -->
	<div class="clothes_main_content">
		<h2 id="category_name"> <?= $pageName ?> </h2>

		<ul class="nav nav-tabs">
		  <li class="active"><a href="people">Find People</a></li>
		  <li><a href="connections">My Connections</a></li>
		</ul>
		<div class="tab-content">
		<div id = "find_people" class="tab-pane fade in active">
				<div id = "search_users_div">
					<form method="POST" action="people">
					<input id = "usersearchbox" type="search" name="username_search" placeholder="Search Users by first or last name"/>
					<input type="hidden" id="user_search_id" name = "user_search_id"/>
					<input type="submit" value="Go"/>
					<p><strong>Note:</strong> Execute a blank search for all users </p>
					</form>
				</div>
				<div class="clothes_remaining" class="tab-pane fade in active">
					<div id="deals_list">
						<table id = "user_list" class="table table-striped" >
							<?php if(! is_null($result)) {

								while($row = mysql_fetch_assoc($result)): ?>
									<tr>
										<td> <a href="<?=BASE_URL?>/Connections?user=<?=$row['id'] ?>"><?=$row['f_name']; ?> <?=$row['l_name']; ?></a></td>
										<td> <?= $row['email']; ?> </td>
										<?php if($user != $row['id']) { ?>
										<td> <img id = "following<?= $row['id'] ?>" width="75" height="25" src = "<?=BASE_URL?>/public/img/button_following.png" style="visibility:<?php echo ($row['follow'] != null?"display":"hidden");?>"/> </td>
										<td> <input type = "image" id = "follow_img<?= $row['id'] ?>" value = "<?php echo ($row['follow'] != null?"unfollow":"follow");?>-<?= $row['id'] ?>" name = "follow_user" width="125" height="35" src = "<?=BASE_URL?>/public/img/button_<?php echo ($row['follow'] != null?"unfollow":"follow");?>.png"/> </td>
										<?php } ?>
									</tr>
								<?php endwhile; }
								else
								{ ?>
									<tr>
										<td> No results to be displayed </td>
									</tr>

							<?php	}?>
						</table>
						<!--<?php
						if($page>1){
						?>
						<a href= "<?= BASE_URL ?>/editUser/page<?= $page-1 ?>" > <button> Previous Page </button></a>
						<?php }
						if($page<$totalpages){
						?>
						<a href= "<?= BASE_URL ?>/editUser/page<?= $page+1 ?>" style="float:right"> <button>Next Page</button></a>
						<?php } ?> -->

					</div>

					</div>
				</div>
				<div id = "my_connections" class="tab-pane fade">
						<h4> My connections </h4>
						<table class="table table-striped" >
							<?php if($friend != null) {
								while($eachrow = mysql_fetch_assoc($friend)): ?>
								<tr>
									<td> <?= $eachrow['f_name'].' '.$eachrow['l_name'] ?></td>
									<td> <?= $eachrow['email'] ?> </td>
									<td> <img width="75" height="25" src = "<?=BASE_URL?>/public/img/button_following.png" /> </td>
								</tr>
							<?php endwhile;
					} ?>
				</table>
				</div>

			</div>
	</div>
