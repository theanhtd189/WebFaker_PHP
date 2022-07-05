<?php
if(!isset($show)){
    header('Location: index.php');
}
$set_type = "css";
$list_r = $App->Select("SELECT * FROM `config` WHERE Type = '$set_type'", 0);
$num = 0;

if ($list_r) {
    $num = (mysqli_num_rows($list_r));
}
?>
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-textdirection-l-to-r"></i>
        </span> Css
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <!-- <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li> -->
        </ul>
    </nav>
</div>

<div class="card-body">
    <h4 class="card-title"><i class="mdi mdi-bullseye"></i> Web Style </h4>
    <p class="card-description">Thay đổi css trang web </p>
    <button type="button" id="show_table" class="btn btn-inverse-success btn-fw"><i class="mdi mdi-plus-circle"></i> Thêm mới</button>

    <blockquote class="blockquote" style="position: relative;display:none" id="_table">
        <div class="card-body">
            <form class="forms-sample" enctype="multipart/form-data" autocomplete="off" id="post_form">
                <div class="form-group">
                    <label for="">Tên <code>( Tên cho dễ nhớ thôi )</code> </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên css" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Css <code>( Nội dung css )</code> </label>
                    <textarea type="text" rows="10" class="form-control" id="value" name="value" required autocomplete="off"></textarea>
                </div>

                <input type="hidden" name="callback" value="<?=$_SERVER['REQUEST_URI']?>">
                <input type="hidden" name="ID" id="id">
                <input type="hidden" name="type" value="<?=$set_type?>">
                <input type="hidden" name="ok">
                <button type="submit" class="btn btn-gradient-primary me-2" id="btn-ok">OK</button>
                <button type="reset" style="display:none" class="btn btn-gradient-primary me-2" id="btn-reset">Hủy</button>
            </form>
        </div>
    </blockquote>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Danh sách</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Giá Trị</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($num != 0)
                while ($r = mysqli_fetch_assoc($list_r)) {
                    $_id = $r['ID'];
                    $_name = $r['Name'];                 
                    $_value = base64_decode($r['Value']);                      
                ?>
                    <tr>
                        <td r-id="<?= $_id ?>" r-name="Name" r-value="<?= $_name ?>"><?= $_name ?></td>
                        <td r-id="<?= $_id ?>" r-name="Value" r-value="<?= $_value ?>">
                        <textarea rows="6"><?= $_value ?></textarea>
                    </td>
                        <td>
                            <a href="#" e-type="edit" data-id="<?= $_id ?>">Sửa</a> |
                            <a href="xuly?action=delete&oid=<?= $_id ?>" e-type="delete" data-id="<?= $_id ?>">Xóa</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once 'admin_js.php';
?>