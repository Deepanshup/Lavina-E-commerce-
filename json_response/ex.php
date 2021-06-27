<?php
include("../wp-config.php");
try {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
    //http://jobmafiaa.com/products/lavina/json_response/ex.php?email=deepanshu01pathak@gmail.com&stts=1&total_count=5&totalprice=100&fname=deepanshu&lname=pathak&street=palasia&city=indore&state=mp&country=INDIA&postcode=452001&b_email=avl.gauravpatidar@gmail.com&b_cn=7734826695&p_method=cod&place_order_2=Submit
    if(isset($_REQUEST["place_order_2"])){
        $email=$_GET['email'];
        $stts=$_GET['stts'];
        $count=$_GET['total_count'];
        $totalprice=$_GET['totalprice'];
        $fname=$_GET['fname'];
        $lname=$_GET['lname'];
        $street=$_GET['street'];
        $city=$_GET['city'];
        $state=$_GET['state'];
        $country=$_GET['country'];
        $postcode=$_GET['postcode'];
        $b_email=$_GET['b_email'];
        $b_cn=$_GET['b_cn'];
        $p_method=$_GET['p_method'];
        date_default_timezone_set("Asia/Kolkata");
        $date=date("Y-m-d")." ".date("H:i:s");
        $meta_value=array($fname,$lname,$street,$city,$state,$postcode,$country,$b_email,$b_cn,$p_method);
        $value=array('_billing_first_name','_billing_last_name','_billing_address_1','_billing_city','_billing_state','_billing_postcode','_billing_country','_billing_email','_billing_phone','_payment_method_title');
        if($stts=='true')
        {
            $stts=1;
        }
        else
        {
            $stts=0;
        }
        $i=0;
        $Sql_Query = "select ID,user_login,user_nicename from wp_users where `user_email`='$email' and user_status='$stts'";
        $result = mysqli_query($con,$Sql_Query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $sql = "select customer_id from `wp_wc_customer_lookup`  WHERE `email`='$email' && user_id='$row[0]'";
                $result = mysqli_query($con,$sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $sql = "select MAX(ID) as max from `wp_posts`";
                        $result = mysqli_query($con,$sql);
                        $rows = mysqli_fetch_array( $result );
                        $largestNumber = $rows['max']+1;
                        $d1=date('F d,Y @ H:i A');
                        $d2=date('Y-F-d-Hi-a');
                        $sqll="INSERT INTO `wp_posts`(`ID`, `post_date`, `post_date_gmt`, `post_title`, `post_status`, `comment_status`, `ping_status`,`post_name`,`post_modified`, `post_modified_gmt`,`guid`,`post_type`) 
                            VALUES ('$largestNumber','$date','$date','Order &ndash; $d1','wp-processing','open','open','order-$d2','$date','$date','http://jobmafiaa.com/products/lavina/?post_type=shop_order&#038;p=$largestNumber','shop_order')";
        				if ($con->query($sqll)) {
        				    $sqll = "INSERT INTO `wp_wc_order_stats`(`order_id`,`date_created`, `date_created_gmt`, `num_items_sold`, `total_sales`, `net_total`, `status`, `customer_id`) 
                                VALUES ('$largestNumber','$date','$date','$count','$totalprice','$totalprice','wp-processing','$row[0]')";
                            if ($con->query($sqll)) {
                                $sql= "select `post_id`,`count` from `cart` where `email`='$email'";
                                $result = mysqli_query($con,$sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($rowss = mysqli_fetch_array($result)) {
                                        $sqlls = "select meta_key,meta_value from `wp_postmeta`  WHERE `post_id`='$rowss[0]'";
                                        $resultl = mysqli_query($con,$sqlls);
                                        if (mysqli_num_rows($resultl) > 0) {
                        			        while ($rowl = mysqli_fetch_array($resultl)) {
                                                if($rowl[0]=='_price'){
                        				            $sqlt = "select post_title FROM `wp_posts`  WHERE `ID`=$rowss[0]";
                        				            $resultt = mysqli_query($con,$sqlt);
                        				            if (mysqli_num_rows($resultt) > 0) {
                        					            while ($rowt = mysqli_fetch_array($resultt)) {
                        						            $sqllk="INSERT INTO `wp_woocommerce_order_items`(`order_item_name`, `order_item_type`,`order_id`) VALUES ('$rowt[0]','line_item','$largestNumber')";
                                                            if ($con->query($sqllk)) {
                                                                $aeae="SELECT `order_item_id` FROM `wp_woocommerce_order_items` WHERE `order_item_name`='$rowt[0]' and `order_id`=$largestNumber";
                                                                $resultsssh = mysqli_query($con,$aeae);
                                                                $rowsf = mysqli_fetch_array( $resultsssh );
                                                                $nmj = $rowsf['order_item_id'];
                                                                $sqllm = "INSERT INTO `wp_wc_order_product_lookup`(`order_item_id`,`order_id`, `product_id`, `customer_id`, `date_created`, `product_qty`, `product_net_revenue`) 
                                                                        VALUES ('$nmj','$largestNumber','$rowss[0]','$row[0]','$date','$rowss[1]','$rowl[1]')";
                                                                if ($con->query($sqllm)) {
                                                                    $response="Success";
                                                                }
                                                            }
                        					            }
                        				            }
                        			            }
                        		            }
                                       }
                                    }
                                }
                                for($i=0;$i<sizeof($value);$i++){
                                    $sqlllk = "INSERT INTO `wp_postmeta`( `post_id`, `meta_key`, `meta_value`) VALUES ('$largestNumber','$value[$i]','$meta_value[$i]')";
                                    if ($con->query($sqlllk)) {
                                        $response="Success";
                                    }
                                }
                            }
        				}
                    }
                     echo json_encode($response);
                }
                else {
        	        $sql = "INSERT INTO `wp_wc_customer_lookup`(`user_id`, `username`, `first_name`, `last_name`, `email`,`date_registered`, `country`, `postcode`, `city`, `state`) 
        	                                            VALUES ($row[0],'$row[1]','$fname','$lname','$email','$date','$country','$postcode','$city','$state')";
                    if ($con->query($sql)) {
                        $sql = "select customer_id from `wp_wc_customer_lookup`  WHERE `email`='$email' && user_id=$row[0]";
                        $result = mysqli_query($con,$sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $sql = "select MAX(ID) as max from `wp_posts`";
                                $result = mysqli_query($con,$sql);
                                $rows = mysqli_fetch_array( $result );
                                $largestNumber = $rows['max']+1;
                                $sqll="INSERT INTO `wp_posts`(`ID`, `post_date`, `post_date_gmt`, `post_title`, `post_status`, `comment_status`, `ping_status`,`post_name`,`post_modified`, `post_modified_gmt`,`guid`,`post_type`) 
                                    VALUES ('$largestNumber','$date','$date','Order &ndash; date('F d,Y @ H:i A')','wp-processing','open','open','order-date('Y-F-d-Hi-a')','$date','$date','http://jobmafiaa.com/products/lavina/?post_type=shop_order&#038;p=$largestNumber','shop_order')";
                				if ($con->query($sqll)) {
                				    $sqll = "INSERT INTO `wp_wc_order_stats`(`order_id`,`date_created`, `date_created_gmt`, `num_items_sold`, `total_sales`, `net_total`, `status`, `customer_id`) 
                                        VALUES ('$largestNumber','$date','$date','$count','$totalprice','$totalprice','wp-processing','$row[0]')";
                                    if ($con->query($sqll)) {
                                        $sql= "select `post_id`,`count` from `cart` where `email`='$email'";
                                        $result = mysqli_query($con,$sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($rowss = mysqli_fetch_array($result)) {
                                                $sqlls = "select meta_key,meta_value from `wp_postmeta`  WHERE `post_id`='$rowss[0]' ";
                                                $resultl = mysqli_query($con,$sqlls);
                                                if (mysqli_num_rows($resultl) > 0) {
                                			        while ($rowl = mysqli_fetch_array($resultl)) {
                                                        if($rowl[0]=='_price'){
                                				            $sqlt = "select post_title FROM `wp_posts`  WHERE `ID`=$rowss[0]";
                                				            $resultt = mysqli_query($con,$sqlt);
                                				            if (mysqli_num_rows($resultt) > 0) {
                                					            while ($rowt = mysqli_fetch_array($resultt)) {
                                						            $sqllk="INSERT INTO `wp_woocommerce_order_items`(`order_item_name`, `order_item_type`,`order_id`) VALUES ('$rowt[0]','line_item','$largestNumber')";
                                                                    if ($con->query($sqllk)) {
                                                                        $aeae="SELECT `order_item_id` FROM `wp_woocommerce_order_items` WHERE `order_item_name`='$rowt[0]' and `order_id`=$largestNumber";
                                                                        $resultsssh = mysqli_query($con,$aeae);
                                                                        $rowsf = mysqli_fetch_array( $resultsssh );
                                                                        $nmj = $rowsf['order_item_id'];
                                                                        $sqllm = "INSERT INTO `wp_wc_order_product_lookup`(`order_item_id`,`order_id`, `product_id`, `customer_id`, `date_created`, `product_qty`, `product_net_revenue`) 
                                                                                VALUES ('$nmj','$largestNumber','$rowss[0]','$row[0]','$date','$rowss[1]','$rowl[1]')";
                                                                        if ($con->query($sqllm)) {
                                                                            
                                                                        }
                                                                    }
                                					            }
                                				            }
                                			            }
                                		            }
                                               }
                                            }
                                        }
                                        for($i=0;$i<sizeof($value);$i++){
                                            $sqlllk = "INSERT INTO `wp_postmeta`( `post_id`, `meta_key`, `meta_value`) VALUES ('$largestNumber','$value[$i]','$meta_value[$i]')";
                                            if ($con->query($sqlllk)) {
                                                $response="Success";
                                            }
                                        }
                                    }
                				}
                            }
                        }
                    }
                    echo json_encode($response);
                }
            }
        }
      mysqli_close($con);
    }
    
    // if(isset($_REQUEST["sp_register"])){
    //     $fname = $_GET['fname'];
    //     $lname = $_GET['lname'];
    //     $fullname = $_GET['fname'].' '.$_GET['lname'];
    //     $username = $_GET['name'];
    //     $email = $_GET['email'];
    //     $password = $_GET['password'];
    //     $contact_no = $_GET['contact_no'];
    //     $user_status = $_GET['user_status'];
    //     if($user_status=='true'){
    //         $CheckSQL = "select * from wp_users where user_email='$email' and user_status=-1";
    //         $check = mysqli_fetch_array(mysqli_query($con,$CheckSQL));
    //         if(isset($check)){
    //             $response = "Email already exists.";
    //             echo json_encode($response);
    //         }
    //         else{
    //             $CheckSQL = "select * from wp_users where user_email='$email' and user_status=1";
    //             $check = mysqli_fetch_array(mysqli_query($con,$CheckSQL));
    //             if(isset($check)){
    //                 $response = "Email already exists.";
    //                 echo json_encode($response);
    //             }
    //             else{
    //                 $Sql_Query = "INSERT INTO `wp_users`(`user_login`,`user_pass`,`user_nicename`,`user_email`,`user_url`,`display_name`,`user_status`) VALUES
    //                         ('$username','$password','$fname','$email','$contact_no','$fullname',-1)";
    //                 if($con->query($Sql_Query)){
    //                     $response = "Wait for Verification Email..!!";
    //                     echo json_encode($response);
    //                 }
    //                 else{
    //                     $response= "Oops an error occured try again later..!!";
    //                     echo json_encode($response);
    //                 }
    //             }
    //         }
    //     }
    //     else{
    //         $CheckSQL = "select * from wp_users where user_email='$email' and user_status=0";
    //         $check = mysqli_fetch_array(mysqli_query($con,$CheckSQL));
    //         if(isset($check)){
    //             $response = "Email already exists.";
    //             echo json_encode($response);
    //         }
    //         else{
    //             $Sql_Query = "INSERT INTO `wp_users`(`user_login`,`user_pass`,`user_nicename`,`user_email`,`user_url`,`display_name`,`user_status`) VALUES
    //                         ('$username','$password','$fname','$email','$contact_no','$fullname',0)";
    //             if($con->query($Sql_Query)){
    //                 $response = "Wait for Verification Email..!!";
    //                 echo json_encode($response);
    //             }
    //             else{
    //                 $response= "Oops an error occured try again later..!!";
    //                 echo json_encode($response);
    //             }
    //         }
    //     }
    //     mysqli_close($con);
    // }
    
    // if(isset($_REQUEST["wishlist"])){
    //     $off=$_GET["offset"];
    //     $email=$_GET["email"];
    //     $count=$_GET["count"];
    //     $x=0;
    //     $y=$off*10;
    //     $z=$y+9;
    //     $o=0;
    //     $event=$_GET["event"];
    //     $post_id=$_GET["post_id"];
    //     $ID;
    //     $stts=$_GET['stts'];
    //     if($stts=='1')
    //     {
    //         $stts=1;
    //     }
    //     else
    //     {
    //         $stts=0;
    //     }
    //     if($event=="insert"){
    //         $Sql_Query = "select ID from wp_users where `user_email`='$email' and user_status=$stts";
    //         $result = mysqli_query($con,$Sql_Query);
    //         if (mysqli_num_rows($result) > 0) {
    //             while ($row = mysqli_fetch_array($result)) {
    //                 $ID=$row[0];
    //                 $response["results"]=array();
    //                 $sql= "INSERT INTO `wp_tinvwl_items`(`product_id`, `author`, `quantity`) VALUES ('$post_id','$row[0]','$count')";
    //                 if(mysqli_query($con,$sql)){
    //                     $response = "Article successfully added to the wishlist.";
    //                     echo json_encode($response);
    //                 }
    //                 else{
    //                     $response= "Oops! An error occurred.";
    //                     echo json_encode($response);
    //                 }
    //             }
    //         }
    //         mysqli_close($con);
    //     }
    //     if($event=="delete"){
    //         $Sql_Query = "select ID from wp_users where `user_email`='$email' and user_status=$stts";
    //     $result = mysqli_query($con,$Sql_Query);
    //     if (mysqli_num_rows($result) > 0) {
    //         while ($row = mysqli_fetch_array($result)) {
    //             $ID=$row[0];
    //         }
    //     }
    //         $response["results"]=array();
    //         $sql= "DELETE FROM `wp_tinvwl_items` WHERE `product_id`='$post_id' and `author`='$ID'";
    //         if(mysqli_query($con,$sql)){
    //             $response = "Article successfully removed from wishlist";
    //             echo json_encode($response);
    //         }
    //         else{
    //             $response= "Oops! An error occurred.";
    //             echo json_encode($response);
    //         }
    //         mysqli_close($con);
    //     }
    //     if($event=="selectedItems"){
    //         $Sql_Query = "select ID from wp_users where `user_email`='$email' and user_status=$stts";
    //     $result = mysqli_query($con,$Sql_Query);
    //     if (mysqli_num_rows($result) > 0) {
    //         while ($row = mysqli_fetch_array($result)) {
    //             $ID=$row[0];
    //         }
    //     }
    //         $response["results"]=array();
    //         $product=array();
    //         $sql= "select product_id from wp_tinvwl_items where author='$ID'";
    //         $result = mysqli_query($con,$sql);
    //         if (mysqli_num_rows($result) > 0) {
    //             while ($row = mysqli_fetch_array($result)) {
    //                 $product = $row[0];
    //                 array_push($response["results"], $product);
    //             }
    //             echo json_encode($response);
    //         }
    //         else
    //         {
    //             array_push($response["results"], $product);
    //             echo json_encode($response);
    //         }
    //         mysqli_close($con);
    //     }
    // }
    
    // if(isset($_REQUEST["cart"])){
    //     $off=$_GET["offset"];
    //     $email=$_GET["email"];
    //     $count=$_GET["count"];
    //     $x=0;
    //     $y=$off*10;
    //     $z=$y+9;
    //     $o=0;
    //     $event=$_GET["event"];
    //     $post_id=$_GET["post_id"];
    //     if($event=="insert"){
    //         $response["results"]=array();
    //         $sql= "INSERT INTO `cart`(`post_id`,`email`,`count`) VALUES ('$post_id','$email','$count')";
    //         if(mysqli_query($con,$sql)){
    //             $response = "Item successfully added to cart.";
    //             echo json_encode($response);
    //         }
    //         else{
    //             $response= "Oops! An error occurred.";
    //             echo json_encode($response);
    //         }
    //         mysqli_close($con);
    //     }
    //     if($event=="delete"){
    //         $response["results"]=array();
    //         $sql= "DELETE FROM `cart` WHERE `post_id`='$post_id' and `email`='$email'";
    //         if(mysqli_query($con,$sql)){
    //             $response = "Item successfully removed from cart.";
    //             echo json_encode($response);
    //         }
    //         else{
    //             $response= "Oops! An error occurred.";
    //             echo json_encode($response);
    //         }
    //         mysqli_close($con);
    //     }
    //     if($event=="selectedItems"){
    //         $response["results"]=array();
    //         $product=array();
    //         $sql= "select post_id from `cart` where email='$email' ";
    //         $result = mysqli_query($con,$sql);
    //         if (mysqli_num_rows($result) > 0) {
    //             while ($row = mysqli_fetch_array($result)) {
    //                 $product = $row[0];
    //                 array_push($response["results"], $product);
    //             }
    //             echo json_encode($response);
    //         }
    //         else
    //         {
    //             array_push($response["results"], $product);
    //             echo json_encode($response);
    //         }
    //         mysqli_close($con);
    //     }
    // }
    
    // if(isset($_REQUEST["wishlistFetch"])){
	   // $off=$_GET["offset"];
	   // $email=$_GET["email"];
	   // $x=0;
    //     $y=$off*10;
    //     $z=$y+9;
    //     $o=0;
    //     $response["results"] = array();
    //     $ID;
    //     $stts=$_GET['stts'];
    //     if($stts=='1')
    //     {
    //         $stts=1;
    //     }
    //     else
    //     {
    //         $stts=0;
    //     }
    //     $Sql_Query = "select ID from wp_users where `user_email`='$email' and user_status=$stts";
    //     $result = mysqli_query($con,$Sql_Query);
    //     if (mysqli_num_rows($result) > 0) {
    //         while ($row = mysqli_fetch_array($result)) {
    //             $ID=$row[0];
    //         }
    //     }
    //     $sql= "select `product_id`,`quantity` from `wp_tinvwl_items` where `author`='$ID'";
    //     $result = mysqli_query($con,$sql);
    //     if (mysqli_num_rows($result) > 0) {
    //         while ($row = mysqli_fetch_array($result)) {
				// $product = array();
    //             $sqll = "select meta_key,meta_value from `wp_postmeta`  WHERE `post_id`='$row[0]' ";
    //             $resultl = mysqli_query($con,$sqll);
    //             if (mysqli_num_rows($resultl) > 0) {
			 //       while ($rowl = mysqli_fetch_array($resultl)) {
    //                     if($rowl[0]=='_menu_item_type'||$rowl[0]=='_regular_price'||$rowl[0]=='total_sales'||$rowl[0]=='_tax_status'
    //                               ||$rowl[0]=='_stock_status'||$rowl[0]=='_wc_average_rating'||$rowl[0]=='_price'||$rowl[0]=='_sale_price'||$rowl[0]=='_sku'
    //                               ||$rowl[0]=='_stock'  ){
				//             $product["post_id"] = $row[0];
				//             $product["count"] = $row[1];
				//             $product[$rowl[0]] = $rowl[1];
				//             $sqlt = "select * FROM `wp_posts`  WHERE `ID`='$row[0]'";
				//             $resultt = mysqli_query($con,$sqlt);
				//             if (mysqli_num_rows($resultt) > 0) {
				// 	            while ($rowt = mysqli_fetch_array($resultt)) {
				// 		            $productssss=strip_tags($rowt[5]);
				// 		            $product["post_title"] = utf8_encode(preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $productssss));
				// 		            $productss=strip_tags($rowt[4]);
				// 		            $product["long_desc"] = utf8_encode(preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $productss));
				// 		            $productsss=strip_tags($rowt[6]);
				// 		            $product["short_desc"] = utf8_encode(preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $productsss));
    //                                 $sqli = "select `guid` FROM `wp_posts`  WHERE post_parent='$row[0]'";
    //                                 $resulti = mysqli_query($con,$sqli);
    //                                 if (mysqli_num_rows($resulti) > 0) {
				// 			            while ($rowi = mysqli_fetch_array($resulti)) {
				// 				            $product["img_url"]=$rowi[0];
    //                                         $sqld = "select `term_taxonomy_id` FROM `wp_term_relationships` WHERE object_id='$row[0]'";
    //                                         $resultd = mysqli_query($con,$sqld);
    //                                         if (mysqli_num_rows($resultd) > 0) {
    //                                             while ($rowd = mysqli_fetch_array($resultd)) {
    //                                                 $sqlcc = "select `term_id` FROM `wp_term_taxonomy`  WHERE term_taxonomy_id='$rowd[0]'";
    //                                                 $resultcc = mysqli_query($con,$sqlcc);
    //                                                 if (mysqli_num_rows($resultcc) > 0) {
    //                                                     while ($rowcc = mysqli_fetch_array($resultcc)) {
    //                                                         $sqlccc = "select `name`,`slug` FROM `wp_terms` WHERE term_id='$rowcc[0]'";
    //                                                         $resultccc = mysqli_query($con,$sqlccc);
    //                                                         if (mysqli_num_rows($resultccc) > 0) {
    //                                                             while ($rowccc = mysqli_fetch_array($resultccc)) {
    //                                                                 if($o==0){
    //                                                                     $product["Main"] = $rowccc[1];
    //                                                                 }
    //                                                                 else if($o==1){
    //                                                                     $product["category"] = $rowccc[1];
    //                                                                 }
    //                                                                 else if($o==2){
    //                                                                     $product["sub_category"] = $rowccc[1];
    //                                                                 }
    //                                                                 else if($o==3){
    //                                                                     $product["sub_to_sub_category"] = $rowccc[1];
    //                                                                 }
    //                                                                 $o++;
    //                                                             }
    //                                                         }
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }
				// 			            }
				// 		            }
				// 	            }
				//             }
				          
			 //           }
		  //          }
    //           }
    //             if($x>=$y && $x<=$z){
    //               if($product["img_url"]!="" ){
		  //              array_push($response["results"], $product);
    //                     $o=0;
    //               }
		  //      }
    //             if($product["img_url"]!=""){
		  //          $x++;$o=0;
    //             }
    //             else if($product["img_url"]=="" ){
    //                 if($x>0 ){
    //                   if($x<9){
    //                       $x--;$o=0;
    //                   }
    //                 }
    //                 else{
    //                     $x=0;$o=0;
    //                 }
    //             }
    //         }
    //          echo json_encode($response);
    //     }
    //     else {
	   //     $response["success"] = 0;
    //         $response["message"] = "Data not found.";
    //         echo json_encode($response);
    //     }
    //   mysqli_close($con);
    // }
}
catch(PDOException $e)
{
}
?>
