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
                <h3 class="fw-bolder m-0 toll-title">Shopiy API Settings</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="shopify_settings_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="" method="POST">
                <!--begin::Card body-->
                <input type="hidden" name="action" value="shopify">
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Shop URL</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="shopify[shopify_shop]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'shopify_shop') ? $db->getConfig('shopify_shop', 'shopify') : ""; ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                            <span class="required">API Access Token</span>
                            <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i> -->
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="password" name="shopify[shopify_access_token]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'shopify_access_token') ? $db->getConfig('shopify_access_token', 'shopify') : ""; ?>">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">API Key</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="password" name="shopify[shopify_api_key]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'shopify_api_key') ? $db->getConfig('shopify_api_key', 'shopify') : ""; ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">API Secret</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="password" name="shopify[shopify_api_secret]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'shopify_api_secret') ? $db->getConfig('shopify_api_secret', 'shopify') : ""; ?>">
                        </div>
                        <?php echo $db->hasConfigKey('config_form', 'shopify_api_secret')
                        ?>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Webhook Secret Key</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="password" name="shopify[shopify_webhook_secret]" class="form-control form-control-lg form-control-solid" placeholder=" " value="<?php echo $db->hasConfigKey('config_key', 'shopify_webhook_secret') ? $db->getConfig('shopify_webhook_secret', 'shopify') : ""; ?>">
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">API Scope</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <textarea name="shopify[shopify_api_scope]" class="form-control form-control-lg form-control-solid" placeholder=" "><?php echo $db->hasConfigKey('config_key', 'shopify_api_scope') ? $db->getConfig('shopify_api_scope', 'shopify') :  ""; ?></textarea>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
                    <button type="submit" class="btn toll-button " id="shopify_settings_button">Save Changes</button>
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
                        'shopify[shopify_shop]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shop Url is required'
                                },

                            }
                        },
                        'shopify[shopify_access_token]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shopify API access token is required'
                                }
                            }
                        },
                        'shopify[shopify_api_key]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shopify API key is required'
                                }
                            }
                        },
                        'shopify[shopify_api_secret]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shopify API secret is required'
                                }
                            }
                        },
                        'shopify[shopify_webhook_secret]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shopify webhook secret is required'
                                }
                            }
                        },
                        'shopify[shopify_api_scope]': {
                            validators: {
                                notEmpty: {
                                    message: 'Shopify API scope is required'
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
                                shopify: {
                                    shopify_shop: form.querySelector('[name="shopify[shopify_shop]"]').value,
                                    shopify_access_token: form.querySelector('[name="shopify[shopify_access_token]"]').value,
                                    shopify_api_key: form.querySelector('[name="shopify[shopify_api_key]"]').value,
                                    shopify_api_secret: form.querySelector('[name="shopify[shopify_api_secret]"]').value,
                                    shopify_webhook_secret: form.querySelector('[name="shopify[shopify_webhook_secret]"]').value,
                                    shopify_api_scope: form.querySelector('[name="shopify[shopify_api_scope]"]').value,
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
                form = document.querySelector('#shopify_settings_form');
                submitButton = document.querySelector('#shopify_settings_button');

                handleForm();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        orderValidation.init();
    });
</script>