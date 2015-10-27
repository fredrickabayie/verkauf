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
    *Function to change the price of a product
    *
    */
    function change_price_of_product_query ( $productId, $productPrice ) 
    {
        $editPrice_query = "UPDATE `pointofsale_midsem_verkauf_inventory` SET productPrice='$productPrice' WHERE productId='$productId'";
        
        return $this->query($editPrice_query);
    }
    
    
}

//$okay = new Queries();
//$okay->addproduct_to_inventory_query ( "productName", "5", "99.6", "45452452646" );
