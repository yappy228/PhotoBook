$(function(){
    $('.js-modal-open').on('click',function(){
        var target = $(this).data('target');
        var modal = document.getElementById(target);
        var post_id = $(this).attr("data");
        
        $.post({
            url: '../utils/ajax_view.php',
            data:{
                'post_id': post_id
            },
            dataType: 'json', 
        }).done(function(data){
            $(".modal-content").html(data);
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        })
        
        $(modal).fadeIn();
        $('body').css('overflow','hidden');
        
        return false;
    });
    
    $('.js-modal-close').on('click',function(){
        $('.js-modal').fadeOut();
        $('body').css('overflow','');
        
        return false;
    });
});