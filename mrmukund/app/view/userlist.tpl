	<!-- Deals Page -->
	<div class="clothes_main_content">
		<h2 id="category_name"> <?= $pageName ?> </h2>

		<div class="clothes_remaining">

			<div id="people_list">
				<table id = "people_user_list" class="table table-striped" >

				<tr>
					<td class = "user_email"> <strong> Email </strong> </td>
					<td class = "user_first_name"> <strong> First Name </strong> </td>
					<td class = "user_last_name"> <strong> Last Name </strong> </td>
					<td class = "user_post_deals"> <strong> Add Deals? </strong> </td>
					<td class = "user_change_submit"> <strong> Update </strong> </td>
				</tr>
					<?php if(! is_null($result)) {

						foreach($result as $row): ?>
							<tr>
								<form method="POST" action= "userupdate">
								<td><?=$row->get('email'); ?></td>
								<td><?=$row->get('f_name'); ?></td>
								<td><?=$row->get('l_name'); ?> </td>
								<td>
									<select name="user_add_deals">
										<option value="Yes" <?php echo ($row->get('add_deal') == 'yes'? "selected":"") ?> >Yes</option>
										<option value="No" <?php echo ($row->get('add_deal') == 'no'? "selected":"") ?> >No</option>
									</select>

									</td>
								<td> <input type = "hidden" name = "userid" value = "<?= $row->get('id'); ?>"/>
										<input type="submit" value="Update"/>
								</td>
							</form>
							</tr>


				<?php endforeach; }?>
				</table>
				<?php
				if($page>1){
				?>
				<a href= "<?= BASE_URL ?>/editUser/page<?= $page-1 ?>" > << Previous</a>
				<?php }
				if($page<$totalpages){
				?>
				<a href= "<?= BASE_URL ?>/editUser/page<?= $page+1 ?>" style="float:right"> Next >></a>
				<?php } ?>

			</div>

		</div>

	</div>
