<?php

include('StoreOperations.php');
 //Make sure that it is a POST request.

 if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';		
	error_log(print_r("content-type ".$contentType, TRUE)); 
	$content = trim(file_get_contents("php://input"));

	//Attempt to decode the incoming RAW post data from JSON.
	$decoded = json_decode($content, true);
	error_log(print_r($decoded, TRUE));

	//If json_decode failed, the JSON is invalid.
	if ($decoded['method'] != Constants::$addImages) {
		if(!is_array($decoded)){
			throw new Exception('Received content contained invalid JSON!');
		}
	}
	$storeOpration = new StoreOperations();
	if($decoded["params"]["method"] == Constants::$ownerRegister) {
		// echo $str = implode(',', $decoded["params"]["data"]);
		$storeOpration->registerOwner($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$ownerlogin) {
		$storeOpration->loginOwnerUSer($decoded["params"]["data"]);
	} else if($decoded['method'] == Constants::$addImages) {
		$storeOpration->uploadImage($decoded['image']);
	}
}

class Constants
{
	
	 public static $Customerregister = "customerregister";
	 public static $ownerRegister = "registerOwner";
	 public static $addProduct = "addProduct";
	 public static $addAddress = "addAddress";
	 public static $placeOrder = "placeOrder";
	 public static $addoffers = "addoffers";
	 public static $addImages = "addImages";
	 public static $customerlogin = "customerlogin";
	 public static $ownerlogin = "ownerlogin";
	 public static $getAddress = "getAddress";
	 public static $getoffers = "getoffers";
	 public static $getProducts = "getProducts";
	 public static $getImagedata = "getImagedata";
	 public static $getImage = "getImage";
	 public static $getOrders = "getOrders";
	 public static $getCustomerOrders = "getCustomerOrders";
	 public static $deleteOwnerProduct = "deleteOwnerProduct";
	 public static $addcategory = "addcategory";
	 public static $addsubcategory = "addsubcategory";
	 public static $getcategories = "getcategories";
	 public static $getSubcategories = "getSubcategories";
	 public static $getcatProducts = "getcatProducts";
	 public static $deleteImage = "deleteImage";
	 public static $getAllCategories = "getAllCategories";
	 public static $updateProduct = "updateProduct";
	 public static $updatetoCompleteOrder = "updatetoCompleteOrder";
	 public static $updatePasword = "updatePasword";
	 public static $updatetoCancelOrder = "updatetoCancelOrder";
	 public static $updatetoOrderStatus = "updatetoOrderStatus";

}

?>