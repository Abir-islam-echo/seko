<?php loadView('header');
$db = new \Toll_Integration\DB();
if (!empty($_POST) && isset($_POST['action'])) {
    $db->processConfigForm((object) $_POST);
}
?>

<div class="content d-flex flex-column flex-column-fluid container-xxl">

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header toll-card border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0 toll-title">SMTP Settings</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="" method="POST">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="mail">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Host Name</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="mail[mail_host]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_host', 'mail'); ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                            <span class="required">Port</span>
                            <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i> -->
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="mail[mail_port]" class="form-control form-control-lg form-control-solid" placeholder="" value="<?php echo $db->getConfig('mail_port', 'mail'); ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required"> Username</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="mail[mail_username]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_username', 'mail'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Password</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="password" name="mail[mail_password]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_password', 'mail'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Encryption</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="mail[mail_encryption]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_encryption', 'mail'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="kt_account_profile_details_submit">Save Changes</button>
                </div>
                <!--end::Actions-->
                <input type="hidden">
                <div></div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>


</div>
<div class="content d-flex flex-column flex-column-fluid container-xxl">

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header toll-card border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0 toll-title">Admin Mail Settings</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="" method="POST">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="mail_settings">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Admin Email</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="mail_settings[mail_settings_mailer]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_settings_mailer', 'mail_settings'); ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                            <span class="required">Admin Name</span>
                            <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i> -->
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="mail_settings[mail_settings_name]" class="form-control form-control-lg form-control-solid" placeholder="" value="<?php echo $db->getConfig('mail_settings_name', 'mail_settings'); ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required"> Username</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="mail_settings[mail_settings_recipient]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_settings_recipient', 'mail_settings'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Add CC</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="mail_settings[mail_settings_cc]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_settings_cc', 'mail_settings'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Add BCC</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="mail_settings[mail_settings_bcc]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->getConfig('mail_settings_bcc', 'mail_settings'); ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="kt_account_profile_details_submit">Save Changes</button>
                </div>
                <!--end::Actions-->
                <input type="hidden">
                <div></div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>


</div>
<!-- cancel Mail template -->
<div class="content d-flex flex-column flex-column-fluid container-xxl">

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header toll-card border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0 toll-title">Cancel Mail Template</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="" method="POST">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="cancel">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Mail Subject
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-9 fv-row fv-plugins-icon-container">
                            <input name='cancel[cancel_subject]' class='form-control form-control-lg form-control-solid' value='<?php echo $db->getConfig('cancel_subject', 'cancel'); ?>'>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class='col-lg-3'>
                            <label class=" col-form-label required fw-bold fs-6">Cancle Template</label>
                            <div class="text-muted mb-5">Short Code :</br>[order_id],[order_number],[customer_name],[customer_address],[customer_phone],[customer_email]
                                [product_details],[customer_details],[cancel_details]</div>
                        </div>


                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-9 fv-row fv-plugins-icon-container">
                            <textarea id="myTextarea" name='cancel[cancel_template]'><?php echo $db->getConfig('cancel_template', 'cancel'); ?></textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="kt_account_profile_details_submit">Save Changes</button>
                </div>
                <!--end::Actions-->
                <input type="hidden">
                <div></div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>


</div>

<!-- hold mail template -->
<div class="content d-flex flex-column flex-column-fluid container-xxl">

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header toll-card border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0 toll-title">Hold Mail Template</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="" method="POST">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="hold">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Hold Subject</label>
                        <!--end::Label-->
                        <!--begin::Col-->

                        <div class="col-lg-10 fv-row fv-plugins-icon-container">
                            <input name='hold[hold_subject]' class='form-control form-control-lg form-control-solid' value='<?php echo $db->getConfig('hold_subject', 'hold'); ?>'>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <div class='col-lg-3'>
                            <label class=" col-form-label required fw-bold fs-6">Hold Template</label>
                            <div class="text-muted mb-5">Short Code :</br>[order_id],[customer_name],[customer_address],[product_name]</div>
                        </div>
                        <!--end::Label-->
                        <!--begin::Col-->

                        <div class="col-lg-9 fv-row fv-plugins-icon-container">
                            <textarea id="myTextarea" name='hold[hold_template]'><?php echo $db->getConfig('hold_template', 'hold'); ?></textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="kt_account_profile_details_submit">Save Changes</button>
                </div>
                <!--end::Actions-->
                <input type="hidden">
                <div></div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>


</div>



<?php loadView('footer'); ?>