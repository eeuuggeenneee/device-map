getBusInfo()

function getBusInfo(){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:"get_about_us"
        },
        dataType: "JSON",
        success: function (response) {
            
            $('.business_name').html(response.Name)
            $('.logo-icon').attr('src', '../assets/images/logo/'+response.Logo)
            $('#title_icon').attr('href', '../assets/images/logo/'+response.Logo)
        }
    })
}



function categoryDD(id){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:"category_dd"
        },
        dataType: "JSON",
        success: function (response) {
            
            $('#'+id).html(response)
        }
    })
}



function supplierDD(id){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:"supplier_dd"
        },
        dataType: "JSON",
        success: function (response) {

            $('#'+id).html(response)      
        }
    })
}



function countItems(id){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:"count_items"
        },
        dataType: "JSON",
        success: function (response) {
            
            $('#'+id).html(response)
        }
    })
}



function countOnHand(id){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:'count_on_hand'
        },
        dataType: "JSON",
        success: function (response) {
            
            $('#'+id).html(response)
        }
    })
}



function countStckIn(id, date){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            datefil:date,
            action:"count_stock_in"
        },
        dataType: "JSON",
        success: function (response) {

            $('#'+id).html(response)
        }
    })
}



function customerDD(id){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            action:"customer_dd"
        },
        dataType: "JSON",
        success: function (response) {
            
            $('#'+id).html(response)
        }
    })
}



function stockOutInfo(id, id2, id3, date_fil){

    $.ajax({
        type: "POST",
        url: "exec/fetch.php",
        data: {
            datefil:date_fil,
            action:"stock_out_info"
        },
        dataType: "JSON",
        success: function (response) {
            
            $('#'+id).html(response.Sold)
            $('#'+id2).html(response.Sales)
            $('#'+id3).html(response.Count)
        }
    })
}



//Login
$('#login_form').on('submit',function(e){
    e.preventDefault()
    var uname = $('#Uname').val()
    var pass = $('#Upass').val()

    $.ajax({
        method: "POST",
        dataType: "JSON",
        url: "exec/login_auth.php",
        data:{
            action:'login',
            username:uname,
            password:pass
        },
        success: function (data) {
            var result = JSON.parse(data)

            console.log(result)
            if(result==1){
                swal({
                    title: "Login Successfully",
                    text:"Welcome User!",
                    icon: "success",
                    buttons: false,
                    dangerMode: true,
                })
                setTimeout(function(){
                     location.href='index.php'}
                     , 3000)
                
            }
            else if(result==2){
                swal({
                    title: "Something went wrong",
                    text:"Username and Password is incorrect",
                    icon: "error",
                    buttons: false,
                    dangerMode: true,
                })
            }
            else if(result==3){
                swal({
                    title: "INFORMATIONS",
                    text:"Empty fields",
                    icon: "warning",
                    buttons: false,
                    dangerMode: true,
                })
            }
        }
    });
})

//Login End


