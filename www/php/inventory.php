<?php
if (isset($_GET['cmd']))
{
    $cmd = $_REQUEST['cmd'];
    
    switch ($cmd)
    {
        case 'addproduct_to_inventory':
            addproduct_to_inventory ( );
            break;
            
        case 'gpa':
            check_gpa ( );
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
    
    if (isset($_REQUEST['productName'])&&isset($_REQUEST['productQuantity'])&&isset($_REQUEST['productPrice'])&&isset($_REQUEST['productBarcode']))
    {
        include_once dirname(__FILE__) . '/queries.php';

        $productName = $_REQUEST['productName'];
        $productQuantity = $_REQUEST['productQuantity'];
        $productPrice = $_REQUEST['productPrice'];
        $productBarcode = $_REQUEST['productBarcode'];

        $addproduct_to_inventory = new Queries();

        if ($addproduct_to_inventory->addproduct_to_inventory_query($productName, $productQuantity, $productPrice, $productBarcode))
        {
            echo "added";
        }
        else
        {
            echo "Not added";
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










