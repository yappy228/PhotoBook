$(function () {
    var reader = new FileReader();

    $('#fileinput').change(function () {
        var file = $(this).prop('files')[0];
        var type = file.type;
        var size = file.size;
        var readfilename = file.name;
        var limit = 2097152; //2MB
        if (type !== 'image/jpeg') {
            alert('利用可能な画像形式はjpeg,jpgです');
            $(this).val('');
            return;
        }
        if (size > limit) {
            alert('アップロード可能なファイルのサイズは2MBまでです');
            $(this).val('');
            return;
        }
        reader.readAsDataURL(file);
        reader.onload = function () {
            $('.blank').css("display", "none");
            $('.preview').css("display", "block");
            $('.preview').append("<img src=" + reader.result + ">");
        }
    });

});
