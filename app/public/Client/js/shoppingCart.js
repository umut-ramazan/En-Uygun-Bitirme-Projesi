$(document).ready(function (){
    $(".addToCartBtn").click(function (){
        var url = '/client/shopping/process';
        var data = {
            p: 'addToCart',
            productId: $(this).attr("productId"),
        };
        $.post(url,data,function (response){
            $(".cart-count").text(response);
        })
    })




})


