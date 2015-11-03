/*global console, $, alert, cordova, JQuery, timer, Connection*/
function sendRequest(u) {
    "use strict";
    var obj, result;
    obj = $.ajax({
        url: u,
        async: false
    });
    result = $.parseJSON(obj.responseText);
    return result;
}


////Function to test for network connection
//function checkConnection() {
//    "use strict";
//    var networkState, states;
//    
//    networkState = navigator.connection.type;
//
//    states = {};
//    
//    states[Connection.UNKNOWN]  = 'Unknown connection';
//    states[Connection.ETHERNET] = 'Ethernet connection';
//    states[Connection.WIFI]     = 'WiFi connection';
//    states[Connection.CELL_2G]  = 'Cell 2G connection';
//    states[Connection.CELL_3G]  = 'Cell 3G connection';
//    states[Connection.CELL_4G]  = 'Cell 4G connection';
//    states[Connection.CELL]     = 'Cell generic connection';
//    states[Connection.NONE]     = 'No network connection';
//
//    alert('Connection type: ' + states[networkState]);
//}
//
//checkConnection();
//
//function onOffline() {
//    // Handle the offline event
//    "use strict";
//    alert('Device is offline');
//}
//
//document.addEventListener("offline", onOffline, false);
//
//
//function onOnline() {
//    // Handle the offline event
//    "use strict";
//    alert('Device is online');
//}
//
//document.addEventListener("online", onOnline, false);


$(function () {
    "use strict";
    $("#login_btn").click(function () {
        var url, username, password, messenger;

        username = encodeURI(document.getElementById("username").value);
        password = encodeURI(document.getElementById("password").value);

        url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=user_login&username=" + username + "&password=" + password;

        messenger = sendRequest(url);

        if (messenger.result === 1) {
            window.location.replace("home_page.html");

        } else if (messenger.result === 2) {
            window.location.replace("sale_page.html");

            return false;
        } else {
            $(".status").text(messenger.message);
        }

    });
});


//function to add product to the inventory
$(function () {
    "use strict";
    $("#addproduct_save_btn").click(function () {

        var productName, productQuantity, productPrice, productBarcode, messenger, url;

        productName = encodeURI(document.getElementById("productName").value);
        productQuantity = encodeURI(document.getElementById("productQuantity").value);
        productPrice = encodeURI(document.getElementById("productPrice").value);
        productBarcode = encodeURI(document.getElementById("productBarcode").value);

        url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=addproduct_to_inventory&productName=" + productName + "&productQuantity=" + productQuantity + "&productPrice=" + productPrice + "&productBarcode=" + productBarcode;

        messenger = sendRequest(url);

        if (messenger.result === 1) {
            $(".message").text(messenger.status);
        } else {
            $(".message").text(messenger.status);
            return false;
        }
    });
});



$(function () {
    "use strict";
    $(function () {
        var url, messenger, index, list = "";
        url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=getlist_of_products_from_inventroy";

        messenger = sendRequest(url);

        if (messenger.result === 1) {
            for (index in messenger.product) {
                if (messenger.product.hasOwnProperty(index)) {
                    list += "<li style='' class='collection-item avatar list_item' onClick='edit_product_price(this.id)' id=" + messenger.product[index].productId + "," + messenger.product[index].productName + "," + messenger.product[index].productPrice + ">";
                    list += "<i class='fa fa-code circle'></i>";
                    list += "<span class='title'>" + messenger.product[index].productName + "</span>";
                    list += "<p>Qty " + messenger.product[index].productQuantity + "<br>";
                    list += "GHC " + messenger.product[index].productPrice + "</p>";
                    list += "<a href='#!' class='secondary-content'><i class='fa fa-check'></i></a>";
                    list += "</li>";
                }
            }
        }
        $(".show_inventory").html(list);
    });
});



function edit_product_price(productInfo) {
    "use strict";
    console.log(productInfo);
    var detail, str;
    str = productInfo;
    detail = str.split(",");

    $('#edit_productId').val(detail[0]);
    $('.modal_product_name').text(detail[1]);
    $('#edit_product_price').val(detail[2]);
    $('#modal1').openModal();
}


$(function () {
    "use strict";
    $('#edit_productId').hide();
    $("#update_product_price_btn").click(function () {
        var productPrice, productId, messenger, url;

        productId = encodeURI(document.getElementById("edit_productId").value);
        productPrice = encodeURI(document.getElementById("edit_product_price").value);

        url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=change_price_of_product&productId=" + productId + "&productPrice=" + productPrice;

        messenger = sendRequest(url);

        if (messenger.result === 1) {
            $(".message").text(messenger.status);
        } else {
            $(".message").text(messenger.status);
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
            },
            function (error) {
                alert("Scanning failed: " + error);
            }
        );
    });
});


//Function to handle the barcode scanner button
$(function () {
    "use strict";
    $("#transaction_productId").hide();
    $("#transaction_productBarcode_btn").click(function () {
        cordova.plugins.barcodeScanner.scan(
            function (result) {

                var productBarcode, url, messenger;
                $("#transaction_productBarcode").val(result.text);
                productBarcode = result.text;

                //                url = "inventory.php?cmd=change_price_of_product&productId=" + productBarcode;

                url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=search_for_product&productBarcode=" + productBarcode;

                messenger = sendRequest(url);

                if (messenger.result === 1) {
                    $("#transaction_productId").val(messenger.productId);
                    $("#transaction_productName").val(messenger.productName);
                    $("#transaction_productQuantity").val(messenger.productQuantity);
                    $("#transaction_productPrice").val(messenger.productPrice);
                } else {
                    $(".message").text(messenger.message);
                }

            },
            function (error) {
                alert("Scanning failed: " + error);
            }
        );
    });
});


/**
 *Function to add a new transaction
 *
 */
$(function () {
    "use strict";
    $("#transaction_save_btn").click(function () {
        var customerNumber, productId, total, url, messenger;

        customerNumber = encodeURI(document.getElementById("transaction_customerNumber").value);
        productId = encodeURI(document.getElementById("transaction_productId").value);
        total = encodeURI(document.getElementById("transaction_productPrice").value);

        url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=make_transaction&customerNumber=" + customerNumber + "&productId=" + productId + "&total=" + total;

        messenger = sendRequest(url);

        if (messenger.result === 1) {
            $(".message").text(messenger.status);
        } else {
            $(".message").text(messenger.status);
        }
    });
});









//Function to handle the barcode scanner button
$(function () {
    "use strict";
    //    $("#transaction_productId").hide();
    $("#transact_barcode_btn").click(function () {
        //        cordova.plugins.barcodeScanner.scan(
        //            function (result) {

        var productBarcode, url, messenger, tr;
        //                $("#transact_productBarcode").val(result.text);
        //                productBarcode = result.text;

        productBarcode = encodeURI(document.getElementById("transact_productBarcode").value);
        url = "inventory.php?cmd=search_for_product&productBarcode=" + productBarcode;

        //                url = "http://cs.ashesi.edu.gh/~csashesi/class2016/fredrick-abayie/mobileweb/pointofsale_midsem_verkauf/php/inventory.php?cmd=search_for_product&productBarcode=" + productBarcode;

        messenger = sendRequest(url);
        tr = "";
        if (messenger.result === 1) {
            tr += "<tr class='transact_id' id=" + messenger.productId + ">";
            tr += "<td>" + messenger.productName + "</td>";
            tr += "<td>" + messenger.productPrice + "</td>";
            tr += "<td><input type='number' value='1'/></td>";
            tr += "</tr>";

            $(".transact_list").prepend(tr);
            var test = encodeURI(document.getElementById("total_price").textContent);
            var total = parseFloat(test) + parseFloat(messenger.productPrice);
            $(".total_price").text(total);
            console.log(total);
            //                    $("#transaction_productId").val(messenger.productId);
            //                    $("#transaction_productName").val(messenger.productName);
            //                    $("#transaction_productQuantity").val(messenger.productQuantity);
            //                    $("#transaction_productPrice").val(messenger.productPrice);
            console.log($(".transact_id").attr("id"));
        } else {
            $(".message").text(messenger.message);
        }


        //            },
        //            function (error) {
        //                alert("Scanning failed: " + error);
        //            }
        //        );
    });
});

//$(function() {
//    
//});

//<tr>
//<td>Alvin</td>
//<td>$0.87</td>
//<td>-</td>
//</tr>