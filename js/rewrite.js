$(function() {
    $(document).ready(function () {
        let margin = 0;
        let wsize = $(window).width();
        if(wsize >= 1000){
            $(".HomeSubBar").css("display", "block");
            margin = $(".Mainwrap").css("margin-left");
            margin = parseInt(margin.slice(0,-2));
            let left = 614 + margin;
            $(".HomeSubBar").css("left", left + "px");
            $(".Mainwrap").css("justify-content","");
        }else{
            $(".HomeSubBar").css("display", "none");
            $(".Mainwrap").css("justify-content","center");
        }
    });
    $(window).resize(function () {
        let margin = 0;
        let wsize = $(window).width();
        if(wsize >= 1000){
            $(".HomeSubBar").css("display", "block");
            margin = $(".Mainwrap").css("margin-left");
            margin = parseInt(margin.slice(0,-2));
            let left = 614 + margin;
            $(".HomeSubBar").css("left", left + "px");
            $(".Mainwrap").css("justify-content","");
        }else{
            $(".HomeSubBar").css("display", "none");
            $(".Mainwrap").css("justify-content","center");
        }
    });
});
