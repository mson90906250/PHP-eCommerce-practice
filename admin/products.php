<?php 
	//$_SERVER['DOCUMENT_ROOT'] = "C:/xampp/htdocs";
	require_once $_SERVER['DOCUMENT_ROOT']."/tutorial/core/init.php";
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		loginErrorRedirect();
	}
	include "includes/head.php";
	include "includes/navigation.php";

	//將product從列表中移除,並沒有將database裡的資料刪除,目的是用來當作記錄用
	if(isset($_GET['delete'])){
		$delete_id = sanitize($_GET['delete']);
		$db->query("UPDATE product SET deleted = 1 WHERE id = '$delete_id'");
		header("Location: products.php");
	}
	
	if(isset($_GET['add'])||isset($_GET['edit'])){ 
		$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
		$parentQuery =$db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

		$title = ((isset($_POST['title']))?sanitize($_POST['title']):'');
		$brand = ((isset($_POST['brand']))?sanitize($_POST['brand']):'');
		$price = ((isset($_POST['price']))?sanitize($_POST['price']):'');
		$list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):'');
		$description = ((isset($_POST['description']))?sanitize($_POST['description']):'');
		$sizes = ((isset($_POST['sizes']))?sanitize($_POST['sizes']):'');
		$saved_image = ((isset($_POST['photo']))?sanitize($_POST['photo']):'');
		$category = ((isset($_POST['child']))?sanitize($_POST['child']):'');

		$parent = ((isset($_POST['parent']))?$_POST['parent']:'');
		if(isset($_GET['edit'])){
			$edit_id = $_GET['edit'];
			$productQuery = $db->query("SELECT * FROM product WHERE id = '$edit_id'");
			$productRow = mysqli_fetch_assoc($productQuery);
			//按下delete image按鈕後
			if(isset($_GET['delete_image'])){
				$imgi = (int)$_GET['imgi']-1;
				$images = explode(",",$productRow['image']);
				$image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
				//unset($image_url);無法移除圖片
				unlink($image_url);
				//將已刪除的圖片路徑從$images中去掉
				unset($images[$imgi]);
				//將$images變回string
				$imageString = implode(",", $images);
				$db->query("UPDATE product SET image = '{$imageString}' WHERE id = '$edit_id'");
				header("Location: products.php?edit=".$edit_id);
			}
			$title = ((isset($_POST['title']))?sanitize($_POST['title']):$productRow['title']);
			$brand = ((isset($_POST['brand']))?sanitize($_POST['brand']):$productRow['brand']);
			$price = ((isset($_POST['price']))?sanitize($_POST['price']):$productRow['price']);
			$sizes = rtrim(((isset($_POST['sizes']))?sanitize($_POST['sizes']):$productRow['sizes']),",");
			$list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$productRow['list_price']);
			$description = ((isset($_POST['description']))?sanitize($_POST['description']):$productRow['description']);
			$category = ((isset($_POST['child']))?sanitize($_POST['child']):$productRow['categories']);
			$parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
			$parentRow = mysqli_fetch_assoc($parentQ);
			$parent = ((isset($_POST['parent']))?sanitize($_POST['parent']):$parentRow['parent']);
			$saved_image = (($productRow['image'] != '')?$productRow['image']:'');
			$dbPath = $saved_image;

		}

		if(!empty($sizes)){
			//拆解字串
				$sizesString = sanitize($sizes);
				//rtrim(string,charlist) 
				//rtrim() 函数移除字符串右侧的空白字符或其他预定义字符。
				$sizesString = rtrim($sizesString,",");
				$sizesArray = explode(",", $sizesString);
			//將size,quantity和threshold分開
				$sArray = array();
				$qArray = array();
				$tArray = array();
				foreach ($sizesArray as $ss) {
					$sqArray = explode(":", $ss);
					$sArray[] = (isset($sqArray[0]))?$sqArray[0]:"";
					$qArray[] = (isset($sqArray[1]))?$sqArray[1]:"";
					$tArray[] = (isset($sqArray[2]))?$sqArray[2]:"";
					//$sqArray = array();	
				}
			}else{
				$sizesArray = array();
			}

		//isset($_POST)會一直回傳true值,所以盡量不要這樣用
		//if($_POST) For comparisons php will convert (type cast) the ´$_POST` array to a boolean value of true or false. If an array is empty it will be converted to false and to true otherwise. 
		if($_POST){

			//確認有星號*的欄位是否都被填寫
			$errors = array();
			$required = array("title","brand","parent","child","price","sizes");
			$tmpLoc = array();
			$uploadPath = array();
			//確認是否符合圖片格式規定
			//自定一個格式規則
			$allowed = array("png","jpg","jpeg","gif");			
			foreach($required as $field){
				if($_POST[$field]==""){
					$errors[] = "All fields with an Astrisk required"; 
					break;
				}
			}
			$photoCount = count($_FILES['photo']['name']);

			if($photoCount > 0){
				for($i=0; $i<$photoCount; $i++){
			// 	if($_FILES["photo"]["size"] > 0){
					$name = $_FILES['photo']['name'][$i];
					$nameArray = explode(".", $name);
					$fileName = $nameArray[0];//檔名
					$fileExt = $nameArray[1];//副檔名
					$mime = explode("/",$_FILES['photo']['type'][$i]);
					$mimeType = $mime[0];
					$mimeExt = $mime[1];
					$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
					$fileSize = $_FILES['photo']['size'][$i]; 
					//由於有時候迴圈跑太快的關係,間隔可能低於1ms,導致前一張圖名和後一張圖名重複
					//所以要加個$i 來保證圖名不重複
					$uploadName = md5(microtime()).$i.".".$fileExt;
					$uploadPath[] = BASEURL."images/products/".$uploadName;
					if($i != 0){
						$dbPath .= ",";
					}
					$dbPath .= "/tutorial/images/products/".$uploadName;
					//確認欲上傳的檔案是否為image
					if($mimeType!="image"){
						$errors[] = "Ths file must be an image.";
					}
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

			// 	}
				}	
			}
			
			if(!empty($errors)){
				echo display_errors($errors);
			}else{
				if($photoCount > 0){
					//move_uploaded_file(file,newloc) ; 上傳圖片並將圖片路徑輸入進database
					for($i = 0; $i < $photoCount; $i++ ){
						move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
					}
				}
				var_dump($_FILES['photo']);
				
				$sizes = rtrim($sizes,",");
				$insertsql = "INSERT INTO product (title,price,list_price,brand,categories,image,description,sizes) VALUES ('$title','$price','$list_price','$brand','$category','$dbPath','$description','$sizes')";
				if(isset($_GET['edit'])){
					$insertsql = "UPDATE product SET title = '$title',price = '$price',list_price = '$list_price',brand='$brand',categories='$category',image='$dbPath',description='$description',sizes='$sizes' WHERE id = '$edit_id'";
				}
				$db->query($insertsql);
				header("Location: products.php");

			}
		}
		?>
		<h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit':'Add A New') ?> Product</h2><hr>
		<!-- 
		application/x-www-form-urlencoded:	
			All characters are encoded before sent (this is default)
		multipart/form-data:	
			No characters are encoded. This value is required when you are using forms that have a file upload control
		text/plain:	
			Spaces are converted to "+" symbols, but no special characters are encoded
			簡而言之有用到檔案(如照片)上傳請選擇"multipart/form-data"-->
		<form action="products.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id:'add=1') ?>" method="POST" enctype="multipart/form-data">
			<div class="form-group col-md-3">
				<label for="title">Title*:</label>
				<input type="text" name="title" id="title" class="form-control" value="<?= $title ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="brand">Brand*:</label>
				<select name="brand" id="brand" class="form-control">
					<option value="" <?= ((isset($_POST['brand']) && $_POST['brand'] == '')?'selected':'') ?>></option>
					<?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
						<option value="<?= $b['id'] ?>" <?= (($brand == $b['id'])?' selected':'') ?>>
							<?= $b['brand'] ?>		
						</option>
					<?php endwhile; ?>	
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="parent">Parent Category*:</label>
				<select name="parent" id="parent" class="form-control">
					<option value=""<?= ((isset($_POST['parent']) && $_POST['parent'] == '')?' selected':'') ?>></option>
					<?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
						<option value="<?= $p['id'] ?>"<?= (( $parent == $p['id'])?' selected':'') ?>>
							<?= $p['category'] ?>
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
				<input type="text" name="price" id="price" class="form-control" value="<?= $price ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="list_price">List Price:</label>
				<input type="text" name="list_price" id="list_price" class="form-control" value="<?= $list_price ?>">
			</div>
			<div class="form-group col-md-3">
				<label>Quantity & Sizes*:</label>
				<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false">Quantity & Sizes</button>
			</div>
			<div class="form-group col-md-3">
				<label for="sizes">Sizes & Quantity Preview</label>
				<input type="text" name="sizes" id="sizes" class="form-control" value="<?= $sizes ?>" readonly>
			</div>
			<div class="form-group col-md-6">
				<?php if($saved_image!=''): ?>
					<?php 
						$imgi = 1;
						$images = explode(",",$saved_image);
						foreach($images as $image):
					 ?>
					<div class="saved-image col-md-4">
						<img src="<?= $image ?>" alt="saved image" class="img-responsive center-block" >
						<a href="products.php?delete_image=1&edit=<?= $edit_id ?>&imgi=<?php echo $imgi; ?>" class="btn btn-warning">Delete image</a>
					</div>
					<?php
						$imgi++; 
						endforeach; 
						?>
				<?php else: ?>	
					<label for="photo">Product Photo:</label>
					<input type="file" name="photo[]" id="photo" class="form-control" multiple>
				<?php endif; ?>
			</div>
			<div class="form-group col-md-6">
				<label for="description">Description:</label>
				<textarea name="description" id="description" class="form-control" rows="6"><?= $description ?></textarea>
			</div>
			<div class="form-group pull-right">
				<a href="products.php" class="btn btn-default">Cancel</a>
				<input type="submit" class="btn btn-success" value="<?= ((isset($_GET['edit']))?'Edit':'Add A New') ?> Product">
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
		      <div class="modal-body timeline">
		      	<div class="container-fluid ">
		      		<?php for($i = 1;$i <= 12;$i++): ?>
			        	<div class="form-group col-md-2">
			        		<label for="size<?= $i ?>">Size:</label>
			        		<input type="text" name="size<?= $i ?>" id="size<?= $i ?>" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ?>" class="form-control ">
			        	</div>
			        	<div class="form-group col-md-2">
			        		<label for="qty<?= $i ?>">Quantity:</label>
			        		<input type="number" name="qty<?= $i ?>" id="qty<?= $i ?>" min="0" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:0) ?>" class="form-control">
			        	</div>
			        	<div class="form-group col-md-2">
			        		<label for="threshold<?= $i ?>">Threshold:</label>
			        		<input type="number" name="threshold<?= $i ?>" id="threshold<?= $i ?>" min="0" value="<?= ((!empty($tArray[$i-1]))?$tArray[$i-1]:0) ?>" class="form-control">
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
 					<a href="products.php?delete=<?= $product['id'] ?>" class="btn btn-xs btn-default" onclick="return confirm('Are you sure that you want to delete this product?');"><span class="glyphicon glyphicon-remove-sign"></span></a>
 				</td>
 				<td><?= $product['title'] ?></td>
 				<td><?= money($product['price']) ?></td>
 				<td><?= $category ?></td>
 				<td><a href="products.php?featured=<?= (($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?= (($product['featured'] == 0)?'plus':'minus'); ?> " ></span></a>
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

  <script>
  	jQuery("document").ready(function(){
  		getChildOptions('<?= $category ?>');
  	});
  </script>