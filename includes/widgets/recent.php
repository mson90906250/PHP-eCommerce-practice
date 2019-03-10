<h3 class="text-center">Popular Items</h3>
<?php 
	//從交易記錄中挑最新的5筆當作熱門商品
	$transQ = $db->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");
	//將每一筆記錄存放到$result[]裡
	$result = array();
	//在熱門商品中 不希望已出現過的商品再出現一次 所以用$used_id[]來記錄曾出現的id
	$used_ids = array();
	while($row = mysqli_fetch_assoc($transQ)){
		$result[] = $row;
		$json_items = $row['items'];
		$items = json_decode($json_items,true);
		foreach($items as $item){
			//判斷商品是否重複
			if(!in_array($item['id'], $used_ids)){
				$used_ids[] = $item['id'];
			}
		}
		
	}
 ?>

<div id="recent_widget">
	<table class="table table-condensed">
		<?php foreach($used_ids as $id): 
			$productQ = $db->query("SELECT id,title FROM product WHERE id = '{$id}'");
			$product = mysqli_fetch_assoc($productQ);
		 ?>
		 <tr>
		 	<td><?php echo $product['title']; ?></td>
		 	<td><a class="text-primary" onclick="detailsmodal(<?php echo $product['id'] ?>)">View</a></td>
		 </tr>
		<?php endforeach; ?>
	</table>
</div>
