<?php
include('DBValues.php');
class DbOperations
{

    private function dbConnection()
    {
        $servername = DBValues::$servername;
        $username = DBValues::$username;
        $password = DBValues::$password;
        $dbname = DBValues::$dbname;
		
        // Create connection
        $con = mysqli_connect($servername, $username, $password, $dbname);

        if (mysqli_connect_errno()) {
            // Handle connection error
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        return $con;
    }

	public function isRegisterOwner($phoneNumber)
    {
		$con = $this->dbConnection();
		$insertQuery = "select * FROM StoresTable WHERE phoneNumber = '$phoneNumber'";
		
		$result = mysqli_query($con, $insertQuery);
		$rowcount = mysqli_num_rows($result);
		mysqli_close($con);
		if ($rowcount > 0) {
			return true;
		}
		return false;
    }
	
	public function insertintoStoreTable($customerName, $storeName,
												 $phoneNumber, $storeId, $address,
												  $area, $city, $pincode, $rating, $password) {
		$con = $this->dbConnection();
		$insertQuery = "INSERT INTO StoresTable(customerName, storeName, phoneNumber, storeId, address, area, city, pincode, rating, password) VALUES ('$customerName', '$storeName', '$phoneNumber', '$storeId', '$address', '$area', '$city','$pincode', '$rating', '$password')";
		if(mysqli_query($con, $insertQuery)){
			return TRUE;
		} else {
			return FALSE;

		} 
		mysqli_close($con);
	}
	
	public function getOwnerDetails($phoneNumber, $password)
    {
		$con = $this->dbConnection();
        $insertQuery = "SELECT * FROM StoresTable WHERE phoneNumber = ?";
        $stmt = mysqli_prepare($con, $insertQuery);

        if ($stmt === false) {
            // Handle statement preparation error
            die("Error in preparing statement: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $array = [];

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['password'] === $password) {
                $array["customerName"] = $row['customerName'];
                $array["storeName"] = $row['storeName'];
                $array["storeId"] = $row['storeId'];
                $array["area"] = $row['area'];
                $array["city"] = $row['city'];
                $array["pincode"] = $row['pincode'];
                $array["rating"] = $row['rating'];
            } else {
                $array["error"] = "password wrong";
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $array;
    }

	function loadImage($id) {
        $con = $this->dbConnection();
        $selectQuery = "SELECT imageData FROM ImagesTable WHERE id = ?";
        $stmt = $con->prepare($selectQuery);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($imageData);
            $stmt->fetch();

            header("Content-Type: image/jpeg"); // Change the content type based on your image type
            echo $imageData;
        } else {
            echo "Image not found.";
        }

        $stmt->close();
        $con->close();
	}

	public function insertIntoImagesTable($imageid, $file) {
		$con = $this->dbConnection();
		
		$sql = "INSERT INTO `ImagesTable` (`id`, `imageData`) VALUES (?, ?)";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("ss", $imageid, $file);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			return false;
		}

	}


}

?>
