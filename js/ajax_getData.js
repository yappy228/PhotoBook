$(function(){
    $('.search_submit').on('click',function(){
        var val = $('input[name="search"]').val();
        if(!val){
            return;
        }
        $.post({
            url: '../utils/ajax_getData.php',
            data:{
                'name': val
            },
            dataType: 'json', 
        }).done(function(data){
            var htmlstring = "";
            $.each(data , function(idx,obj){
                htmlstring += '<div class="Oneperson"><input type="hidden" name="user_id" value="' + obj.id + '"><div class="Personimg">';
                console.log(obj.image);
                if(obj.image == ""){
                    htmlstring += '<i class="fas fa-user-circle fa-2x"></i></div>'
                }else{
                    htmlstring += '<img src="../upload/users/' + obj.image + '" id="profileimage"></div>';
                }
                htmlstring += '<div class="Personprofile"><div class="nickname">' + obj.nickname + '</div><div class="name">' + obj.name + '</div></div></div>';
            });
            $('.person_menu').html(htmlstring);
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        })
    })
})