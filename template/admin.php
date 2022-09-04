<?php

/*
    Admin Page
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function qiita2wp_add_admin_page() { 

    // SAVE
    if($options = qiita2wp_sanitize($_POST)) {
        update_option( 'qiita2wp_settings', $options );
        $error_message = qiita2wp_update();
    }

    // READ
    $options = get_option( 'qiita2wp_settings', [] );

?>
    
    <div class="wrap">
        <h1>Qiita2WP 設定</h1>
        <h2>基本設定</h2>
        <?php if(isset($error_message)):?>
            <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
                <p><strong><?php echo ($error_message ? $error_message : "保存 & 更新が正常に完了しました。");?></strong></p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする。</span></button>
            </div>
        <?php endif;?>
        <form method="post" novalidate="novalidate">
            <input type="hidden" id="_wpnonce" name="_wpnonce" value="03beed4ae2">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="your_token">個人用アクセストークン</label></th>
                        <td><input name="your_token" type="text" id="your_token" value="<?php echo $options['your_token'];?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cron_interval">自動更新間隔</label></th>
                        <td>
                            <select name="cron_interval" id="cron_interval" class="postform">
                                <?php 
                                    $cron_interval_list = [
                                        '0' => '自動更新しない',
                                        'hourly' => '1時間間隔',
                                        'twicedaily' => '12時間間隔',
                                        'daily' => '24時間間隔',
                                    ];
                                    foreach($cron_interval_list as $value => $label) echo '<option class="level-0" value="'.$value.'" '.($options['cron_interval'] === $value ? 'selected' : '').'>'.$label.'</option>';
                                ?>
                            </select>
                            <p class="description">
                                自動更新機能はwp_cronに依存しています。<br>
                                WordPressサイトのアクセスが少ない場合は、設定通りに自動更新されない場合があります。
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Qiita記事の保存先カテゴリ</th>
                        <td>
                            <select name="set_category" id="set_category" class="postform">
                            <?php 
                                $categories = array_column(get_categories(['hide_empty' => False]), 'cat_name', 'cat_ID');
                                echo "<option class=\"level-0\" value=\"0\">未設定</option>";
                                foreach($categories as $cat_ID => $cat_name) echo '<option class="level-0" value="'.$cat_ID.'" '.(($options['set_category'] == $cat_ID) ? 'selected' : '').'>'.$cat_name.'</option>';
                            ?>
                            </select>
                            <p class="description">事前に<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=category' );?>">カテゴリー設定</a>からQiita用のカテゴリを作成してください。</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="設定を保存 & 更新を実行"></p>
        </form>

        <h2>実行ログ（最大50件）</h2>
        <?php
            $log_array = get_option( 'qiita2wp_logs', [] );
            $str = '<textarea rows="10" style="width: 100%; background-color: #FFFFFF;" readonly>';
            if($log_array) {
                foreach(array_reverse($log_array) as $t => $m) $str .= "${m}（${t}）\n";
            }
            else $str .= 'ログはありません。';
            $str .= '</textarea>';
            echo $str;
        ?>
    </div>

<?php }