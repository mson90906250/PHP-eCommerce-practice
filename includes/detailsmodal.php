	<!-- Details Modal -->
	<div class="modal fade details-1" id="details-1" role="dialog" tabindex="-1" aria-labelledby="details-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title text-center">Levis Jeans</h4>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6">
								<div class="center-block">
									<img src="images/products/men4.png" alt="Levis Jeans" class="details img-responsive">
								</div>
							</div>
							<div class="col-sm-6">
								<h4>Details</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam similique itaque assumenda consequuntur reiciendis non quis incidunt nihil blanditiis id, quia, eaque deleniti aliquid eligendi ipsam a accusamus numquam voluptates?</p>
								<hr>
								<p>Price: $34.99</p>
								<p>Brand: Levis Jeans</p>
								<form action="add_cart.php" method="post">
									<div class="form-group">
										<p>Availible: 3</p>
										<div class="row">
											<div class="col-xs-3">
												<label for="quantity">Quantity:</label>
												<input type="text" class="form-control" id="quantity" name="quantity">
											</div>
											<div class="col-xs-9"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="size">Size:</label>
										<select name="size" id="size">
											<option value=""></option>
											<option value="28">28</option>
											<option value="32">32</option>
											<option value="36">36</option>
										</select>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Close</button>
					<button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
				</div>
			</div>
		</div>
		
	</div>