<?php
if (isset($_GET['cmd']))
{
    $cmd = $_REQUEST['cmd'];
    
    switch ($cmd)
    {
        case 'addproduct_to_inventory':
            addproduct_to_inventory ( );
            break;
            
        case 'getlist_of_products_from_inventroy':
            getlist_of_products_from_inventroy ( );
            break;
            
        case 'major':
            check_major ( );
            break;
            
        case 'passcode':
            get_otp ( );
            break;
            
        default:
            echo "usage: [GPA [studentID]] [Major [studentID]]";
            break;
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
            echo ' { "result":1, "status": "Failed to add product to inventory" } ';
        }
    }
}


/**
*Function
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
 * Function to display all tasks
 */
function display_tasks ( )
{
    include '../models/user_class.php';
    $obj = new User ( );
    session_start();
    $user_id = $_SESSION['user_id'];
       
    if ( $obj->user_display_assigned_tasks ( $user_id ) )
    {
        $row = $obj->fetch ( );
        echo '{"result":1, "tasks": [';
        while ( $row )
        {
            echo '{"task_id": "'.$row ["task_id"].'", "task_title": "'.$row ["task_title"].'", 
            "task_description": "'.$row ["task_description"].'",  "user_sname": "'.$row ["user_sname"].'",
            "user_fname": "'.$row ["user_fname"].'"}';
            
            if ($row = $obj->fetch ( ) )   {
                    echo ',';
            }
        }
            echo ']}';
    }   else
    {
        echo '{"result":0,"status": "An error occured for select product."}';
    }
}//end of display_all_tasks()



    
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
    include_once '../Smsgh/Api.php';
    
    $auth = new BasicAuth("jokyhrvs","volkzmqn");
//    $auth = new BasicAuth("yralkzfn","znbzlsho");
    
    $apiHost = new ApiHost($auth);
    $messageApi = new MessagingApi($apiHost);
    
    try
    {
        $messageResponse = $messageApi->sendQuickMessage("Important", $phoneNo, $message);
        
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



/**
*Function to generate random numbers
*
*/
function random($length = 6)
{
//    session_start();
    $chars = 'bcdfghjklmnprstvwxzaeiou0123456789';
    $result = '';

    for ($p = 0; $p < $length; $p++)
    {
        $result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
    }

    return $result;
}
//echo random();










