<?php 
	require_once '../core/init.php';
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		//loginErrorRedirect();
		header("Location: login.php");
	}
	// //確認權限是否足夠
	// if(!hasPermission()){
	// 	//不足時
	// 	permissionErrorRedirect("brands.php");
	// }

	include 'includes/head.php';
	include 'includes/navigation.php';
	

 ?>

<?php 
	//取出交易記錄
	$txnQ = "SELECT t.id,t.cart_id,t.full_name,t.description,t.txn_date,t.grand_total,c.items,c.paid,c.shipped
			FROM transaction t 
			LEFT JOIN cart c ON t.cart_id = c.id
			WHERE c.paid = 1 AND c.shipped = 0
			ORDER BY t.txn_date";

	$txnResults = $db->query($txnQ);		
 ?>

<div class="col-md-12">
	<!-- 已付款但還沒寄出的表格 -->
	<h3 class="text-center">Orders to ship</h3>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<th></th>
			<th>Name</th>
			<th>Description</th>
			<th>Total</th>
			<th>Date</th>
		</thead>
		<tbody>
			<?php while($order = mysqli_fetch_assoc($txnResults)): ?>
				<tr>
					<td><a href="orders.php?txn_id=<?php echo $order['id'] ?>" class="btn btn-xs btn-info">Details</a></td>
					<td><?php echo $order['full_name'] ?></td>
					<td><?php echo $order['description'] ?></td>
					<td><?php echo $order['grand_total'] ?></td>
					<td><?php echo $order['txn_date'] ?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>

</div>

<div class="row">
	<!--Sales By Month -->
	<?php 
		$thisYr = date("Y");
		$lastYr = $thisYr - 1;
		$thisYrQ = $db->query("SELECT grand_total,txn_date FROM transaction WHERE YEAR(txn_date) = '{$thisYr}'");
		$lastYrQ = $db->query("SELECT grand_total,txn_date FROM transaction WHERE YEAR(txn_date) = '{$lastYr}'");
		$current = array();
		$last = array();
		$currentTotal = 0;
		$lastTotal = 0;
		//將今年每個月的收入做個別加總
		while($x = mysqli_fetch_assoc($thisYrQ)){
			//先將日期從字串轉成時間格式 再從中抽取"月"並放入$month
			$month = date("n",strtotime($x['txn_date']));//用"m"來取代"n"的話會出問題
			//判斷$month是否已作為key並加到current[]裡
			if(!array_key_exists($month,$current)){
				//如果不在current[]裡的話
				$current[(int)$month] = $x['grand_total'];
			}else{
				//如果在current[]裡的話
				$current[(int)$month] += $x['grand_total'];
			}
			//將今年的收入做加總
			$currentTotal += $x['grand_total'];
		}

		//var_dump($current);
		//如果array() 裡的key所對應的值爲null的話 則該key不存在於此array裡

		//將去年每個月的收入做個別加總
		while($x = mysqli_fetch_assoc($lastYrQ)){
			//先將日期從字串轉成時間格式 再從中抽取"月"並放入$month
			$month = date("n",strtotime($x['txn_date']));
			//判斷$month是否已作為key並加到current[]裡
			if(!array_key_exists($month,$last)){
				//如果不在current[]裡的話
				$last[(int)$month] = $x['grand_total'];
			}else{
				//如果在current[]裡的話
				$last[(int)$month] += $x['grand_total'];
			}
			//將去年的收入做加總
			$lastTotal += $x['grand_total'];
		}		
		?>
	<!-- Sales By Month -->
	<div class="col-md-4">
		<h3 class="text-center">Sales By Month</h3>
		<table class="table table-bordered table-condensed ">
			<thead>
				<th></th>
				<th><?php echo $lastYr; ?></th>
				<th><?php echo $thisYr ?></th>
			</thead>
			<tbody>
				<?php for($i = 1;$i <= 12;$i++): 
					//取得英文的月份名
					//詳細可參考http://php.net/manual/en/datetime.createfromformat.php
					//		   http://php.net/manual/en/datetime.format.php
					$dt = DateTime::createFromFormat("!m",$i);//return a DateTime object
					//賦予不在陣列裡的月份值,以便後續的計算
					if(!array_key_exists($i, $last)){$last[$i] = 0;}	
					if(!array_key_exists($i, $current)){$current[$i] = 0;}
					?>
					<!-- 當該月有收入時變更該列的背景顏色 -->
					<tr <?php echo ($current[$i] > $last[$i])?'class="bg-info"':'class="bg-warning"'; ?>>
						<td><?php echo $dt->format("F");//取得英文的月份名 ?></td>
						<td><?php echo money($last[$i]); ?></td>
						<td><?php echo money($current[$i]); ?></td>
					</tr>
				<?php endfor; ?>
				<tr>
					<td>Total</td>
					<td><?php echo money($lastTotal); ?></td>
					<td><?php echo money($currentTotal); ?></td> 
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Low Inventory -->
	<?php 
		//選擇未被下架的商品
		$lowQ = $db->query("SELECT * FROM product WHERE deleted = 0");
		//用於記錄庫存低於threshold的商品
		$lowItems = array();
		while($product = mysqli_fetch_assoc($lowQ)){
			$item = array();
			$sizes = sizesToArray($product['sizes']);//回傳一個二維陣列
			//取得該商品的category及category的parent
			$cat = getCategory($product['categories']);//回傳一個關聯陣列
			foreach($sizes as $size){
				//僅挑選庫存<=threshold的商品
				if($size['quantity'] <= $size['threshold']){
					$item = array(
						"title" => $product['title'],
						"size" => (isset($size['size']))?$size['size']:"",
						"quantity" => (isset($size['quantity']))?$size['quantity']:"",
						"threshold" => (isset($size['threshold']))?$size['threshold']:"",
						"category" => $cat['parent']."~".$cat['child'],
					);
					$lowItems[] = $item;
				}
			}
		}
	 ?>
	<div class="col-md-8">
		<h3 class="text-center">Low Inventory</h3>
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<th>Product</th>
				<th>Category</th>
				<th>Size</th>
				<th>Quantity</th>
				<th>Threshold</th>
			</thead>
			<tbody>
				<?php foreach($lowItems as $lowItem): ?>
					<tr <?php echo ($lowItem['quantity'] ==0 )?' class="bg-danger"':'' ?>>
						<td><?php echo $lowItem['title']; ?></td>
						<td><?php echo $lowItem['category']; ?></td>
						<td><?php echo $lowItem['size']; ?></td>
						<td><?php echo $lowItem['quantity']; ?></td>
						<td><?php echo $lowItem['threshold']; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

 <?php 
 	include 'includes/footer.php';
 ?>