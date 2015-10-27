<?php
include_once 'adb.php';

class Queries extends adb {
    
    
   function __construct ( ) 
   {
       $this->connect();
   }
    
    
    
    function __destruct ( )
    {
        $this->close();
    }
    
    
    /**
    *Function to distinguish between the two users
    */
    function user_login_query ( $username, $password )
    {
        $login_query = "SELECT * FROM `pointofsale_midsem_verkauf_login` WHERE username='$username' and password='$password'";
        
        return $this->query($login_query);
    }
    
    
    /**
    *Function to query the adding of a new product to the inventory
    *
    *
    */
    function addproduct_to_inventory_query ( $productName, $productQuantity, $productPrice, $productBarcode ) 
    {
        $insert_query = "INSERT INTO `pointofsale_midsem_verkauf_inventory` ( `productName`, `productQuantity`, `productPrice`, `productBarcode` ) VALUES ( '$productName', '$productQuantity', '$productPrice', '$productBarcode' )";
        
        return $this->query($insert_query);
    }
    
    
   /** 
    *Function to get details of a student
    *
    */
    function getlist_of_products_from_inventroy_query ( )
    {
        $productList_query = "SELECT * FROM `pointofsale_midsem_verkauf_inventory`";
        
        return $this->query($productList_query);
    }
    
    
    /**
    *Function to get the quantity of a product by name
    *
    */
    function get_product_quantity_from_inventory_query ( $productName ) {
        $productQuantity_query = "SELECT productQuantity FROM `pointofsale_midsem_verkauf_inventory` WHERE productName='$productName'";
        
        return $this->query($productQuantity_query);
    }
    
    
    /**
    *Function to change the price of a product
    *
    */
    function change_price_of_product_query ( $productId, $productPrice ) 
    {
        $editPrice_query = "UPDATE `pointofsale_midsem_verkauf_inventory` SET productPrice='$productPrice' WHERE productId='$productId'";
        
        return $this->query($editPrice_query);
    }
    
    
    /**
    *Function to make a transaction
    *
    */
    function make_transaction_query ( $customerNumber, $productId, $total ) {
        $transaction_query = "INSERT INTO `pointofsale_midsem_verkauf_transaction` (`customerNumber`, `productId`, `total`) VALUES ( '$customerNumber', '$productId', '$total' )";
        
        return $this->query($transaction_query);
    }
    
    
    /**
    *Function to search for product by barcode
    *
    */
    function search_for_product_query ( $productBarcode ) {
        $search_query = "SELECT * FROM `pointofsale_midsem_verkauf_inventory` WHERE `productBarcode`='$productBarcode'";
        
        return $this->query($search_query);
    }
    
    
}

//$okay = new Queries();
//$okay->addproduct_to_inventory_query ( "productName", "5", "99.6", "45452452646" );
