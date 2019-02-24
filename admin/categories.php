<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";

	$sql = "SELECT * FROM categories WHERE parent = 0";
	$result = $db->query($sql);

	//delete category
	if(isset($_GET['delete'])&&!empty($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		
		//如果要刪除的對象是parent的話,也要將它的child都清掉
		$dsql = "SELECT * FROM categories WHERE id ='delete_id'";
		$dresult = $db->query($dsql);
		$category = mysqli_fetch_assoc($dresult);
		if($category['parent'] == 0){
			$dsql = "DELETE FROM categories WHERE parent = '$delete_id'";
			$db->query($dsql);
		}

		$dsql = "DELETE FROM categories WHERE id = '$delete_id'";
		$db->query($dsql);
		header("Location: categories.php");
	}

	//process form (Add Category)
	$errors = array();//用於記錄錯誤訊息
	//確認表單有被填寫
	if(isset($_POST)&&!empty($_POST)){
		$category = sanitize($_POST['category']);
		$parent = sanitize($_POST['parent']);
		//確認category是否為""
		if($category == ""){
			$errors[] .= "The category can not be left blank";
		}
		//確認category是否已存在於database
		$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$parent'";
		$fresult = $db -> query($sqlform);
		$count = mysqli_num_rows($fresult);
		if($count>0){
			$errors[] .= $category." already exsists";
		}

		//確認過程中是否有錯誤出現($errors[]裡是否有東西)
		if(!empty($errors)){
			//顯示錯誤訊息
			$display = display_errors($errors); ?>
			<script>
				//當頁面讀取完畢時,執行方法
				jQuery("document").ready(function(){
					jQuery("#errors").html('<?= $display ?>');
				});
			</script>
		<?php 
		}else{
			//新增category
			$add_sql = "INSERT INTO categories(category,parent) VALUES ('$category','$parent')";
			$db -> query($add_sql);
			//如果不用以下的方法的話,有可能會有東西沒被讀取的問題
			header("Location: categories.php");
		}
	}

 ?>

 <h2 class="text-center">Categories</h2><hr>

 <div>
 	<div class="row">
 		<div class="col-md-6">
 			<form class="form" action="categories.php" method="post">
 				<legend>Add Category</legend>
 				<!-- 顯示錯誤訊息用的 -->
 				<div id="errors"></div>
 				<div class="form-group">
 					<label for="parent">Parent</label>
 					<select class="form-control" name="parent" id="parent">
 						<option value="0">Parent</option>
 						<?php while($parent = mysqli_fetch_assoc($result)): ?>
 							<option value="<?= $parent['id']; ?>"><?= $parent['category']; ?></option>
 						<?php endwhile; ?>
 					</select>
 				</div>
 				<div class="form-group" >
 					<label for="category">Category</label>
 					<input class="form-control" type="text" name="category" id="category">
 				</div>
 				<div class="form-group">
 					<input type="submit" value="Add Category" class="btn btn-success">
 				</div>
 			</form>
 		</div>
 		<div class="col-md-6">
 			<table class="table table-bordered">
 				<thead>
 					<th>Category</th><th>Parent</th><th></th>
 				</thead>
 				<tbody>
 					<?php 
 						$sql = "SELECT * FROM categories WHERE parent = 0";
						$result = $db->query($sql);
 						while($parent = mysqli_fetch_assoc($result)): 
 						$parent_id = $parent['id'];
 						//利用parent的id來找出相對應的child
 						$sql_child = "SELECT * FROM categories WHERE parent = '$parent_id'";
 						$cresult = $db->query($sql_child);
 						?>
	 					<tr class="bg-primary">
	 						<td><?= $parent['category']; ?></td>
	 						<td>parent</td>
	 						<td>
	 							<a href="categories.php?edit=<?= $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
	 							<a href="categories.php?delete=<?= $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
	 						</td>
	 					</tr>
	 					<?php while($child = mysqli_fetch_assoc($cresult)): ?>
	 						<tr class="bg-info">
	 						<td><?= $child['category']; ?></td>
	 						<td><?= $child['parent'] ?></td>
	 						<td>
	 							<a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
	 							<a href="categories.php?delete=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
	 						</td>
	 					</tr>
	 					<?php endwhile; ?>	
 					<?php endwhile; ?>
 				</tbody>
 			</table>
 		</div>
 	</div>
 </div>

 <?php 
 	include "includes/footer.php";
  ?>