</div>

	<div class="col-md-12 text-center">&copy; Copyright 2013-2019 Shaunta's Boutique</div>

	<script>
		function updateSizes(){
			var sizeString = "";
			for(var i=1;i<=12;i++){
				if(jQuery("#size"+i).val()!=""){
					sizeString += jQuery("#size"+i).val()+":"+jQuery("#qty"+i).val()+":"+jQuery("#threshold"+i).val()+",";
				}
			}
			jQuery("#sizes").val(sizeString);
		}

		//根據parent category的選擇而在child category產生不同的option
		//jQuery change() 方法
		//定义和用法
		//当元素的值改变时发生 change 事件（仅适用于表单字段）。
		//change() 方法触发 change 事件，或规定当发生 change 事件时运行的函数。
		//注意：当用于 select 元素时，change 事件会在选择某个选项时发生。当用于 text field 或 text area 时，change 
		//事件会在元素失去焦点时发生。

		jQuery("select[name='parent']").change(function(){
			getChildOptions();
		});
		function getChildOptions(selected){
			if(typeof selected === "undefined"){
				var selected = "";
			}

			var parentID = jQuery('#parent').val();
			jQuery.ajax({
				type:"POST",
				url:"/tutorial/admin/parsers/child_categories.php",
				data:{parentID:parentID,selected:selected},
				success:function(data){
					jQuery("#child").html(data);
				},
				error:function(){
					alert("Somethings went wrong with child_category");
				},
			})
		}

		//5000ms後清掉登入訊息
		jQuery("document").ready(function(){
 			setTimeout(function(){
 				jQuery("#success_flash").html("");
 				jQuery("#error_flash").html("");
 			},5000);
 		});
	</script>


</body>
</html>