<?php
	include 'connect.php';
	
//Create Album
	if(isset($_POST['create-album'])){
		$albumName = $_POST['album-name'];
		
	//check if an album name has been entered, if empty display an error messages
		if(empty($albumName)){
			$albumNameError = "Album name can not be empty";
		} else { //insert into database
			$sql = "INSERT INTO albums(name) VALUES('$albumName')";
			$query = mysqli_query($connect, $sql);
			if($query){
				$albumCreateSuccess = "Album successfully created.";
			}else{
				$albumCreateError = "Album could not be created, please try again later.";
			}
		}
	}
	
//Upload Photo
		if(isset($_POST['upload-photo'])){
			$caption = $_POST['caption'];
			$album = $_POST['album'];
			$photo   = $_FILES['photo']['name'];
			$temp = $_FILES['photo']['tmp_name']; //temporary file 
			$path = "photos/".$photo;
			
		//check if a caption has been entered or photo chosen, if empty display an error messages
			if(empty($caption)){
				$captionError = "Caption can not be empty";
			}
			if(empty($photo)){
				$photoError = "You must select a photo to upload";
			}
		//no errors, insert into the database
			if(move_uploaded_file($temp, $path)){
				$sql = "INSERT INTO photos(path, caption, album) VALUES('$path', '$caption', '$album')";
				$query = mysqli_query($connect, $sql);
				if($query){
					$photoUploadSuccess = "Photo uploaded successfully";
				}else {
					$photoUploadError = "Photo not uploaded, try again later";
				}
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
<title>My Photo Album | TutorialsLodge</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="wrapper">
		<div class="albums">
			<h3>Albums</h3>
			<span class="success"><?php if(isset($albumCreateSuccess)) echo $albumCreateSuccess ?></span>
				<form action="" method="post">
				<table>
					<tr>
						<td><label for="album-name">Album Name</label></td>
						<td><input type="text" name="album-name"><span class="error"><?php if(isset($albumNameError)) echo $albumNameError ?></span></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="create-album" value="Create Album"></td>
					</tr>
				</table>
			</form>
			<span class="error"><?php if(isset($albumCreateError)) echo $albumCreateError ?></span>
			<?php
				$sql = "SELECT * FROM albums";
				$query = mysqli_query($connect, $sql);
				$count = mysqli_num_rows($query); //count the number of rows in the albums table
				if($count == 0){
					echo "You have not created any album";
				} else{
					echo "Albums you have created:";
					echo "<ul>";
					while($albums = mysqli_fetch_assoc($query)){
						echo "<li>".$albums['name']."</li>";
					}
					echo "</ul>";
				}
			?>
		</div>
		<div class="photos">
			<h3>Photos</h3>
			<div class="uload-photo">
				<span class="success"><?php if(isset($photoUploadSuccess)) echo $photoUploadSuccess ?></span>
				<form action="" method="post" enctype="multipart/form-data">
					<table>
						<tr>
							<td><label for="caption">Caption</label></td>
							<td><input type="text" name="caption"><span><?php if(isset($captionError)) echo $captionError ?></span>
</td>
						</tr>
						<tr>
							<td><label for="album">Select Album</label></td>
							<td>
								<select name="album">
									<option value="">Select Album</option>
									<?php
										$sql = "SELECT * FROM albums";
										$query = mysqli_query($connect, $sql);
										while($albums = mysqli_fetch_assoc($query)){
											$id = $albums['id'];
											$name = $albums['name'];
											echo "<option value=$id>".$name."</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="photo">Choose Photo</label></td>
							<td><input type="file" name="photo"><span><?php if(isset($photoError)) echo $photoError ?></span>
</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="upload-photo" value="Upload Photo"></td>
						</tr>
					</table>
				</form>
				<span class="error"><?php if(isset($photoUploadError)) echo $photoUploadError ?></span>
			</div>
			<div class="photo-gallery">
			<?php
				$sql1 = "SELECT * FROM albums";
				$query1 = mysqli_query($connect, $sql);
				while($albumId = mysqli_fetch_assoc($query1)){
					$ID = $albumId['id'];
					$albm = $albumId['name'];
					
						$sql2 = "SELECT * FROM photos WHERE album = '$ID'";
						$query2 = mysqli_query($connect, $sql2);
						while($photos = mysqli_fetch_assoc($query2)){
							$cap = $photos['caption'];
							$source = $photos['path'];
						?>
							<div class="thumbnail">
								<?php //echo $cap; ?>
								<img src="<?php echo $source;?>">
								<?php //echo "Photo uploaded to: "."<b>".$albm."</b>"; ?>
							</div>
						<?php
						}  
				}
				$sql3 = "SELECT * FROM photos";
				$query3 = mysqli_query($connect, $sql3);
				$count = mysqli_num_rows($query3); //count the number of rows in the photos table
				if($count == 0){
					echo "<p>"."You currently have to no photos."."</p>";
				}
			?>
			<div class="clear"></div>
			</div>	
		</div>
	</div>
</body>
</html>