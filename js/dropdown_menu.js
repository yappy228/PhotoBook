$(function(){
    var flg;
    $('.bottom_menu').on('click',function(){
        if($(this).attr('class').match('selected')){
            $(this).removeClass('selected');
            $('.pulldown').slideUp('fast');
            
            $(this).addClass('selected');
            $('.pulldown').slideDown('fast');
        }else{
            $(this).addClass('selected');
            $('.pulldown').slideDown('fast');
        }
    });
    
    $('.bottom_menu ,.pulldown').hover(function(){
        flg = true;
    }, function(){
        flg = false;
    })
    
    $(document).click(function() {
        if(flg == false){
            if($('.bottom_menu').attr('class').match('selected')){
                $('.bottom_menu').removeClass('selected');
                $('.pulldown').slideUp('fast');
            }
        }
    });
});