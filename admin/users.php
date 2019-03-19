<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
	//確認是否登入成功
	if(!isLoggedIn()){
		//登入失敗時執行
		loginErrorRedirect();
	}
	//確認權限是否足夠
	if(!hasPermission("admin")){
		//不足時
		permissionErrorRedirect("brands.php");
	}

	include 'includes/head.php';
	include 'includes/navigation.php';

	

	//delete user
	if(isset($_GET['delete'])){
		$delete_id = sanitize($_GET['delete']);
		$db->query("DELETE FROM users WHERE id = '$delete_id'");
		$_SESSION['success_flash'] = "The user has been deleted.";
		header("Location: users.php");
	}

	//add user
	if(isset($_GET['add'])){ 

		$name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
		$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
		$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
		$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
		$permission = ((isset($_POST['permission']))?sanitize($_POST['permission']):'');
		$errors = array();

		//validate form
		if($_POST){
			//check if the email already exists
			$emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
			$emailCount = mysqli_num_rows($emailQuery);
			if($emailCount>0){
				$errors[] = "The email has already been used";
			}else{
				//check if all the fields are filled out
				$required = array("name","email","password","confirm","permission");
				foreach($required as $f){
					if(empty($_POST[$f])){
						$errors[] = "You must fill out all fields.";
						break;
					}
				}

				// check if password has at least 6 characters
				if(strlen($password)<6){
					$errors[] = "The password must be at least 6 characters";
				} 

				// check if password matches confirm
				if($password != $confirm){
					$errors[] = "The password must match the confirm";
				}

				// check if the email is valid
				if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					$errors[] = "The email must be valid";
				}
			}

			//check errors
			if(!empty($errors)){
				echo display_errors($errors);
			}else{
				//add a user
				$hashed_password = password_hash($password,PASSWORD_DEFAULT);
				$db->query("INSERT INTO users(full_name,email,password,permissions) VALUES ('$name','$email','$hashed_password','$permission')");
				$_SESSION['success_flash'] = "A new user has been added.";
				header("Location: users.php");
			}
			
		}
		
		?>

		<h2>Add A New User</h2><hr>
		<form action="users.php?add=1" method="post">
			<div class="form-group col-md-6">
				<label for="name">Full Name:</label>
				<input type="text" name="name" id="name" class="form-control" value="<?= $name ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" class="form-control" value="<?= $email ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" class="form-control" value="<?= $password ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="confirm">Confirm Password:</label>
				<input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="permission">Permission:</label>
				<select name="permission" id="permission" class="form-control">
					<option value="" <?= (($permission == "")?' selected':'') ?>></option>
					<option value="editor" <?= (($permission == "editor")?' selected':'') ?>>editor</option>
					<option value="admin,editor" <?= (($permission == "admin,editor")?' selected':'') ?>>admin,editor</option>
				</select>
			</div>
			<div class="form-group pull-right col-md-6 text-right">
				<br>
				<a href="users.php" class="btn btn-default">Cancel</a>
				<input type="submit" class="btn btn-primary" value="Add A New User">
			</div>

		</form>
<?php }else{
		$usersQuery = $db->query("SELECT * FROM users ORDER BY full_name");

 ?>

<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add_product_btn">Add A New User</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th></th>
		<th>Name</th>
		<th>Email</th>
		<th>Join Date</th>
		<th>Last Login</th>
		<th>Permissons</th>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($usersQuery)): ?>
			<tr>
				<td>
					<?php if($user['id'] != $user_data['id']): ?>
						<a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-xs btn-default" onclick="return confirm('Are you sure that you want to delete this user?');">
							<span class="glyphicon glyphicon-remove-sign"></span>
						</a>
					<?php endif; ?>	
				</td>
				<td><?= $user['full_name'] ?></td>
				<td><?= $user['email'] ?></td>
				<td><?= $user['join_date'] ?></td>
				<td><?= (($user['last_login'] == "0000-00-00 00:00:00")?"Never":$user['last_login']); ?></td>
				<td><?= $user['permissions'] ?></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

 <?php 
 	} //endelse
 	include 'includes/footer.php';
 ?>