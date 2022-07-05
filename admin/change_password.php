<?php
if (!isset($show)) {
    header('Location: index.php');
}
$id = $_SESSION['admin_id'];
$u = $App->Select("select * from admin where ID='$id'",1);

?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-key-change"></i>
        </span> Đổi mật khẩu
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <!-- <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li> -->
        </ul>
    </nav>
</div>
<form class="card-body" id="post_form">
    <p class="card-description"> Mật khẩu <code>để trống</code> nếu không đổi </p>
    <div class="form-group">
        <label>Tên đăng nhập</label>
        <input type="text" id="username" name="username" value="<?=$u['Username']?>" class="form-control form-control-lg" placeholder="Username" required>
    </div>
    <div class="form-group">
        <label>Mật khẩu</label>
        <input type="password" id="p1" name="password" class="form-control form-control-lg" placeholder="Password">
    </div>
    <div class="form-group">
        <label>Nhập lại mật khẩu</label>
        <input type="password" id="p2" class="form-control form-control-lg" placeholder="Repassword">
    </div>
    <input type="hidden" name="id" value="<?php echo $_SESSION['admin_id'];?>">
    <button type="submit" class="btn btn-inverse-success btn-fw">OK</button>
</fo>

<script>
    $(document).ready(function() {
        $("#post_form").submit(function(e) {
            e.preventDefault();
            var u = $("#username");
            var p1 = $("#p1");
            var p2 = $("#p2")
            if(p1!="" && p1.val().trim() != p2.val().trim()){
                alert("Mật khẩu không khớp!");
            }
            else
            {
                $.ajax({
                    url:"xuly?action=change_pass",
                    type:"post",
                    data:$(this).serialize(),
                    success:function(data){
                        console.log(data);
                        if(data==1){
                            alert("Đã cập nhật!");
                            location.reload();
                        }
                        else
                        {
                            alert("Lỗi!");
                        }
                    },
                    error:function(data){
                        console.log('error ',data);
                    }
                })
            }
        })
    })
</script>