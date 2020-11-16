$(function(){
  $('#fileinput').change(function(e){
    //ファイルオブジェクトを取得する
    var file = e.target.files[0];
    var reader = new FileReader();
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
    //アップロードした画像を設定する
    reader.onload = (function(file){
      return function(e){
        if($("#noselected").length){
            var htmlstring = "<img src='' id='profileimage'>";
            $(".profilehead").prepend(htmlstring);
            
            $("#profileimage").attr("src", e.target.result);
            $("#profileimage").attr("title", file.name);
            $("#noselected").remove();    
        }else{
            $("#profileimage").attr("src", e.target.result);
            $("#profileimage").attr("title", file.name);
        } 
      };
    })(file);
    reader.readAsDataURL(file);
      
  });
});