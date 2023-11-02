<?php loadView('header');

$sftp = new \Toll_Integration\RemoteSFTP();
if (!empty($_POST) && isset($_POST['action'])) {
    $db->processOrderForm((object) $_POST);
}

?>
<!--begin::Content-->
<div class=" d-flex flex-column flex-column-fluid">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom ">
                <!--begin::Header-->
                <div class="card-header toll-card py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="fw-bolder m-0 toll-title">Dispatch Log</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1 toll-title" style="font-size: 12px;">All the list of unfullfilled order</span>
                    </div>
                    <div class="card-toolbar">
                        <!-- Button trigger modal-->

                        <!-- Modal 2  order Edit modal:: start-->
                        <style>
                            .modal-dialog {
                                max-width: 70%;
                            }
                        </style>
                        <div class="modal fade" id="order_prefix_edit" tabindex="-1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update Order Type</h5>
                                        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close" style="padding: 0;">
                                            <i class="fa-solid fa-xmark toll-cross"></i>
                                        </button>
                                    </div>
                                    <form novalidate="novalidate" id="order_prefix_form" action="" method="POST">
                                        <input type="hidden" name="action" value="order_update">
                                        <input type="hidden" name="id" id='order_id' value="">
                                        <div class="modal-body">

                                            <div class="form-group fv-row">
                                                <label class="pb-2">Order Prefix</label>
                                                <textarea name="" id="order_prefix_edit_input" cols="30" rows="20" class="form-control form-control-lg"></textarea>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal 2 Edit modal: end-->

                        <!-- <button type="reset" class="btn btn-success mr-2">Save Changes</button> -->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <!--order table -->
                <div class="">
                    <!--begin: Datatable-->

                    <div class="flex-row-fluid">
                        <!--begin::Card-->
                        <div class="card card-custom card-stretch">


                            <!--begin::Body-->
                            <div class="card-body">

                                <!-- order Prefix table::Start -->
                                <div class="card card-custom card-toll">

                                    <div class="card-body">
                                        <!--begin: Datatable-->
                                        <table class="table order-table toll-table table-bordered  table-head-custom table-checkable" id="kt_datatable">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="padding-left: 10px;">Serial No</th>
                                                    <th scope="col">File Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $unfufilledData = $sftp->alldirectoreis();
                                                print_r($unfufilledData);
                                                $count = 1;
                                                foreach ($unfufilledData as $key => $value) {
                                                    if ($key == 0 | $key == 1 | $key == 2) {
                                                        continue;
                                                    }
                                                ?>
                                                    <tr class='value{{ }}'>
                                                        <th class="pl-2" style="padding-left:10px ;"><?php echo $count++ ?></th>
                                                        <td><?php echo $value ?> </td>
                                                        <td>

                                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#order_prefix_edit" data-method="EDIT" data-id="<?php echo $value ?>" data-prefix='<?php echo $sftp->singleUnfullfillXml($value) ?>' data-type='<?php echo $value ?>' class="btn btn-sm btn-clean btn-icon edit" title="Edit">
                                                                <i class="fa-solid fa-pen toll-cross"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php

                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <!--end: Datatable-->
                                    </div>
                                </div>
                                <!-- order Prefix table::End  -->
                            </div>
                            <!--end::Body-->

                        </div>

                    </div>


                    <!--end: Datatable-->

                    <!--end::Body-->
                </div>
                <!--end::Card-->
            </div>

            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->



    <script type="text/javascript">
        $(document).ready(function() {

            // function Edit POST
            $(document).on('click', '.order-table .edit', function() {

                $('#order_id').val($(this).data('id'));
                $('#order_prefix_edit_input').val($(this).data('prefix'));
                $('#order_type_edit_input').val($(this).data('type'));
            });
            $(document).on('click', '.email-table .edit', function() {

                $('#email_id').val($(this).data('id'));
                $('.order_prefix_update_input').val($(this).data('type'));
                $('.email_prefix_update_input').val($(this).data('prefix'));
            });

        });
    </script>





    <?php loadView('footer'); ?>
    <script>
        $("#kt_datatable_example_1").DataTable();
    </script>

    <script>
        $('.delete').on('click', function(e) {
            var btn = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",

                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        type: "POST",
                        url: ajaxUrl,
                        data: {
                            table_id: {
                                id: $(this).data('id')
                            },
                            action: 'delete'
                        },
                        success: function(response) {
                            console.log(response);
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            if (response.status == true) {
                                Swal.fire({
                                    text: "Deleted sucessfully !",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    location.reload();

                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again. !",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                })
                            }
                        },
                        dataType: 'json'
                    });




                }
            })
        });
    </script>

    <script src="<?php echo APP_URL; ?>public/assets/js/template/submit.js"></script>