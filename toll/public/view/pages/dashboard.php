<?php loadView('header');
$db = new \Toll_Integration\DB();
$api = new \Toll_Integration\API();
$mail = new \Toll_Integration\Mail();
$remoteSftp = new \Toll_Integration\RemoteSFTP();

?>
<div id="kt_content_container" class="content d-flex flex-column flex-column-fluid container-xxl">
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xl-12">
            <!--begin::Mixed Widget 2-->
            <div class="card card-xl-stretch">
                <!--begin::Header-->
                <div class="card-header border-0  py-5" style="background-color:#037e79 !important; border-bottom: 1px solid #EFF2F5 !important;">
                    <h3 class="card-title fw-bolder text-white">Connection Status</h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body p-0">
                    <!--begin::Chart-->
                    <div class="mixed-widget-2-chart card-rounded-bottom bg-danger" data-kt-color="danger" style="height: 200px; min-height: 200px; background-color:#037e79 !important;"></div>
                    <!--end::Chart-->
                    <!--begin::Stats-->
                    <div class="card-p mt-n20 position-relative">
                        <!--begin::Row-->
                        <div class="row g-0">
                            <!--begin::Col-->
                            <div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
                                <a href="#" class="btn btn-sm <?php echo $db->getInfo()['connection'] ? 'btn-success' : 'btn-danger'; ?>" style="float: right; margin-top:-10px;"><?php echo $db->getInfo()['connection'] ? 'Connected' : 'Disconnected'; ?></a>
                                <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect x="8" y="9" width="3" height="10" rx="1.5" fill="currentColor"></rect>
                                        <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="currentColor"></rect>
                                        <rect x="18" y="11" width="3" height="8" rx="1.5" fill="currentColor"></rect>
                                        <rect x="3" y="13" width="3" height="6" rx="1.5" fill="currentColor"></rect>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <span href="#" class="text-warning fw-bold fs-6">Database</span>

                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col bg-light-primary px-6 py-8 rounded-2 me-7 mb-7">
                                <a href="#" class="btn btn-sm <?php echo $api->status  ? 'btn-success' : 'btn-danger'; ?>" style="float: right; margin-top:-10px;"><?php echo $api->status ? 'Connected' : 'Disconnected'; ?></a>
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <img alt="Logo" src="<?php echo APP_URL; ?>public/assets/media/shopify.png" class="h-40px" style="display: block;margin-bottom: 5px;margin-top: 10px;" />
                                <!--end::Svg Icon-->
                                <a href="#" class="text-primary fw-bold fs-6">Shopify</a>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col bg-light-primary px-6 py-8 rounded-2 me-7 mb-7">
                                <a href="#" class="btn btn-sm <?php echo $remoteSftp->status ? 'btn-success' : 'btn-danger'; ?>" style="float: right; margin-top:-10px;"><?php echo $remoteSftp->status ? 'Connected' : 'Disconnected'; ?></a>
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="currentColor"></path>
                                        <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <a href="#" class="text-primary fw-bold fs-6">Toll</a>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col bg-light-primary px-6 py-8 rounded-2  mb-7">
                                <a href="#" class="btn btn-sm <?php echo $mail->status ? 'btn-success' : 'btn-danger'; ?>" style="float: right; margin-top:-10px;"><?php echo $mail->status ? 'Connected' : 'Disconnected'; ?></a>
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="currentColor"></path>
                                        <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <a href="#" class="text-primary fw-bold fs-6">SMTP</a>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->

                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 2-->
        </div>
        <!--end::Col-->

    </div>
    <!--end::Row-->

</div>
<?php loadView('footer'); ?>