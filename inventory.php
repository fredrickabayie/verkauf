<?php
if (isset($_GET['cmd']))
{
    $cmd = $_REQUEST['cmd'];
    
    switch ($cmd)
    {
        case 'user_login':
            user_login ( );
            break;
            
        case 'addproduct_to_inventory':
            addproduct_to_inventory ( );
            break;
            
        case 'getlist_of_products_from_inventroy':
            getlist_of_products_from_inventroy ( );
            break;
            
        case 'change_price_of_product':
            change_price_of_product ( );
            break;
            
        case 'get_product_quantity_from_inventory':
            get_product_quantity_from_inventory ( );
            break;
            
        case 'send':
            send_mesg( '+233209339957', 'verKauf' );
            break;
            
        case 'search_for_product':
            search_for_product ( );
            break;
            
        default:
            echo "usage: [GPA [studentID]] [Major [studentID]]";
            break;
    }
}


/**
*Function to check to user's login
*
*/
function user_login ( ) {
    if(isset($_REQUEST['username'])&&isset($_REQUEST['password'])) {
        include_once 'queries.php';
        
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        
        $user_login = new Queries();
        
        $user_login->user_login_query($username,$password);
        
        if(!$row = $user_login->fetch()) {
            echo '{"result":0, "message":"Failed to login"}';
        } else {
            //echo json_encode($row);
            
            session_start();
            
            $userType = $row['userType'];
            
            if($userType == 'owner') {
                echo '{"result":1, "username":"'.$row['userType'].'"}';
                
                $_SESSION['userType'] = $userType;
                exit();
                
            } else if ($userType == 'teller') {
                echo '{"result":2, "username":"'.$row['userType'].'"}';
                
                $_SESSION['userType'] = $userType;
                exit();
            }
        }
    }
}



/**
 *Function to add a new product to the database
 */
function addproduct_to_inventory ( ) {
    
    if (isset($_GET['productName'])&&isset($_GET['productQuantity'])&&isset($_GET['productPrice'])&&isset($_GET['productBarcode']))
    {
        include_once 'queries.php';

        $productName = $_REQUEST['productName'];
        $productQuantity = $_REQUEST['productQuantity'];
        $productPrice = $_REQUEST['productPrice'];
        $productBarcode = $_REQUEST['productBarcode'];

        $addproduct_to_inventory = new Queries();

        if ($addproduct_to_inventory->addproduct_to_inventory_query($productName, $productQuantity, $productPrice, $productBarcode))
        {
            echo '{"result":1, "status": "Product added to inventory"}';
        }
        else
        {
            echo '{"result":0, "status": "Failed to add product to inventory"}';
        }
    }
}


/**
*Function to get the list of products in the inventory
*
*/
function getlist_of_products_from_inventroy ( ) {
    include_once 'queries.php';
    
    $getlist_of_products_from_inventroy = new Queries();
    
    if ($getlist_of_products_from_inventroy->getlist_of_products_from_inventroy_query( ))
    {
        $row = $getlist_of_products_from_inventroy->fetch();
        echo '{"result": 1, "product": [';
        while ($row)
        {
            echo '{"productId": "'.$row["productId"].'", "productName": "'.$row["productName"].'", "productQuantity": "'.$row["productQuantity"].'", "productPrice": "'.$row["productPrice"].'", "productBarcode": "'.$row["productBarcode"].'"}';
            
            if ($row = $getlist_of_products_from_inventroy->fetch())
            {
                echo ',';
            }
        }
        echo ']}';
    }
    else {
        echo '{"result": 0, "status": "Could not display products in the inventory"}';
    }
}



/**
*Function to update the price of a product in the inventory
*/
function change_price_of_product ( ) {
    include_once 'queries.php';
    
    $productId = $_REQUEST['productId'];
    $productPrice = $_REQUEST['productPrice'];
    
    $change_price_of_product = new Queries();
    
    if ($change_price_of_product->change_price_of_product_query($productId,$productPrice))
    {
        echo '{"result":1, "status": "Product price updated"}';
    }
    else {
        echo '{"result":0, "status": "Price not updated"}';
    }
}


/**
*Function to get the quantity of a product from the inventory
*through sms
*/
function get_product_quantity_from_inventory ( ) {
    if (isset($_REQUEST['message'])) {
        
        include_once 'queries.php';
        
        $content = $_REQUEST['message'];
        $message = explode(" ", $content);
        $productName = $message[0];
        
        $get_product_quantity_from_inventory = new Queries();
        
        if($get_product_quantity_from_inventory->get_product_quantity_from_inventory_query($productName)) {
            
            $row = $get_product_from_inventory->fetch();
            
            $msg = $studentID." is left with ".$row['productName'];
            
            send_mesg('+233209339957', $msg);
            
            echo $msg;
        }
        else {
            echo "Please enter the product name well!";
        }
    }
}


/**
*Function to search for product by barcode
*
*/
function search_for_product ( ) {
    if (isset($_REQUEST['productBarcode'])) {
        include_once 'queries.php';
        
        $productBarcode = $_REQUEST['productBarcode'];
        
        $search_for_product = new Queries();
        
        $search_for_product->search_for_product_query($productBarcode);
        
        if ($row = $search_for_product->fetch()) {
            echo '{"result":1, "productId":"'.$row['productId'].'", "productName":"'.$row['productName'].'", "productQuantity":"'.$row['productQuantity'].'", "productPrice":"'.$row['productPrice'].'", "productBarcode":"'.$row['productBarcode'].'"}';   
        }
        else {
            echo '{"result":0,"message":"No such product in inventory"}';
        }
    }
}






    
/**
*Function to add a new student to the database
*
*/
function register_student ( )
{
    if (isset($_REQUEST['message']))
    {
        include_once 'act.php';

        $content = $_REQUEST['message'];
        $message = explode(" ", $content);

        $studentID = $message[0];
        $studentName = $message[1];
        $GPA = $message[2];
        $Major = $message[3];
        $phoneNo = $message[4];

        $register = new Act();
        
        $message = "Registered";
                
        if ($register->register_student( $studentID, $studentName, $GPA, $Major, $phoneNo ))
        {
            send_mesg($phoneNo, $message);
            echo "Registered";
        }
        else
        {
            echo "Please enter your registration details";
        }
    }
}




/**
*Function to allow student to check GPA
*
*/
function check_gpa ( )
{
    if (isset($_REQUEST['message']))
    {
        include_once 'act.php';
        
        $content = $_REQUEST['message'];
        $message = explode(" ", $content);
        $studentID = $message[0];
        
        $select = new Act();
        
        if ( $select->check_gpa( $studentID ))
        {
            $row = $select->fetch();
            echo "GPA for ".$studentID." is: ".$row['GPA'];
        }
        else
        {
            echo "Failed to check gpa";
        }
    }
    else
    {
        echo "Please enter your studentID";
    }
}




/**
*Function to allow student to check Major
*
*/
function check_major ( )
{
    if (isset($_REQUEST['message']))
    {
        include_once 'act.php';
        
        $content = $_REQUEST['message'];
        $message = explode(" ", $content);
        $studentID = $message[0];
        
        $select = new Act();
        
        if ( $select->check_major( $studentID ))
        {
            $row = $select->fetch();
            echo "Major for ".$studentID." is: ".$row['Major'];
        }
        else
        {
            echo "Failed to check major";
        }
    }
    else
    {
        echo "Please enter your studentID";
    }
}



/**
*Function to give the user a one time password
*
*/
function get_otp ( )
{
    if (isset($_REQUEST['message']))
    {
        include_once 'act.php';
        session_start();
         session_unset();
        
        $studentID = $_REQUEST['message'];
        
        $_SESSION['passcode'] = random();
        
        $phoneNo = new Act();
        
        if ($phoneNo->get_details ( $studentID ))
        {
            $row = $phoneNo->fetch();
            
            send_mesg ($row['phoneNo'],"Your passcode to login is:".$_SESSION['passcode']);
            echo $row['phoneNo']." ".$_SESSION['passcode'];
        }
    }
}



/**
*Function to display the details of a student
*
*/
function display_details ( $studentID )
{
    include_once 'act.php';
    
    $detail = new Act();
    
    if ($detail->get_details ( $studentID ))
    {
        $row = $detail->fetch();
        
    }
}



/**
*Function to send student message when he registers
*
*/
function send_mesg( $phoneNo, $message )
{
    include_once 'Smsgh/Api.php';
    
//    $auth = new BasicAuth("jokyhrvs","volkzmqn");
    $auth = new BasicAuth("yralkzfn","znbzlsho");
    
    $apiHost = new ApiHost($auth);
    $messageApi = new MessagingApi($apiHost);
    
    try
    {
        $messageResponse = $messageApi->sendQuickMessage("verKauf", $phoneNo, $message);
        
        if($messageResponse instanceof MessageResponse)
        {
            echo "msg1:".$messageResponse->getStatus()."</br></br>";
        }
        elseif ($messageResponse instanceof HttpResponse)
        {
            echo "\nServer Response Status: ".$messageResponse->getStatus()."</br></br>";
        }
        
        echo "</br>success done";
    }
    catch (Exception $ex)
    {
        echo 'Exception', $ex->getMessage(), "\n";
    }
}


