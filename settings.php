<?php
    require_once 'includes/header.php';
?>
<section class="contain settings">
    <section class="account-section">
        <header>
            <h2><?= translate('user_details', $i18n) ?></h2>
        </header>
            <form action="endpoints/user/saveuser.php" method="post" id="userForm">
                <div class="user-form">
                    <div class="fields">
                        <div>
                            <div class="user-avatar">
                                <img src="images/avatars/<?= $userData['avatar'] ?>.svg" alt="avatar" class="avatar" id="avatarImg" onClick="toggleAvatarSelect()"/>
                                <span class="edit-avatar" onClick="toggleAvatarSelect()">
                                    <img src="images/siteicons/editavatar.png" title="Change avatar" />
                                </span>
                            </div>
                            
                            <input type="hidden" name="avatar" value="<?= $userData['avatar'] ?>" id="avatarUser"/>
                            <div class="avatar-select" id="avatarSelect">
                                <div class="avatar-list">
                                    <img src="images/avatars/0.svg" onClick="changeAvatar(0)" />
                                    <img src="images/avatars/1.svg" onClick="changeAvatar(1)" />
                                    <img src="images/avatars/2.svg" onClick="changeAvatar(2)" />
                                    <img src="images/avatars/3.svg" onClick="changeAvatar(3)" />
                                    <img src="images/avatars/4.svg" onClick="changeAvatar(4)" />
                                    <img src="images/avatars/5.svg" onClick="changeAvatar(5)" />
                                    <img src="images/avatars/6.svg" onClick="changeAvatar(6)" />
                                    <img src="images/avatars/7.svg" onClick="changeAvatar(7)" />
                                    <img src="images/avatars/8.svg" onClick="changeAvatar(8)" />
                                    <img src="images/avatars/9.svg" onClick="changeAvatar(9)" />
                                </div>
                            </div>
                        </div>
                        <div class="grow">
                            <div class="form-group">
                                <label for="username"><?= translate('username', $i18n) ?>:</label>
                                <input type="text" id="username" name="username" value="<?= $userData['username'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><?= translate('email', $i18n) ?>:</label>
                                <input type="email" id="email" name="email" value="<?= $userData['email'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password"><?= translate('password', $i18n) ?>:</label>
                                <input type="password" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><?= translate('confirm_password', $i18n) ?>:</label>
                                <input type="password" id="confirm_password" name="confirm_password">
                            </div>
                            <?php
                                $currencies = array();
                                $query = "SELECT * FROM currencies";
                                $result = $db->query($query);
                                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                                    $currencyId = $row['id'];
                                    $currencies[$currencyId] = $row;
                                }
                            ?>
                            <div class="form-group">
                                <label for="currency"><?= translate('main_currency', $i18n) ?>:</label>
                                <select id="currency" name="main_currency" placeholder="Currency">
                                <?php
                                    foreach ($currencies as $currency) {
                                        $selected = ($currency['id'] == $userData['main_currency']) ? 'selected' : '';    
                                ?>
                                        <option value="<?= $currency['id'] ?>" <?= $selected ?>><?= $currency['name'] ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="language"><?= translate('language', $i18n) ?>:</label>
                                <select id="language" name="language" placeholder="Language">
                                <?php 
                                    foreach ($languages as $code => $name) {
                                        $selected = ($code === $lang) ? 'selected' : '';
                                ?>
                                        <option value="<?= $code ?>" <?= $selected ?>><?= $name ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>  
                    </div>
                    <div class="buttons">
                        <input type="submit" value="<?= translate('save', $i18n) ?>" id="userSubmit"/>
                    </div>
                </div>
            </form>

    </section>

    <?php
        $sql = "SELECT * FROM household";
        $result = $db->query($sql);

        if ($result) {
            $household = array();
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $household[] = $row;
            }
        }
    ?>

    <section class="account-section">
        <header>
            <h2><?= translate('household', $i18n) ?></h2>
        </header>
        <div class="account-members">
            <div  id="householdMembers">
            <?php
                foreach ($household as $member) {
                    ?>
                    <div class="form-group-inline" data-memberid="<?= $member['id'] ?>">
                        <input type="text" name="member" value="<?= $member['name'] ?>" placeholder="Member">
                        <button class="image-button medium"  onClick="editMember(<?= $member['id'] ?>)" name="save">
                            <img src="images/siteicons/save.png" title="<?= translate('save_member', $i18n) ?>">
                        </button>
                        <?php
                            if ($member['id'] != 1) {
                                ?>
                                    <button class="image-button medium" onClick="removeMember(<?= $member['id'] ?>)">
                                        <img src="images/siteicons/delete.png" title="<?= translate('delete_member', $i18n) ?>">
                                    </button>
                                <?php
                            } else {
                                ?>
                                    <button class="image-button medium disabled">
                                        <img src="images/siteicons/delete.png" title="<?= translate('cant_delete_member', $i18n) ?>">
                                    </button>
                                <?php
                            }
                        ?>
                    </div>
                    <?php
                }
            ?>
            </div>
            <div class="buttons">
                <input type="submit" value="<?= translate('add', $i18n) ?>" id="addMember" onClick="addMemberButton()"/>
            </div>
        </div>
    </section>

    <?php
        $sql = "SELECT * FROM notifications LIMIT 1";
        $result = $db->query($sql);

        $rowCount = 0;
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $notifications = $row;
            $rowCount++;
        }
        
        if ($rowCount == 0) {
            $notifications['enabled'] = false;
            $notifications['days'] = 1;
            $notifications['smtp_address'] = "";
            $notifications['smtp_port'] = "";
            $notifications['smtp_username'] = "";
            $notifications['smtp_password'] = "";
            $notifications['from_email'] = "";
        }

    ?>

    <section class="account-section">
        <header>
            <h2><?= translate('notifications', $i18n) ?></h2>
        </header>
        <div class="account-notifications">
            <div class="form-group-inline">
                <input type="checkbox" id="notifications" name="notifications" <?= $notifications['enabled'] ? "checked" : "" ?>>
                <label for="notifications"><?= translate('enable_email_notifications', $i18n) ?></label>
            </div>
            <div class="form-group">
                <label for="days"><?= translate('notify_me', $i18n) ?>:</label>
                <select name="days" id="days">
                <?php
                    for ($i = 1; $i <= 7; $i++) {
                        $dayText = $i > 1 ? translate('days_before', $i18n) : translate('day_before', $i18n);
                        $selected = $i == $notifications['days'] ? "selected" : "";
                        ?>
                            <option value="<?= $i ?>" <?= $selected ?>>
                                <?= $i ?> <?= $dayText ?>
                            </option>
                        <?php
                    }
                ?>
                </select>
            </div>
            <div class="form-group-inline">
                <input type="text" name="smtpaddress" id="smtpaddress" placeholder="<?= translate('smtp_address', $i18n) ?>" value="<?= $notifications['smtp_address'] ?>" />
                <input type="text" name="smtpport" id="smtpport" placeholder="<?= translate('port', $i18n) ?>" class="one-third"  value="<?= $notifications['smtp_port'] ?>" />
            </div>
            <div class="form-group-inline">
                <input type="text" name="smtpusername" id="smtpusername" placeholder="<?= translate('smtp_username', $i18n) ?>"  value="<?= $notifications['smtp_username'] ?>" />
            </div>
            <div class="form-group-inline">
                <input type="password" name="smtppassword" id="smtppassword" placeholder="<?= translate('smtp_password', $i18n) ?>"  value="<?= $notifications['smtp_password'] ?>" />
            </div>
            <div class="form-group-inline">
                <input type="text" name="fromemail" id="fromemail" placeholder="<?= translate('from_email', $i18n) ?>"  value="<?= $notifications['from_email'] ?>" />
            </div>
            <div class="settings-notes">
                <p>
                    <i class="fa-solid fa-circle-info"></i> <?= translate('smtp_info', $i18n) ?></p>
                <p>
            </div>
            <div class="buttons">
                <input type="button" class="secondary-button" value="<?= translate('test', $i18n) ?>" id="testNotifications" onClick="testNotificationButton()"/>
                <input type="submit" value="<?= translate('save', $i18n) ?>" id="saveNotifications" onClick="saveNotificationsButton()"/>
            </div>
        </div>
    </section>

    <?php
        $sql = "SELECT * FROM categories";
        $result = $db->query($sql);

        if ($result) {
            $categories = array();
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $categories[] = $row;
            }
        }
    ?>

    <section class="account-section">
        <header>
            <h2><?= translate('categories', $i18n) ?></h2>
        </header>
        <div class="account-categories">
            <div  id="categories">
            <?php
                foreach ($categories as $category) {
                    if ($category['id'] != 1) {
                        $canDelete = true;

                        $query = "SELECT COUNT(*) as count FROM subscriptions WHERE category_id = :categoryId";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':categoryId', $category['id'], SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $row = $result->fetchArray(SQLITE3_ASSOC);
                        $isUsed = $row['count'];

                        if ($isUsed > 0) {
                            $canDelete = false;
                        }
                    ?>
                    <div class="form-group-inline" data-categoryid="<?= $category['id'] ?>">
                        <input type="text" name="category" value="<?= $category['name'] ?>" placeholder="Category">
                        <button class="image-button medium"  onClick="editCategory(<?= $category['id'] ?>)" name="save">
                            <img src="images/siteicons/save.png" title="<?= translate('save_category', $i18n) ?>">
                        </button>
                        <?php
                            if ($canDelete) {
                            ?>
                                <button class="image-button medium" onClick="removeCategory(<?= $category['id'] ?>)">
                                    <img src="images/siteicons/delete.png" title="<?= translate('delete_category', $i18n) ?>">
                                </button>
                            <?php
                            } else {
                            ?>
                                <button class="image-button medium disabled">
                                    <img src="images/siteicons/delete.png" title="<?= translate('cant_delete_category_in_use', $i18n) ?>">
                                </button>
                            <?php
                            }
                        ?>
                    </div>
                    <?php
                    }
                }
            ?>
            </div>
            <div class="buttons">
                <input type="submit" value="<?= translate('add', $i18n) ?>" id="addCategory" onClick="addCategoryButton()"/>
            </div>
        </div>
    </section>

    <?php
        $sql = "SELECT * FROM payment_methods";
        $result = $db->query($sql);

        if ($result) {
            $payments = array();
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $payments[] = $row;
            }
        }
    ?>

    <?php
        $sql = "SELECT * FROM currencies";
        $result = $db->query($sql);

        if ($result) {
            $currencies = array();
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $currencies[] = $row;
            }
        }

        $query = "SELECT main_currency FROM user WHERE id = 1";
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $mainCurrencyId = $row['main_currency'];

        $query = "SELECT date FROM last_exchange_update";
        $exchange_rates_last_updated = $db->querySingle($query);

    ?>

    <section class="account-section">
        <header>
            <h2><?= translate('currencies', $i18n) ?></h2>
        </header>
        <div class="account-currencies">
            <div id="currencies">
            <?php
                foreach ($currencies as $currency) {
                    $canDelete = true;
                    $isMainCurrency = false;
                    if ($currency['id'] === $mainCurrencyId) {
                        $canDelete = false;
                        $isMainCurrency = true;
                    } else {
                        $query = "SELECT COUNT(*) as count FROM subscriptions WHERE currency_id = :currencyId";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':currencyId', $currency['id'], SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $row = $result->fetchArray(SQLITE3_ASSOC);
                        $isUsed = $row['count'];

                        if ($isUsed > 0) {
                            $canDelete = false;
                        }
                    }
                    ?>

                    <div class="form-group-inline" data-currencyid="<?= $currency['id'] ?>">
                        <input type="text" class="short" name="symbol" value="<?= $currency['symbol'] ?>" placeholder="$">
                        <input type="text" name="currency" value="<?= $currency['name'] ?>" placeholder="Currency Name">
                        <input type="text" name="code" value="<?= $currency['code'] ?>" placeholder="Currency Code">
                        <button class="image-button medium"  onClick="editCurrency(<?= $currency['id'] ?>)" name="save">
                            <img src="images/siteicons/save.png" title="<?= translate('save_currency', $i18n) ?>">
                        </button>
                        <?php
                            if ($canDelete) {
                            ?>
                                <button class="image-button medium" onClick="removeCurrency(<?= $currency['id'] ?>)">
                                    <img src="images/siteicons/delete.png" title="<?= translate('delete_currency', $i18n) ?>">
                                </button>
                            <?php
                            } else {
                                $cantDeleteMessage = $isMainCurrency ? translate('cant_delete_main_currency', $i18n) : translate('cant_delete_currency_in_use', $i18n);
                            ?>
                                <button class="image-button medium disabled">
                                    <img src="images/siteicons/delete.png" title="<?= $cantDeleteMessage ?>">
                                </button>
                            <?php
                            }
                        ?>
                        
                    </div>
                    <?php
                }
            ?>
            </div>
            <div class="buttons">
                <input type="submit" value="<?= translate('add', $i18n) ?>" id="addCurrency" onClick="addCurrencyButton()"/>
            </div>
            <div class="settings-notes">
                <p>
                    <i class="fa-solid fa-circle-info"></i>
                    <?= translate('exchange_update', $i18n) ?>
                    <span>
                        <?= $exchange_rates_last_updated ?>
                    </span>
                </p>
                <p>
                    <i class="fa-solid fa-circle-info"></i>
                    <?= translate('currency_info', $i18n) ?>
                    <span>
                        fixer.io 
                        <a href="https://fixer.io/symbols" target="_blank" title="Currency codes">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </span>
                </p>
                <p>
                    <?= translate('currency_performance', $i18n) ?>
                </p>
            </div>
        </div>
    </section>

    <?php
        $apiKey = "";
        $sql = "SELECT api_key FROM fixer";
        $result = $db->query($sql);
        if ($result) {
            $row = $result->fetchArray(SQLITE3_ASSOC);
            if ($row) {
                $apiKey = $row['api_key'];
            }
        }
    ?>

    <section class="account-section">
        <header>
            <h2>Fixer API Key</h2>
        </header>
        <div class="account-fixer">
            <div class="form-group">
                <input type="text" name="fixer-key" id="fixerKey" value="<?= $apiKey ?>" placeholder="<?= translate('api_key', $i18n) ?>">
            </div>
            <div class="settings-notes">
                <p><i class="fa-solid fa-circle-info"></i><?= translate('fixer_info', $i18n) ?></p>
                <p><?= translate('get_key', $i18n) ?>: 
                    <span>
                        https://fixer.io/ 
                        <a href="https://fixer.io/#pricing_plan" title="Get free fixer api key" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </span>
                </p>    
            </div>
            <div class="buttons">
                <input type="submit" value="<?= translate('save', $i18n) ?>" id="addFixerKey" onClick="addFixerKeyButton()"/>
            </div>
        </div>
    </section>    

    <section class="account-section">
        <header>
            <h2><?= translate('display_settings', $i18n) ?></h2>
        </header>
        <div class="account-settings-list">
            <div>
                <input type="button" value="<?= translate('switch_theme', $i18n) ?>" onClick="switchTheme()">
            </div>
            <?php
                $monthlyprice = isset($_COOKIE['showMonthlyPrice']) && $_COOKIE['showMonthlyPrice'] === 'true';
                $convertcurrency = isset($_COOKIE['convertCurrency']) && $_COOKIE['convertCurrency'] === 'true';
                $removebackground = isset($_COOKIE['removeBackground']) && $_COOKIE['removeBackground'] === 'true';
            ?>
            <div>
                <div class="form-group-inline">
                    <input type="checkbox" id="monthlyprice" name="monthlyprice" onChange="setShowMonthlyPriceCookie()" <?php if ($monthlyprice) echo 'checked'; ?>>
                    <label for="monthlyprice"><?= translate('calculate_monthly_price', $i18n) ?></label>
                </div>
            </div>
            <div>
                <div class="form-group-inline">
                    <input type="checkbox" id="convertcurrency" name="convertcurrency" onChange="setConvertCurrencyCookie()" <?php if ($convertcurrency) echo 'checked'; ?>>
                    <label for="convertcurrency"><?= translate('convert_prices', $i18n) ?></label>
                </div>
            </div>
        </div>
    </section>    

    <section class="account-section">
        <header>
            <h2><?= translate('experimental_settings', $i18n) ?></h2>
        </header>
        <div class="account-settings-list">
            <div>
                <div class="form-group-inline">
                    <input type="checkbox" id="removebackground" name="removebackground" onChange="setRemoveBackgroundCookie()" <?php if ($removebackground) echo 'checked'; ?>>
                    <label for="removebackground"><?= translate('remove_background', $i18n) ?></label>
                </div>
            </div>
        </div>
        <div class="settings-notes">
            <p>
                <i class="fa-solid fa-circle-info"></i>
                <?= translate('experimental_info', $i18n) ?>
            </p>
        </div>
    </section>    

    <section class="account-section">
        <header>
            <h2><?= translate('payment_methods', $i18n) ?></h2>
        </header>
        <div class="payments-list">
            <?php
                $paymentsInUseQuery = $db->query('SELECT id FROM payment_methods WHERE id IN (SELECT DISTINCT payment_method_id FROM subscriptions)');
                $paymentsInUse = [];
                while ($row = $paymentsInUseQuery->fetchArray(SQLITE3_ASSOC)) {
                    $paymentsInUse[] = $row['id'];
                }

                foreach ($payments as $payment) {
                    $inUse = in_array($payment['id'], $paymentsInUse);
                    ?>
                        <div class="payments-payment"
                             data-enabled="<?= $payment['enabled']; ?>"
                             data-in-use="<?= $inUse ? 'yes' : 'no' ?>"
                             data-paymentid="<?= $payment['id'] ?>"
                             title="<?= $inUse ? translate('cant_delete_payment_method_in_use', $i18n) : ($payment['enabled'] ? translate('disable', $i18n) : translate('enable', $i18n)) ?>"
                             onClick="togglePayment(<?= $payment['id'] ?>)">
                            <img src="images/uploads/icons/<?= $payment['icon'] ?>"  alt="Logo" />
                            <span class="payment-name">
                                <?= $payment['name'] ?>
                            </span>
                        </div>
                    <?php
                } 
            ?>
        </div>
        <div class="settings-notes">
            <p>
                <i class="fa-solid fa-circle-info"></i>
                <?= translate('payment_methods_info', $i18n) ?>
            </p>
        </div>
    </section>

</section>
<script src="scripts/settings.js?<?= $version ?>"></script>

<?php
    require_once 'includes/footer.php';
?>