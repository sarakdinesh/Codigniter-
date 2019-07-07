<?php include('Layouts/header.php') ?>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">
        <?php include('Layouts/left-panel.php') ?>
        <!-- START CONTENT -->
        <section id="content">
            <!--breadcrumbs start-->
            <div id="breadcrumbs-wrapper">
                <!-- Search for small screen -->
                <div class="header-search-wrapper grey lighten-2 hide-on-large-only">
                    <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col s5 m5">
                            <h5 class="breadcrumbs-title">Movies</h5>
                            <ol class="breadcrumbs">
                                <li><a href="<?php echo $this->config->item('base_url') . 'back-office-panel/dashboard' ?>">Dashboard</a>
                                </li>
                                <li class="active">Movies</li>
                            </ol>
                        </div><br><br>
                        <div class="col s7 m7">
                            <a class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right" href="<?php echo $this->config->item('base_url') . 'back-office/movies' ?>">View Movies <i class="material-icons right">add_circle</i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--breadcrumbs end-->
            <!--start container-->

            <div class="container" >
                <form action="<?php echo base_url('back-office/movies-save') ?>" id="form" class="form-horizontal" enctype="multipart/form-data" method="post">
                    <div>

                        <div id="basic-form" class="section">
                            <h4><?php
                                if (isset($datas)) {
                                    echo "Edit Movies";
                                } else {
                                    echo "Add New Movies";
                                }
                                ?></h4>
                            <div class="row">
                                <div class="col s12 m12 l12">
                                    <div class="card-panel">
                                        <div class="row">
                                            <div class="row">
                                                <input id="id" type="hidden" name="id" value="<?php if (isset($datas)) echo $datas->unique_id ?>">
                                                <div class="input-field col s6">
                                                    <select name="menu_id"  id="menu_id" required="">

                                                        <?php
                                                        $this->table = "tbl_entertainment_category";
                                                        $datasdasd = $this->master->get_all_status();
                                                        if (isset($datas))
                                                            $sl_class = $datas->menu_id;
                                                        else
                                                            $sl_class = "";
                                                        if (empty($sl_class)) {
                                                            echo '<option  selected disabled>Please select Menu</option>';
                                                        }
                                                        foreach ($datasdasd as $key) {
                                                            $is_selected = '';

                                                            if ($key["id"] == $sl_class) {
                                                                $is_selected = 'selected';
                                                            }

                                                            echo '<option  value="' . $key["id"] . '" ' . $is_selected . ' > ' . $key["title"] . '</option>';
                                                        }
                                                        ?>

                                                    </select>
                                                    <label for="menu_id">Select Menu</label>
                                                </div>
                                                <div class="input-field col s6">
                                                    <input id="title" type="text" name="title" value="<?php if (isset($datas)) echo $datas->title ?>" required="">
                                                    <label for="title">Title</label>
                                                </div>
                                                <div class="input-field col s6">
                                                    <input id="page_name" type="text" name="page_name"  value="<?php if (isset($datas)) echo $datas->page_name ?>" required="">
                                                    <label for="page_name">Page Name</label>
                                                </div>
                                                <div class="input-field col s12">
                                                    <div>Description</div>
                                                    <textarea id="textarea1" class="materialize-textarea ckeditor" name="description" ><?php if (isset($datas)) echo $datas->description ?></textarea>

                                                </div>
                                                <div class="input-field col s6">
                                                    <input id="meta_keyword" type="text" name="meta_keyword"  value="<?php if (isset($datas)) echo $datas->meta_keyword ?>" required="">
                                                    <label for="meta_keyword">Meta Keyword</label>
                                                </div>
                                                <div class="input-field col s6">
                                                    <input id="meta_description" type="text" name="meta_description"  value="<?php if (isset($datas)) echo $datas->meta_description ?>" required="">
                                                    <label for="meta_description">Meta Description</label>
                                                </div>
                                                <br>
                                                <br>

                                                <div class="input_container">
                                                    <div class="row col s6">
                                                        <label class="control-label">Upload Image</label><br>
                                                        <div class="col s2">
                                                            <a style="cursor: pointer"><img src="<?php
                                                                if (isset($datas->image_path))
                                                                    echo base_url('uploads/') . $datas->image_path;
                                                                else
                                                                    echo base_url('public/image/upload.png');
                                                                ?>" id="imageDiv" alt="add image" class="pull-left " style="height:100px;width:100px"> </a>
                                                            <input type="file" id="image"  name="image" class="hide" />
                                                        </div>                                        </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <a href="#!" class="modal-action gradient-45deg-blue-grey-blue-grey modal-close btn">Cancel</a>

                    <input type="submit"  class="btn cyan waves-effect waves-light" id="btnSave" type="submit" value="
                           <?php
                           if (isset($datas)) {
                               echo "Update";
                           } else {
                               echo "Save";
                           }
                           ?>" name="action"/>


                </form>

            </div>
        </section>
        <br><br><br><br>
        <!-- END CONTENT -->
    </div>
    <!-- END WRAPPER -->
</div>

<script type="text/javascript" src="<?php echo base_url('public/admin/vendors/jquery-3.2.1.min.js '); ?>"></script>


<script type="text/javascript">
    $("#imageDiv").click(function () {
        $("input[id='image']").click();
        $(document).ready(function () {
            $("#image").on('change', function () {
                readURLs(this);
            });
        });

        function readURLs(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imageDiv').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>


<?php include("Layouts/footer.php"); ?>

<?php
if ($this->session->flashdata('error') != "") {
    echo "<script>Materialize.toast('" . $this->session->flashdata('error') . "', 4000, 'rounded');$('.toast').css('background-color', 'red');</script>";
    ?>
<?php } ?>

<?php
if ($this->session->flashdata('success') != "") {
    echo "<script>Materialize.toast('" . $this->session->flashdata('success') . "', 4000, 'rounded');$('.toast').css('background-color', 'green');</script>";
    ?>
<?php } ?>