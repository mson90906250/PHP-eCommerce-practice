	</div>

	<footer class="text-center" id="footer">&copy; Copyright 2013-2019 Shaunta's Boutique</footer>



	<script>
		// $(window).scroll(function(){
		// 	var vscroll = $(this).scrollTop();
		// 	console.log(vscroll);
		// });

		//為避免在使用$使跟其他js產生衝突 所以使用jQuery()
		//選擇window,並做滾動卷軸的監聽
		jQuery(window).scroll(function(){
			//將滾軸的相對於頂部的位置回傳給vscroll
			var vscroll = jQuery(this).scrollTop();
			//console.log(vscroll);
			jQuery("#logotext").css({
				// tranform有多種用法 像translate(平移)是其中一種,translate(x,y),x為正向右平移 y為正則向下
				"transform" : "translate(0px,"+vscroll/2+"px)"
			});

			jQuery("#back-flower").css({
				"transform" : "translate("+vscroll/5+"px,-"+vscroll/12+"px)"
			});

			jQuery("#fore-flower").css({
				"transform" : "translate(0px,-"+vscroll/2+"px)"
			});
		});

		function detailsmodal(id){
			var data = {"id":id};
			jQuery.ajax({
				url: "/tutorial/includes/detailsmodal.php",
				method:"post",
				data: data,
				success: function(data){
					// 此時的data是detailsmodal.php裡有關modal的程式碼
					jQuery("body").append(data);
					jQuery("#details-modal").modal('toggle');
				},
				error: function(){
					alert("Something went wrong!");
				}
			})
		}

		function add_to_cart(){
			jQuery("#modal_errors").html("");
			var size = jQuery("#size").val();
			var quantity = jQuery("#quantity").val();
			quantity = Number(quantity);
			var available = jQuery("#available").val();
			available = Number(available);
			var error = "";

			//serialize() 方法通过序列化表单(form)值，创建 URL 编码文本字符串。
			//您可以选择一个或多个表单元素（比如 input 及/或 文本框），或者 form 元素本身。
			//序列化的值可在生成 AJAX 请求时用于 URL 查询字符串中。
			var data = jQuery("#add_product_form").serialize();

			if(size == "" || quantity == "" || quantity == 0 ){
				error += '<p class="text-danger text-center">You must choose a size and quantity</p>';
				jQuery("#modal_errors").html(error);
				return;
			}else if(quantity > available){
				error += '<p class="text-danger text-center">There are only '+available+' available.</p>';
				jQuery("#modal_errors").html(error);
				return;
			}else{
				jQuery("#modal_errors").html("");
				jQuery.ajax({
					url:"/tutorial/admin/parsers/add_cart.php",
					method:"post",
					data: data,
					success: function(){
						location.reload();
					},
					error: function(){alert("Something went wrong with /tutorial/admin/parsers/add_cart.php")}
				});
			}
			
		}
	</script>
</body>
</html>