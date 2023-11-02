"use strict";

// order definition
var orderValidation = function () {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'order[prefix]': {
                        validators: {
                            notEmpty: {
                                message: 'Order prefix is required'
                            },

                        }
                    },
                    'order[order_type]': {
                        validators: {
                            notEmpty: {
                                message: 'Order type is required'
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
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
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
                            order: {
                                prefix: form.querySelector('[name="order[prefix]"]').value,
                                order_type: form.querySelector('[name="order[order_type]"]').value,
                            },
                            action: form.querySelector('[name="action"]').value
                        },
                        success: function (response) {
                            console.log(response);
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            if (response.status == true) {
                                Swal.fire({
                                    text: "Order Prefix data added sucessfully !",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
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
        init: function () {
            form = document.querySelector('#order_prefix_form');
            submitButton = document.querySelector('#order_prefix_button');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    orderValidation.init();
});

// order update definition
var orderUpdateValidation = function () {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'order[prefix]': {
                        validators: {
                            notEmpty: {
                                message: 'Order prefix is required'
                            },

                        }
                    },
                    'order[order_type]': {
                        validators: {
                            notEmpty: {
                                message: 'Order type is required'
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
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
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
                            order: {
                                prefix: form.querySelector('[name="order[prefix]"]').value,
                                order_type: form.querySelector('[name="order[order_type]"]').value,
                                id: form.querySelector('[name="id"]').value,
                            },
                            action: form.querySelector('[name="action"]').value
                        },
                        success: function (response) {
                            console.log(response);
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            if (response.status == true) {
                                Swal.fire({
                                    text: "Order Prefix data updated sucessfully !",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
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
        init: function () {
            form = document.querySelector('#order_prefix_edit');
            submitButton = document.querySelector('#order_prefix_update_button');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    orderUpdateValidation.init();
});




// email  definition
var emailValidation = function () {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'email[prefix]': {
                        validators: {
                            notEmpty: {
                                message: 'Email prefix is required'
                            },

                        }
                    },
                    'email[order_type]': {
                        validators: {
                            notEmpty: {
                                message: 'Order type is required'
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
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
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
                            email: {
                                prefix: form.querySelector('[name="email[prefix]"]').value,
                                order_type: form.querySelector('[name="email[order_type]"]').value,
                            },
                            action: form.querySelector('[name="action"]').value
                        },
                        success: function (response) {
                            console.log(response);
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            if (response.status == true) {
                                Swal.fire({
                                    text: "Email Prefix data added sucessfully !",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
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
        init: function () {
            form = document.querySelector('#email_prefix_form');
            submitButton = document.querySelector('#email_prefix_button');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    emailValidation.init();
});


// email update definition
var emailUpdateValidation = function () {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'email[prefix]': {
                        validators: {
                            notEmpty: {
                                message: 'Email prefix is required'
                            },

                        }
                    },
                    'email[order_type]': {
                        validators: {
                            notEmpty: {
                                message: 'Order type is required'
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
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
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
                            email: {
                                prefix: form.querySelector('[name="email[prefix]"]').value,
                                order_type: form.querySelector('[name="email[order_type]"]').value,
                                id: form.querySelector('[name="id"]').value
                            },
                            action: form.querySelector('[name="action"]').value
                        },
                        success: function (response) {
                            console.log(response);
                            // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            if (response.status == true) {
                                Swal.fire({
                                    text: "Email Prefix data updated sucessfully !",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
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
        init: function () {
            form = document.querySelector('#email_prefix_update_form');
            submitButton = document.querySelector('#email_prefix_update_button');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    emailUpdateValidation.init();
});


