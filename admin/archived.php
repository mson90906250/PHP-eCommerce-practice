<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		loginErrorRedirect();
	}
 	include "includes/head.php";
 	include "includes/navigation.php";
 	$archivedSql = "SELECT * FROM product WHERE deleted = 1";
 	$archivedResult = $db->query($archivedSql);

 	if(isset($_GET['archived_id'])){
 		$archived_id = $_GET['archived_id'];
 		$refreshSql = "UPDATE product SET deleted = 0 WHERE id = '$archived_id'";
 		$db->query($refreshSql);
 		header("Location: archived.php");
 	}
 ?>
<h2 class="text-center">Archived</h2><hr>
<div class="container">
	<table class="table table-bordered table-condensed">
		<thead>
			<th></th>
			<th>Product</th>
			<th>Price</th>
			<th>Category</th>
			<th>Featured</th>
			<th>Sold</th>
		</thead>
		<tbody>
			<?php while($archived = mysqli_fetch_assoc($archivedResult)): ?>
				<tr>
					<td><a href="archived.php?archived_id=<?= $archived['id'] ?>"><span class="glyphicon glyphicon-refresh"></span></a></td>
					<td><?= $archived['title'] ?></td>
					<td><?= $archived['price'] ?></td>
					<td><?= $archived['categories'] ?></td>
					<td><?= $archived['featured'] ?></td>
					<td>0</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>

 <?php 
 	include "includes/footer.php";
  ?>