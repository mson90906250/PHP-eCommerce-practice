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
				url:<?= BASEURL; ?>+"includes/detailsmodal.php",
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
	</script>
</body>
</html>