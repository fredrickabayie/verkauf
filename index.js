/*global console, $, alert, cordova, JQuery*/

function sendRequest(u) {
    "use strict";
    var obj, result;
    obj = $.ajax({url: u, async: false});
    result = $.parseJSON(obj.responseText);
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
                list += "<li style='' class='collection-item avatar list_item' onClick='edit_product_price(this.id)' id=" + messenger.product[index].productId + "," + messenger.product[index].productName + "," + messenger.product[index].productPrice + ">";
                list += "<i class='fa fa-code circle'></i>";
                list += "<span class='title'>" + messenger.product[index].productName + "</span>";
                list += "<p>" + messenger.product[index].productQuantity + "<br>";
                list += messenger.product[index].productPrice + "</p>";
                list += "<a href='#!' class='secondary-content'><i class='fa fa-check'></i></a>";
                list += "</li>";
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
//    console.log(detail[0]);
//    console.log(detail[1]);
    
    $('.modal_product_name').text(detail[1]);
    $('#edit_product_price').val(detail[2]);
    $('#modal1').openModal();
}

//
//<li class="collection-item avatar">
//      <i class="material-icons circle">folder</i>
//      <span class="title">Title</span>
//      <p>First Line <br>
//         Second Line
//      </p>
//      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
//    </li>


////            Function to load the list of task
//            $ ( document ).ready ( function ( )
//            {
//               var url = "../controllers/user_controller.php?cmd=5";
//               var obj = sendRequest ( url );
//               var path = "";
//                if ( obj.result === 1 )
//                {
//                    path = $(".path").val();
//                    $("#profileimage").attr("src", path );
//                    var div = "";
//                    var timer;
//                    for ( var index in obj.tasks )
//                    {
//                        div += "<div class='showcontentdetailsinnertile showcontentdetailsinnertile2'\n\
//                                    onclick='getPreview ( "+obj.tasks [index].task_id+" )'>";
//                        div += "<input class='showcontentdetailsinnertilecheckbox showcontentdetailsinnertilecheckbox2'\n\
//                                    value="+obj.tasks [index].task_id+" name='todelete[]' type='checkbox'>";
//                        div += "<div class='showcontentdetailsinnertilename'>";
//                        div += "<span>"+obj.tasks [index].user_fname+" "+obj.tasks [index].user_sname+"</span>";
//                        div += "<div class='showcontentdetailsinnertiledelete showcontentdetailsinnertiledelete2' \n\
//                                    style='float:right; margin-right:10px'>";
//                        div += "<a class='delete' style='padding: 7px' id="+obj.tasks [index].task_id+"><i id='deleteicon' \n\
//                                    class='fa fa-trash-o'></i></a>";
//                        div += "</div>";
//                        div += "</div>";
//                        div += "<div class='showcontentdetailsinnertiletitle'>\n\
//                                    <span>"+obj.tasks [index].task_title+"</span></div>";
//                        div += "<div class='showcontentdetailsinnertiledescription'>\n\
//                                    <span>"+obj.tasks [index].task_description+"</span></div>";
//                        div += "</div>";
//                    }
//                    $ ( ".showcontentdetailsinner" ).html ( div );
////                     $ ( "#divStatus" ).text ( "Found " + obj.products.length + " results" );
//                }
//                else
//                {
////                        $ ( "#divStatus" ).text ( obj.message );
////                        $ ( "#divStatus" ).css ( "backgroundColor", "red" );
//                }
//
//                timer = setTimeout ( '(this)', 1000 );
//            });








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
















