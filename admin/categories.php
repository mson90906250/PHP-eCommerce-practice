<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		loginErrorRedirect();
	}
	include "includes/head.php";
	include "includes/navigation.php";

	$sql = "SELECT * FROM categories WHERE parent = 0";
	$result = $db->query($sql);
	$category = "";
	$post_parent = "";

	//edit category
	if(isset($_GET['edit'])&&!empty($_GET['edit'])){
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
		$edit_result = $db->query($edit_sql);
		$edit_category = mysqli_fetch_assoc($edit_result);
	}

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
		$post_parent = sanitize($_POST['parent']);
		//確認category是否為""
		if($category == ""){
			$errors[] .= "The category can not be left blank";
		}
		//確認category是否已存在於database
		$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
		if(isset($_GET['edit'])){
			$form_id = $edit_category['id'];
			$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$form_id'";
		}
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
			$add_sql = "INSERT INTO categories(category,parent) VALUES ('$category','$post_parent')";
			if(isset($_GET['edit'])){
				$add_sql = "UPDATE categories SET category = '$category',parent = '$post_parent' WHERE id = '$edit_id'";
			}
			$db -> query($add_sql);
			//如果不用以下的方法的話,有可能會有東西沒被讀取的問題
			header("Location: categories.php");
		}
	}

	$category_value="";
	$parent_value = 0;
	if(isset($_GET['edit'])){
		$category_value = $edit_category['category'];
		$parent_value = $edit_category['parent'];
	}else{
		if(isset($_POST)){
			$category_value = $category;
			$parent_value = $post_parent;
		}
	}

 ?>

 <h2 class="text-center">Categories</h2><hr>

 <div>
 	<div class="row">

 		<!-- Form -->
 		<div class="col-md-6">
 			<form class="form" action="categories.php<?= ((isset($_GET['edit']))?'?edit='.$edit_id:'') ?>" method="post">
 				<legend><?= ((isset($_GET['edit']))?'Edit ':'Add A ') ?> Category</legend>
 				<!-- 顯示錯誤訊息用的 -->
 				<div id="errors"></div>
 				<div class="form-group">
 					<label for="parent">Parent</label>
 					<select class="form-control" name="parent" id="parent">
 						<option value="0"<?= (($parent_value == 0)?'selected = "selected"':'') ?>>Parent</option>
 						<?php while($parent = mysqli_fetch_assoc($result)): ?>
 							<option value="<?= $parent['id']; ?>"<?= (($parent_value == $parent['id'])?'selected = "selected"':'') ?>><?= $parent['category']; ?></option>
 						<?php endwhile; ?>
 					</select>
 				</div>
 				<div class="form-group" >
 					<label for="category">Category</label>
 					<input class="form-control" type="text" name="category" id="category" value="<?= $category_value ?>">
 				</div>
 				<div class="form-group">
 					<input type="submit" value="<?= ((isset($_GET['edit']))?'Edit ':'Add A') ?> Category" class="btn btn-success">
 				</div>
 			</form>
 		</div>

 		<!-- Table -->
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
	 							<a href="categories.php?delete=<?= $parent['id']; ?>" class="btn btn-xs btn-default" onclick="return confirm('Are you sure that you want to delete this category?');"><span class="glyphicon glyphicon-remove-sign"></span></a>
	 						</td>
	 					</tr>
	 					<?php while($child = mysqli_fetch_assoc($cresult)): ?>
	 						<tr class="bg-info">
	 						<td><?= $child['category']; ?></td>
	 						<td><?= $child['parent'] ?></td>
	 						<td>
	 							<a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
	 							<a href="categories.php?delete=<?= $child['id']; ?>" class="btn btn-xs btn-default" onclick="return confirm('Are you sure that you want to delete this category?');"><span class="glyphicon glyphicon-remove-sign"></span></a>
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