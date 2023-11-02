<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <!--begin::Container-->
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-bold me-1">2022© All rights reserved</span>
            <a href="https://keenthemes.com/" target="_blank" class="text-gray-800 text-hover-primary">Toll App</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
            <li class="menu-item">
                <a href="" target="_blank" class="menu-link px-2">About</a>
            </li>
            <li class="menu-item">
                <a href="" target="_blank" class="menu-link px-2">Support</a>
            </li>

        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::Root-->

<!--end::Main-->

<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="<?php echo APP_URL; ?>public/assets/js/template/plugins.bundle.js"></script>
<script src="<?php echo APP_URL; ?>public/assets/js/template/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<script type='text/javascript' src='<?php echo APP_URL; ?>public/assets/js/script.js'></script>
<script src="<?php echo APP_URL; ?>public/assets/js/template/datatables.bundle.js"></script>
<script src="<?php echo APP_URL; ?>public/assets/js/template/paginations.js"></script>
<script src="<?php echo APP_URL; ?>public/assets/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#myTextarea',
    });
</script>
<?php
if (isset($_SESSION["status"])) {
    if ($_SESSION["status"] == true) {
        unset($_SESSION["status"]);
?>
        <script>
            Swal.fire({
                text: "Information Saved Sucessfully",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Done",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
    <?php
        echo "session true";
    } else {
        echo 'session nai';
    ?>
        <script>
            Swal.fire({
                text: "Something wrong happen, Try again !",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Done",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
<?php

    }
}

?>
</body>
<!--end::Body-->


</html>