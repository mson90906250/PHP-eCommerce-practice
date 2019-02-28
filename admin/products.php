<?php 
	//$_SERVER['DOCUMENT_ROOT'] = "C:/xampp/htdocs";
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	include "includes/head.php";
	include "includes/navigation.php";
	
	if(isset($_GET['add'])){ 
		$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
		$parentQuery =$db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

		//isset($_POST)會一直回傳true值,所以盡量不要這樣用
		//if($_POST) For comparisons php will convert (type cast) the ´$_POST` array to a boolean value of true or false. If an array is empty it will be converted to false and to true otherwise. 
		if($_POST){

			$title = sanitize($_POST['title']);
			$brand = sanitize($_POST['brand']);
			$price = sanitize($_POST['price']);
			$list_price = sanitize($_POST['list_price']);
			if($_POST['parent'] == ""){
				$_POST['child'] = "";
			}else{
				$categories = sanitize($_POST['child']);
			}
			$description = sanitize($_POST['description']);
			$sizes = sanitize($_POST['sizes']);

			if(!empty($_POST['sizes'])){
			//拆解字串
				$sizesString = sanitize($_POST['sizes']);
				//rtrim(string,charlist) 
				//rtrim() 函数移除字符串右侧的空白字符或其他预定义字符。
				$sizesString = rtrim($sizesString,",");
				$sizesArray = explode(",", $sizesString);
			//將size和quantity分開
				$sArray = array();
				$qArray = array();
				foreach ($sizesArray as $ss) {
					$sqArray = explode(":", $ss);
					$sArray[] = $sqArray[0];
					$qArray[] = $sqArray[1];
					//$sqArray = array();	
				}
			}else{
				$sizesArray = array();
			}

			//確認有星號*的欄位是否都被填寫
			$errors = array();
			$required = array("title","brand","parent","child","price","sizes");
			foreach($required as $field){
				if($_POST[$field]==""){
					$errors[] = "All fields with an Astrisk required"; 
					break;
				}
			}
			if($_FILES["photo"]["size"] > 0){
				var_dump($_FILES);
				$photo = $_FILES['photo'];
				$name = $photo['name'];
				$nameArray = explode(".", $name);
				$fileName = $nameArray[0];//檔名
				$fileExt = $nameArray[1];//副檔名
				$mime = explode("/",$photo['type']);
				$mimeType = $mime[0];
				$mimeExt = $mime[1];
				$tmpLoc = $photo['tmp_name'];
				$fileSize = $photo['size']; 
				$uploadName = md5(microtime()).".".$fileExt;
				$uploadPath = BASEURL."images/products/".$uploadName;
				$dbPath = "/tutorial/images/products/".$uploadName;
				//確認欲上傳的檔案是否為image
				if($mimeType!="image"){
					$errors[] = "Ths file must be an image.";
				}
				//確認是否符合圖片格式規定
				//自定一個格式規則
				$allowed = array("png","jpg","jpeg","gif");
				if(!in_array($fileExt,$allowed)){
					$errors[] = "The file extension must be a png,jpg,jpeg,or gif";
				}
				//確認圖片大小
				if($fileSize > 8000000){
					$errors[] = "The photo must be under 8MB."; 
				}
				//確認mimeExt與fileExt是否吻合
				if($fileExt == $mimeExt && ($mimeExt == "jpeg" && $fileExt != "jpg")){
					$errors[] = "File extension does not match the file";
				}

			}
			if(!empty($errors)){
				echo display_errors($errors);
			}else{
				//上傳圖片並將圖片路徑輸入進database
				move_uploaded_file($tmpLoc, $uploadPath);
				$sizes = rtrim($sizes,",");
				$insertsql = "INSERT INTO product (title,price,list_price,brand,categories,image,description,sizes) VALUES ('$title','$price','$list_price','$brand','$categories','$dbPath','$description','$sizes')";
				$db->query($insertsql);
				header("Location: products.php");

			}
		}
		?>
		<h2 class="text-center">Add A New Product</h2><hr>
		<!-- 
		application/x-www-form-urlencoded:	
			All characters are encoded before sent (this is default)
		multipart/form-data:	
			No characters are encoded. This value is required when you are using forms that have a file upload control
		text/plain:	
			Spaces are converted to "+" symbols, but no special characters are encoded
			簡而言之有用到檔案(如照片)上傳請選擇"multipart/form-data"-->
		<form action="products.php?add=1" method="POST" enctype="multipart/form-data">
			<div class="form-group col-md-3">
				<label for="title">Title*:</label>
				<input type="text" name="title" id="title" class="form-control" value="<?= ((isset($_POST['title']))?sanitize($_POST['title']):'') ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="brand">Brand*:</label>
				<select name="brand" id="brand" class="form-control">
					<option value="" <?= ((isset($_POST['brand']) && $_POST['brand'] == '')?'selected':'') ?>></option>
					<?php while($brand = mysqli_fetch_assoc($brandQuery)): ?>
						<option value="<?= $brand['id'] ?>" <?= ((isset($_POST['brand']) && $_POST['brand']  == $brand['id'])?' selected':'') ?>>
							<?= $brand['brand'] ?>		
						</option>
					<?php endwhile; ?>	
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="parent">Parent Category*:</label>
				<select name="parent" id="parent" class="form-control">
					<option value=""<?= ((isset($_POST['parent']) && $_POST['parent'] == '')?' selected':'') ?>></option>
					<?php while($parent = mysqli_fetch_assoc($parentQuery)): ?>
						<option value="<?= $parent['id'] ?>"<?= ((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?' selected':'') ?>>
							<?= $parent['category'] ?>
						</option>
					<?php endwhile; ?>	
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="child">Child Category*:</label>
				<select name="child" id="child" class="form-control">
					<!-- 根據Parent Category的選擇來動態生成 -->
					<!-- 相關語法寫在footer.php裡 -->
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="price">Price*:</label>
				<input type="text" name="price" id="price" class="form-control" value="<?= ((isset($_POST['price']))?sanitize($_POST['price']):'') ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="list_price">List Price:</label>
				<input type="text" name="list_price" id="list_price" class="form-control" value="<?= ((isset($_POST['list_price']))?sanitize($_POST['list_price']):'') ?>">
			</div>
			<div class="form-group col-md-3">
				<label>Quantity & Sizes*:</label>
				<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false">Quantity & Sizes</button>
			</div>
			<div class="form-group col-md-3">
				<label for="sizes">Sizes & Quantity Preview</label>
				<input type="text" name="sizes" id="sizes" class="form-control" value="<?= ((isset($_POST['sizes']))?$_POST['sizes']:'') ?>" readonly>
			</div>
			<div class="form-group col-md-6">
				<label for="photo">Product Photo:</label>
				<input type="file" name="photo" id="photo" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label for="description">Description:</label>
				<textarea name="description" id="description" class="form-control" rows="6"><?= ((isset($_POST['description']))?sanitize($_POST['description']):'') ?></textarea>
			</div>
			<div class="form-group pull-right">
				<input type="submit" class="btn btn-success" value="Add A New Product">
			</div>
		</form>
		<!-- Modal -->
		<div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
		      </div>
		      <div class="modal-body">
		      	<div class="container-fluid">
		      		<?php for($i = 1;$i <= 12;$i++): ?>
			        	<div class="form-group col-md-4">
			        		<label for="size<?= $i ?>">Size:</label>
			        		<input type="text" name="size<?= $i ?>" id="size<?= $i ?>" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ?>" class="form-control">
			        	</div>
			        	<div class="form-group col-md-2">
			        		<label for="qty<?= $i ?>">Quantity:</label>
			        		<input type="number" name="qty<?= $i ?>" id="qty<?= $i ?>" min="0" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:'') ?>" class="form-control">
			        	</div>
		        	<?php endfor; ?>	
		      	</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
<?php	}else{

	

	$sql = "SELECT * FROM product WHERE deleted = 0";
	$presult = $db->query($sql);

	//按下featured的按鈕
	//empty(0)會回傳true,即被認定為empty,所以"if(isset($_GET['featured'])&&!empty($_GET['featured'])){}"
	//這句會發生問題
	if(isset($_GET['featured'])){
		$id = $_GET['id'];
		$featured = $_GET['featured'];
		$featured_sql="UPDATE product SET featured = '$featured' WHERE id = '$id'";
		$db->query($featured_sql);
		header("Location: products.php");
	}
 ?>
 <h2 class="text-center">Products</h2>
 <a href="products.php?add=1" class="btn btn-success pull-right" id="add_product_btn">Add A Product</a><div class="clearfix"></div>
 <hr>
 <table class="table table-bordered table-condensed table-striped">
 	<thead>
 		<th></th>
 		<th>Product</th>
 		<th>Price</th>
 		<th>Category</th>
 		<th>Featured</th>
 		<th>Sold</th>
 	</thead>
 	<tbody>
 		<?php while($product = mysqli_fetch_assoc($presult)): 
 			$childID = $product['categories'];
 			$category_sql = "SELECT * FROM categories WHERE id = '$childID'";
 			$child_result = $db->query($category_sql);
 			$child_category = mysqli_fetch_assoc($child_result);
 			$parentID = $child_category['parent'];
 			$category_sql = "SELECT * FROM categories WHERE id = '$parentID'";
 			$parent_result = $db->query($category_sql);
 			$parent_category = mysqli_fetch_assoc($parent_result);
 			$category = $parent_category['category']."~".$child_category['category'];
 			?>
 			<tr>
 				<td>
 					<a href="products.php?edit=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
 					<a href="products.php?delete=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
 				</td>
 				<td><?= $product['title'] ?></td>
 				<td><?= money($product['price']) ?></td>
 				<td><?= $category ?></td>
 				<td><a href="products.php?featured=<?= (($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?= (($product['featured'] == 0)?'plus':'minus'); ?> "></span></a>
 					<?= (($product['featured'] == 1)?'featured product':'') ?>
 				</td>
 				<td>0</td>
 			</tr>
 		<?php endwhile; ?>	
 	</tbody>
 </table>

 <?php 
 	}//endelse
 	include "includes/footer.php";
  ?>