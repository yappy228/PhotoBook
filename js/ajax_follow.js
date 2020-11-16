$(function(){
    $('.follow').on('click',function(){
        var follow_user_id =  $(this).attr("follow_user_id");
        var followed_user_id = $(this).attr("followed_user_id");
        var pat = $(this).attr("pat");
        
        $.post({
            url: '../utils/ajax_follow.php',
            data:{
                'follow_user_id': follow_user_id,
                'followed_user_id': followed_user_id,
                'pat': pat
            },
            dataType: 'json', 
        }).done(function(data){
            if(pat === 'follow'){
                $('.follow').attr("pat", "unfollow");
                $('.follow').text("フォロー解除");
                alert("フォローしました");
            }else{
                $('.follow').attr("pat", "follow");
                $('.follow').text("フォローする");
                alert("フォロー解除しました");
            }
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        })
    })
})