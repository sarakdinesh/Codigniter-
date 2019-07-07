<?php include('Layouts/header.php') ?>
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
                            <a class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right" href="<?php echo $this->config->item('base_url') . 'back-office/movies-add' ?>">ADD Movies <i class="material-icons right">add_circle</i></a>

                        </div>
                    </div>

                </div>
            </div>
            <!--breadcrumbs end-->
            <!--start container-->

            <div class="container" >

                <div class="section">
                    <!--DataTables example-->
                    <div id="table-datatables">
                        <div class="row">

                            <div class="col s12">
                                <table id="data-table-simple" class="hoverable display " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Category</th>
                                            <th>Title</th>
                                            <th>Page Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Image</th>
                                            <th>Category</th>
                                            <th>Title</th>
                                            <th>Page Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--end container-->
                </div>
            </div>
        </section>

        <!-- END CONTENT -->
    </div>
    <!-- END WRAPPER -->
</div>
<script type="text/javascript" src="<?php echo base_url('public/admin/vendors/jquery-3.2.1.min.js '); ?>"></script>


<script type="text/javascript">

    var save_method;
    var table;
    $(document).ready(function () {
        table = $('#data-table-simple').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "order": [],
            "ajax": {
                "url": "<?php echo $this->config->item('base_url') . '/back-office/movies-list' ?>",
                "type": "POST"
            },

            "columnDefs": [
                {
                    "targets": [-1],
                    "orderable": false,
                },
            ],

        });


    });
    function reload_table()
    {
        table.ajax.reload(null, false);
    }

    function delete_person(id)
    {
        swal({title: "Are you sure to delete record ?",
            text: "Delete this record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "green",
            confirmButtonText: "Yes",
            closeOnConfirm: false},
                function () {
                    $.ajax({
                        url: "<?php echo $this->config->item('base_url') . '/back-office/movies-delete' ?>/" + id,
                        type: "POST",
                        data: {
                            'id': id},
                        dataType: "JSON",
                        success: function (data)
                        {
                            swal("Deleted!", "Record has been Deleted successfully.", "success");
                            Materialize.toast('Success! Deleted successfully', 2000);
                            $('.toast').css('background-color', 'green');
                            $('#modal_form').modal('close');

                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            Materialize.toast('ERROR! Please try again', 2000);
                            $(".toast").css("background-color", "red");
                        }
                    });

                });
    }


    function active(id)
    {
        swal({title: "Are you sure?",
            text: "You want to activate or deactivate this record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "green",
            confirmButtonText: "Yes",
            closeOnConfirm: false},
                function () {
                    $.ajax({
                        url: "<?php echo $this->config->item('base_url') . '/back-office/movies-active' ?>/" + id,
                        type: "POST",
                        data: {
                            'id': id},
                        dataType: "JSON",
                        success: function (data)
                        {
                            if (data.flag == 1)
                            {
                                swal("Activated!", "Record has been activated successfully.", "success");
                                Materialize.toast('Success! Activated successfully', 2000);
                                $('.toast').css('background-color', 'green');
                                $('#modal_form').modal('close');
                                reload_table();
                            } else {
                                swal("Deactivated!", "Record has been deactivated successfully.", "success");
                                Materialize.toast('Success! Deactivated successfully', 2000);
                                $('.toast').css('background-color', 'green');
                                $('#modal_form').modal('close');
                                reload_table();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            Materialize.toast('ERROR! Please try again', 2000);
                            $(".toast").css("background-color", "red");
                        }
                    });

                });
    }

    function copy(url)
    {
        Materialize.toast(url, 4000);
        $(".toast").css("background-color", "green");
    }

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