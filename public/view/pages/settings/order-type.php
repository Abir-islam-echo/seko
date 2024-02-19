<?php loadView('header');

$db = new \Toll_Integration\DB();
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
                        <h3 class="fw-bolder m-0 toll-title">Order Prefix</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1 toll-title" style="font-size: 12px;">Here order number prefeix and order type can be assign</span>
                    </div>
                    <div class="card-toolbar">
                        <!-- Button trigger modal-->
                        <!--begin::Button-->
                        <a href="#" class="btn btn-primary  font-weight-bolder mr-2" data-bs-toggle="modal" data-bs-target="#order_prefix">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Design/Flatten.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Add New Order Type</a>
                        <!--end::Button-->

                        <!-- Modal 1 add new order modal:: start-->
                        <div class="modal fade" id="order_prefix" tabindex="-1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add New Order Type</h5>
                                        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close" style="padding: 0;">
                                            <i class="fa-solid fa-xmark toll-cross"></i>
                                        </button>
                                    </div>
                                    <form novalidate="novalidate" id="order_prefix_form" action="" method="POST">
                                        <input type="hidden" name="action" value="order">
                                        <div class="modal-body">

                                            <div class="form-group fv-row">
                                                <label class="pb-2">Order Prefix</label>
                                                <input name="order[prefix]" type="text" class="form-control form-control-lg" placeholder="Eg. B2B">
                                            </div>
                                            <div class="form-group fv-row pt-5">
                                                <label class="pb-2">Order Type</label>
                                                <input name="order[order_type]" type="text" class="form-control form-control-lg" placeholder="Eg. B2B">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="order_prefix_button" class="btn btn-success font-weight-bold toll-button">Add New</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal 1 add new modal: end-->



                        <!-- Modal 2  order Edit modal:: start-->
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
                                                <input name="order[prefix]" value='' type="text" id='order_prefix_edit_input' class="form-control form-control-lg" placeholder="Eg. B2B">
                                            </div>
                                            <div class="form-group fv-row pt-5">
                                                <label class="pb-2">Order Type</label>
                                                <input name="order[order_type]" value='' type="text" id='order_type_edit_input' class="form-control form-control-lg" placeholder="Eg. B2B">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="order_prefix_update_button" class="btn btn-success font-weight-bold toll-button">Update</button>
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
                                                    <th scope="col">Order Prefix</th>
                                                    <th scope="col">Order Type</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $orderData = $db->getOrderData();
                                                $count = 1;
                                                foreach ($orderData as $key => $value) {
                                                    if ($value['order_form'] == 'order') {

                                                ?>
                                                        <tr class='value{{ }}'>
                                                            <th class="pl-2" style="padding-left:10px ;"><?php echo $count++ ?></th>
                                                            <td><?php echo $value['prefix'] ?></td>
                                                            <td><?php echo $value['order_type'] ?></td>
                                                            <td>

                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#order_prefix_edit" data-method="EDIT" data-id="<?php echo $value['id'] ?>" data-prefix='<?php echo $value['prefix'] ?>' data-type='<?php echo $value['order_type'] ?>' class="btn btn-sm btn-clean btn-icon edit" title="Edit">
                                                                    <i class="fa-solid fa-pen toll-cross"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" data-method="DELETE" data-id="<?php echo $value['id'] ?>" class="btn btn-sm btn-clean btn-icon delete" title="Delete">
                                                                    <i class="fas fa-trash toll-cross"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
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

            <!-- email prefix :: start -->
            <div class="card card-custom mt-20 mb-20">
                <!--begin::Header-->
                <div class="card-header toll-card py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="fw-bolder m-0 toll-title">Email Prefix</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1 toll-title" style="font-size: 12px;">Here email prefeix and order type can be assign</span>
                    </div>
                    <div class="card-toolbar">



                        <!-- Button trigger modal-->
                        <!--begin::Button-->
                        <a href="#" class="btn btn-primary  font-weight-bolder mr-2" data-bs-toggle="modal" data-bs-target="#email_prefix">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Design/Flatten.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Add New Order Type</a>
                        <!--end::Button-->

                        <!-- Modal 1 add new modal:: start-->
                        <div class="modal fade" id="email_prefix" tabindex="-1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add New Order Type</h5>
                                        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close" style="padding: 0;">
                                            <i class="fa-solid fa-xmark toll-cross"></i>
                                        </button>
                                    </div>
                                    <form class="" action="" id="email_prefix_form" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="email">
                                            <div class="form-group fv-row">
                                                <label class="pb-2">Email Prefix</label>
                                                <div class="input-group mb-5">
                                                    <span class="input-group-text" id="basic-addon3">SAMPLE@</span>
                                                    <input type="text" class="form-control" name='email[prefix]' id="basic-url" aria-describedby="basic-addon3" />
                                                    <span class="input-group-text" id="basic-addon3">.COM</span>
                                                </div>
                                            </div>
                                            <div class="form-group pt-5 fv-row">
                                                <label class="pb-2">Order Type</label>
                                                <input name="email[order_type]" type="text" class="form-control form-control-lg" placeholder="Eg. B2B">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="email_prefix_button" class="btn btn-success font-weight-bold toll-button">Add New</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal 1 add new modal: end-->



                        <!-- Modal 2 Edit modal:: start-->
                        <div class="modal fade" id="email_prefix_edit" tabindex="-1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update Email Prefix</h5>
                                        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close" style="padding: 0;">
                                            <i class="fa-solid fa-xmark toll-cross"></i>
                                        </button>
                                    </div>
                                    <form class="" action="" id="email_prefix_update_form" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="email_update">
                                            <input type="hidden" name="id" id='email_id' value="">
                                            <div class="form-group fv-row">
                                                <label class="pb-2">Email Prefix</label>
                                                <div class="input-group mb-5">
                                                    <span class="input-group-text" id="basic-addon3">SAMPLE@</span>
                                                    <input type="text" class="form-control email_prefix_update_input " name='email[prefix]' id="basic-url" aria-describedby="basic-addon3" />
                                                    <span class="input-group-text" id="basic-addon3">.COM</span>
                                                </div>
                                            </div>
                                            <div class="form-group pt-5 fv-row">
                                                <label class="pb-2">Order Type</label>
                                                <input name="email[order_type]" type="text" class="form-control form-control-lg order_prefix_update_input " placeholder="Eg. B2B">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="email_prefix_update_button" class="btn btn-success font-weight-bold toll-button">Update</button>
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
                                        <table class="table email-table toll-table table-bordered  table-head-custom table-checkable" id="kt_datatable_example_1">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="padding-left: 10px;">Serial No</th>
                                                    <th scope="col">Email Prefix</th>
                                                    <th scope="col">Order Type</th>
                                                    <th scope="col">Action</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $orderData = $db->getOrderData();
                                                $count = 1;
                                                foreach ($orderData as $key => $value) {
                                                    if ($value['order_form'] == 'email') {
                                                ?>
                                                        <tr class='value{{ }}'>
                                                            <th id='serial' style="padding-left: 10px;"><?php echo $count++ ?></th>
                                                            <td><?php echo $value['prefix'] ?></td>
                                                            <td><?php echo $value['order_type'] ?></td>
                                                            <td>

                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#email_prefix_edit" data-method="EDIT" data-id="<?php echo $value['id'] ?>" data-prefix='<?php echo $value['prefix'] ?>' data-type='<?php echo $value['order_type'] ?>' class="btn btn-sm btn-clean btn-icon edit" title="Edit">
                                                                    <i class="fa-solid fa-pen toll-cross"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" data-method="DELETE" data-id="<?php echo $value['id'] ?>" class="btn btn-sm btn-clean btn-icon delete" title="Delete">
                                                                    <i class="fas fa-trash toll-cross"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
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
            <!-- email prefix :: end -->

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