<?php loadView('header');
$db = new \Toll_Integration\DB();
if (!empty($_POST) && isset($_POST['action'])) {
    $db->processConfigForm((object) $_POST);
}
?>

<div class="content d-flex flex-column flex-column-fluid container-xxl" id="kt_content">

    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header toll-card border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0 toll-title">Toll SFTP Settings</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="sftp_settings_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" novalidate="novalidate">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="toll">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">SFTP Host Name</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="toll[sftp_host]" class="form-control form-control-lg form-control-solid" placeholder="" value="<?php echo $db->hasConfigKey('config_key', 'sftp_host') ? $db->getConfig('sftp_host', 'toll') : ""; ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                            <span class="required">SFTP Port</span>
                            <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i> -->
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="toll[sftp_port]" class="form-control form-control-lg form-control-solid" placeholder="" value="<?php echo $db->hasConfigKey('config_key', 'sftp_port') ? $db->getConfig('sftp_port', 'toll') : ""; ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">SFTP Username</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="text" name="toll[sftp_username]" class="form-control form-control-lg form-control-solid" placeholder="" value="<?php echo  $db->hasConfigKey('config_key', 'sftp_username') ? $db->getConfig('sftp_username', 'toll') : ""; ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">SFTP Password</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="password" name="toll[sftp_password]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'sftp_password')  ? $db->getConfig('sftp_password', 'toll') : ""; ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="sftp_settings_button">Save Changes</button>
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



<script>
    // order definition
    var orderValidation = function() {
        // Elements
        var form;
        var submitButton;
        var validator;

        // Handle form
        var handleForm = function(e) {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            validator = FormValidation.formValidation(
                form, {

                    fields: {
                        'toll[sftp_host]': {
                            validators: {
                                notEmpty: {
                                    message: 'SFTP host is required'
                                },

                            }
                        },
                        'toll[sftp_port]': {
                            validators: {
                                notEmpty: {
                                    message: 'SFTP port is required'
                                }
                            }
                        },
                        'toll[sftp_username]': {
                            validators: {
                                notEmpty: {
                                    message: 'SFTP username is required'
                                }
                            }
                        },
                        'toll[sftp_password]': {
                            validators: {
                                notEmpty: {
                                    message: 'SFTP password is required'
                                }
                            }
                        }

                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row'
                        })
                    }
                }
            );

            // Handle form submit
            submitButton.addEventListener('click', function(e) {
                // Prevent button default action
                e.preventDefault();

                // Validate form
                validator.validate().then(function(status) {
                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click 
                        submitButton.disabled = true;


                        // Simulate ajax request

                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        $.ajax({
                            type: "POST",
                            url: ajaxUrl,
                            data: {
                                toll: {
                                    sftp_host: form.querySelector('[name="toll[sftp_host]"]').value,
                                    sftp_port: form.querySelector('[name="toll[sftp_port]"]').value,
                                    sftp_username: form.querySelector('[name="toll[sftp_username]"]').value,
                                    sftp_password: form.querySelector('[name="toll[sftp_password]"]').value,
                                },
                                action: form.querySelector('[name="action"]').value
                            },
                            success: function(response) {
                                console.log(response);
                                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                if (response.status == true) {
                                    Swal.fire({
                                        text: "Shopify setttings data added sucessfully !",
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



                    } else {
                        // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
        }

        // Public functions
        return {
            // Initialization
            init: function() {
                form = document.querySelector('#sftp_settings_form');
                submitButton = document.querySelector('#sftp_settings_button');

                handleForm();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        orderValidation.init();
    });
</script>