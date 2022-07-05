
<script>
    $(document).ready(function() {
        var mode = "add";

        $("[e-type='delete']").click(function(e){
            e.preventDefault();
            var stt =  confirm('Bạn chắc chưa?');
            if(stt){
                $.post($(this).attr("href"),function(data){
                    console.log(data);
                    if(data==1){
                        window.location.reload();
                    }
                    else {
                        alert("Lỗi!")
                    }
                })
            }
        })

        $("[e-type='edit']").click(function() {
            var id = $(this).attr('data-id');
            if ($("#_table").is('hidden')) {
                $("#_table").show(400);
            }
            $("#btn-ok").text("Sửa");
            $("#btn-reset").show(100);
            $("#name").val($("[r-id=" + id + "][r-name=Name]").attr('r-value'));
            $("#value").val($("[r-id=" + id + "][r-name=Value]").attr('r-value'));
            $("#selector").val($("[r-id=" + id + "][r-name=Selector]").attr('r-value'));
            $("#id").val(id);
            mode = "edit";
        })

        $("#post_form").submit(function(e) {
            $(this).attr('method','post');
            $(this).attr('action','xuly?action='+mode);
                // e.preventDefault();
                // console.log($(this).serialize());
                // $.ajax({
                //         url: 'xuly?action='+mode,
                //         type: "POST",
                //         data: $(this).serialize(),
                //         success: function(r) {
                //             console.log(r);
                //             if(r==1){
                //                 window.location.reload();
                //             }
                //             else {
                //                 alert("Lỗi!");
                //             }
                //         },
                //         error: function(e) {
                //             console.log("ERROR ", e);
                //         }
                // });
        })

    $("#btn-reset").click(function() {
        $("#id").val(null);
        $(this).hide(100);
        mode = "add";
    })
    })

    function getFileData(myFile) {
        var file = myFile.files[0];
        var filename = file.name;
        if (isFileImage(file)) {
            document.getElementById("anh").value = filename
        } else {
            alert("Chỉ chấp nhận file ảnh!");
        }
    }

    function isFileImage(file) {
        return file && file['type'].split('/')[0] === 'image';
    }
</script>