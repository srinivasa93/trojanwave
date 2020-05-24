<?php

$target_dir = "../volunteer/";

$conn = new mysqli("localhost", "root", "", "test");
if($conn->connect_error){
  die("Connection Failed:" . $conn->connect_error);
}

elseif(isset($_POST["submit"])){
  $name = $_POST["name"];
  $email = $_POST["email"];
  $mobile = $_POST["mobile"];
  $DOB = $_POST["DOB"];
  $address = $_POST["address"];
	$filename = $_FILES["id_proof"]["name"];
	$type = $_FILES["id_proof"]["type"];
	$data = ($_FILES["id_proof"]["tmp_name"]);
  $target_file = $target_dir . basename($_FILES["id_proof"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if (file_exists($target_file)) {
    echo "Sorry, file already exists." . "<br>";
    $uploadOk = 0;
  }

  // Allow certain file formats
  elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" && $imageFileType != "pdf") {
    echo "Sorry, only JPG, JPEG, PNG, GIF & PDF files are allowed." . "<br>";
    $uploadOk = 0;
  }
  
  // To restrict the user from uploading large files 
  elseif($_FILES["id_proof"]["size"] > 50000000){
    echo "Sorry, your file is too heavy to handle." . "<br>";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  elseif ($uploadOk == 0) {
    echo "Sorry, Something went wrong." . "<br>";
  
  // if good to go, uploads
  } else {
      if (move_uploaded_file($_FILES["id_proof"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["id_proof"]["name"]). " has been uploaded." . "<br>";
        $stmt = $conn->prepare("INSERT INTO volunteer (name, email, mobile, address, DOB, id_name, id_type, id_proof) 
		values(?,?,?,?,?,?,?,?)");
  	    $stmt->bind_param("ssisssss", $name, $email, $mobile, $address, $DOB, $filename, $type, $data);
        $stmt->execute();
        echo "<script>alert('THANK YOU FOR YOUR VOLUNTEERSHIP Helping makes us Happy :)');</script>";
        $stmt->close();
        $conn->close();
  }   else {
        echo "Sorry, there was an error uploading your file." . "<br>";
  }

 }

}

?>	