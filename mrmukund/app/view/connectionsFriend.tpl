	<!-- Deals Page -->
	<div class="clothes_main_content">
		<h2 id="category_name"> <?= $pageName ?> </h2>

		<div class="tab_content_connections">
						<div id = "my_connections">
						<table id = "user_list" class="table table-striped" >
							<?php if($friend != null) {
								while($eachrow = mysql_fetch_assoc($friend)): ?>
								<tr>
									<td> <a href="<?=BASE_URL?>/Connections?user=<?=$eachrow['id'] ?>"><?= $eachrow['f_name'].' '.$eachrow['l_name'] ?></a></td>
									<td> <?= $eachrow['email'] ?> </td>
									<td> <img width="75" height="25" src = "<?=BASE_URL?>/public/img/button_following.png" /> </td>
								</tr>
							<?php endwhile;
					} else
					echo "<p> There are no connections to display </p>";?>
				</table>
				</div>

			</div>
		</div>
