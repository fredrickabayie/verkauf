/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/*global console, $, alert, cordova*/

function sendRequest(u) {
    "use strict";
    var obj, result;
    obj = $.ajax({url: u, async: false});
    result = $.parseJSON(obj.resoponseText);
    return result;
}


//function to add product to the inventory
$(function () {
    "use strict";
    $("#addproduct_save_btn").click(function () {
        
        var productName, productQuantity, productPrice, productBarcode, messenger, url;
        
        productName = encodeURI(document.getElementById("productName").value);
        productQuantity = encodeURI(document.getElementById("productQuantity").value);
        productPrice = encodeURI(document.getElementById("productPrice").value);
        productBarcode = encodeURI(document.getElementById("productBarcode").value);
        
        url = "http://localhost/mobile_web_2015/midsem/verkauf/www/php/inventory.php?cmd=addproduct_to_inventory&productName=" + productName + "&productQuantity=" + productQuantity + "&productPrice=" + productPrice + "&productBarcode" + productBarcode;
        console.log(url);
        
        messenger = sendRequest(url);
        
        if (messenger.status === 1) {
            $("").text(messenger.status);
        } else {
            $("").text(messenger.status);
            return false;
        }
    });
});


//Function to handle the barcode scanner button
$(function () {
    "use strict";
    $("#addproduct_barcodescan_btn").click(function () {
        cordova.plugins.barcodeScanner.scan(
            function (result) {
                $("#productBarcode").val(result.text);
//                    "Format: " + result.format + "\n" +
            },
            function (error) {
                alert("Scanning failed: " + error);
            }
        );
    });
});
















