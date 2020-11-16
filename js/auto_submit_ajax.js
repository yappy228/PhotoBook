$(function(){
    $(document).on('click', '.imagelist div img', function () {
        var val = $('input[name="post_id"]').val();
        if(!val){
            return;
        }
        $.post({
            url: '../utils/ajax_view.php',
            data:{
                'name': val
            },
            dataType: 'json', 
        }).done(function(data){
            
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        })
    })
});