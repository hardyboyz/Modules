

    <input type="hidden" name="num" id="num" value="<?php echo $_REQUEST['count']; ?>" />
    <div style="width: 500px;">
    <textarea cols="2" name="description" id="description"></textarea>

<!--    <script type="text/javascript">
        //<![CDATA[

                // Replace the <textarea id="editor"> with an CKEditor
                // instance, using default configurations.
                CKEDITOR.replace( 'description',
                        {
                                enterMode : CKEDITOR.ENTER_BR,
                                toolbar :
                                [
                                        [ 'Bold', 'Italic','Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','Font','FontSize','TextColor','BGColor','Subscript','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', ],

                                ]
                        });

        //]]>
    </script>-->
    <script type="text/javascript">
        //<![CDATA[
        // Replace the <textarea id="editor"> with an CKEditor
        // instance, using default configurations.
        CKEDITOR.replace( 'description',
        {
            enterMode : CKEDITOR.ENTER_BR,	
            width:800,
            height:50				
        });
        //]]>
    </script>
    </div>
    <input type="submit" onClick="javascript: saveDesc();" />


<script type="text/javascript">
//     var i = document.getElementById("num").value;
//     var id_val = window.opener.document.getElementById('desc_'+i).value;
//     //alert(id_val);
//     if(id_val != ''){
//        document.getElementById("description").value = id_val;
//        document.getElementById("num").value = i;
//    }
    
    
    var i = document.getElementById("num").value;
    //var id_val = window.opener.document.getElementById('description'+i).value;
    var id_val = window.opener.document.getElementById('descHidden_'+i).value;

    if(id_val != ''){
        document.getElementById("description").value = id_val;
        document.getElementById("num").value = i;
    }
    
    
    function saveDesc(){
        
//        var desc = CKEDITOR.instances.description.getData();
//        //alert(desc);
//        var num = document.getElementById("num").value;
//        //alert(num);
//        //var tmp = window.opener.document.getElementById('description'+num).value = desc;
//        window.opener.document.getElementById('desc_'+num).value = desc;
        
        
        
        
         var desc = CKEDITOR.instances.description.getData();
	
                var num = document.getElementById("num").value;
	
                //var tmp = window.opener.document.getElementById('description'+num).value = desc;
                window.opener.document.getElementById('desc_'+num).value = $('<div />').html(desc).text();
                window.opener.document.getElementById('descHidden_'+num).value = desc;
        
        
        
        
        self.close();
    }
</script>