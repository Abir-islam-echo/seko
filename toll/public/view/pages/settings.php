<?php loadView('header'); ?>

<?php
$db = new \Toll_Integration\DB();
if (!empty($_POST) && isset($_POST['action'])) {
    $db->processConfigForm((object) $_POST);
}
?>

<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Settings</h1>

    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Shopify API
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form action="" class="shopify-api-settings" method="POST">
                        <input type="hidden" name="action" value="shopify">
                        <div class="mb-3">
                            <label for="shopify[shopify_shop]" class="form-label">Shop</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('shopify_shop', 'shopify'); ?>" id="shopify[shopify_shop]" name="shopify[shopify_shop]" placeholder="myshop.shopify.com">
                        </div>
                        <div class="mb-3">
                            <label for="shopify[shopify_access_token]" class="form-label">API Access Token</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('shopify_access_token', 'shopify'); ?>" id="shopify[shopify_access_token]" name="shopify[shopify_access_token]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="shopify[shopify_api_key]" class="form-label">API Key</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('shopify_api_key', 'shopify'); ?>" id="shopify[shopify_api_key]" name="shopify[shopify_api_key]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="shopify[shopify_api_secret]" class="form-label">API Secret</label>
                            <input type="password" class="form-control" value="<?php echo $db->getConfig('shopify_api_secret', 'shopify'); ?>" id="shopify[shopify_api_secret]" name="shopify[shopify_api_secret]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="shopify[shopify_webhook_secret]" class="form-label">Webhook Secret Key</label>
                            <input type="password" class="form-control" value="<?php echo $db->getConfig('shopify_webhook_secret', 'shopify'); ?>" id="shopify[shopify_webhook_secret]" name="shopify[shopify_webhook_secret]" placeholder="">
                        </div>

                        <div class="mb-3">
                            <label for="shopify[shopify_api_scope]" class="form-label">API Scope</label>
                            <textarea class="form-control" id="shopify[shopify_api_scope]" name="shopify[shopify_api_scope]" placeholder=""><?php echo $db->getConfig('shopify_api_scope', 'shopify'); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Toll SFTP
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form action="" class="toll-sftp-settings" method="POST">
                        <input type="hidden" name="action" value="toll">
                        <div class="mb-3">
                            <label for="toll[sftp_host]" class="form-label">Host</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('sftp_host', 'toll'); ?>" id="toll[sftp_host]" name="toll[sftp_host]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="toll[sftp_port]" class="form-label">Port</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('sftp_port', 'toll'); ?>" id="toll[sftp_port]" name="toll[sftp_port]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="toll[sftp_username]" class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('sftp_username', 'toll'); ?>" id="toll[sftp_username]" name="toll[sftp_username]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="toll[sftp_password]" class="form-label">Passowrd</label>
                            <input type="password" class="form-control" value="<?php echo $db->getConfig('sftp_password', 'toll'); ?>" id="toll[sftp_password]" name="toll[sftp_password]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseTwo">
                    SMTP Settings
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form action="" class="toll-sftp-settings" method="POST">
                        <input type="hidden" name="action" value="mail">
                        <div class="mb-3">
                            <label for="mail[mail_host]" class="form-label">Host</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_host', 'mail'); ?>" id="mail[mail_host]" name="mail[mail_host]" placeholder="ex. smtp.example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mail[mail_port]" class="form-label">Port</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_port', 'mail'); ?>" id="mail[mail_port]" name="mail[mail_port]" placeholder="ex. 465">
                        </div>
                        <div class="mb-3">
                            <label for="mail[mail_username]" class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_username', 'mail'); ?>" id="mail[mail_username]" name="mail[mail_username]" placeholder="ex. user@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mail[mail_password]" class="form-label">Passowrd</label>
                            <input type="password" class="form-control" value="<?php echo $db->getConfig('mail_password', 'mail'); ?>" id="mail[mail_password]" name="mail[mail_password]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="mail[mail_encryption]" class="form-label">Encryption</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_encryption', 'mail'); ?>" id="mail[mail_encryption]" name="mail[mail_encryption]" placeholder="">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                    Mail Settings
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form action="" class="toll-sftp-settings" method="POST">
                        <input type="hidden" name="action" value="mail_settings">
                        <div class="mb-3">
                            <label for="mail_settings[mail_settings_mailer]" class="form-label">Admin Email</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_settings_mailer', 'mail_settings'); ?>" id="mail_settings[mail_settings_mailer]" name="mail_settings[mail_settings_mailer]" placeholder="ex. admin@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mail_settings[mail_settings_name]" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_settings_name', 'mail_settings'); ?>" id="mail_settings[mail_settings_name]" name="mail_settings[mail_settings_name]" placeholder="ex. John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="mail_settings[mail_settings_recipient]" class="form-label">Recipient Email</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_settings_recipient', 'mail_settings'); ?>" id="mail_settings[mail_settings_recipient]" name="mail_settings[mail_settings_recipient]" placeholder="ex. user@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mail_settings[mail_settings_cc]" class="form-label">Add CC</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_settings_cc', 'mail_settings'); ?>" id="mail_settings[mail_settings_cc]" name="mail_settings[mail_settings_cc]" placeholder="ex. user@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mail_settings[mail_settings_bcc]" class="form-label">Add BCC</label>
                            <input type="text" class="form-control" value="<?php echo $db->getConfig('mail_settings_bcc', 'mail_settings'); ?>" id="mail_settings[mail_settings_bcc]" name="mail_settings[mail_settings_bcc]" placeholder="ex. user@example.com">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php loadView('footer'); ?>