$(function(){
    $(document).on('click', '.Oneperson', function(){
        var val = $(this).children("input").attr("value");
        $('<form/>', {action: 'profile.php' , method: 'post'})
            .append($('<input/>', {type: 'hidden', name: 'user_id', value: val}))
            .appendTo(document.body)
            .submit();
    });
});