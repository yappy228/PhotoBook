$(function(){
    var flg;
    $('.search_submit').on('click',function(){
        var val = $('input[name="search"]').val();
        if(!val){
            alert("検索したい人の名前を入力してください");
            return;
        }
        if($(this).attr('class').match('selected')){
            $(this).removeClass('selected');
            $('.SearchResult').slideUp('fast');
            
            $(this).addClass('selected');
            $('.SearchResult').slideDown('fast');
        }else{
            $(this).addClass('selected');
            $('.SearchResult').slideDown('fast');
        }
    });
    
    $('.search_submit ,.SearchResult').hover(function(){
        flg = true;
    }, function(){
        flg = false;
    })
    
    $(document).click(function() {
        if(flg == false){
            if($('.search_submit').attr('class').match('selected')){
                $('.search_submit').removeClass('selected');
                $('.SearchResult').slideUp('fast');
            }
        }
    });

    
});