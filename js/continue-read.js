$(function(){
    $(".Tudukiwoyomu").on("click", function(){
        var id = $(this).parent().attr("post_id");
        $(this).css('display','none');
        $(`.Comment .row2 .Hidetext[post_id="${id}"]`).slideToggle(100);
    });
    $(".Tudukiwoyomu_Ano1").on("click", function(){
        var id = $(this).parent().attr("post_id");
        $(this).css('display','none');
        $(`.AnoComment1 .row2 .Hidetext[post_id="${id}"]`).slideToggle(100);
    });
    $(".Tudukiwoyomu_Ano2").on("click", function(){
        var id = $(this).parent().attr("post_id");
        $(this).css('display','none');
        $(`.AnoComment2 .row2 .Hidetext[post_id="${id}"]`).slideToggle(100);
    });
    
});