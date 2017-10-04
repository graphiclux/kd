<?php
    $pageURL = get_admin_url(null, 'admin.php?page=WordPressMagicScroll-shortcodes-page');

    $magic = magictoolbox_WordPress_MagicScroll_get_data();
    $shortcode_img_url = preg_replace('/\/magicscroll\/constructor_magicscroll\/view\//is', '/magicscroll/', plugin_dir_url( __FILE__ ));
    $shortcode_img_url .= 'core/admin_graphics/icon.png';
?>
<div class="list-container">
    <div class="loader"><div class="magictoolbox-loader"></div></div>
    <h1>Magic Scroll shortcodes</h1>
    <p>
        Create Magic Scroll below, to insert with the <img src="<?php echo $shortcode_img_url; ?>" alt="Magic Scroll" style="vertical-align: middle;"/> shortcut into any page or post.
    </p>
    <p style="margin-right:20px; float:right; font-size:15px; white-space: nowrap;">
        &nbsp;<a href="<?php echo WordPressMagicScroll_url('http://www.magictoolbox.com/magicscroll/modules/wordpress/',' configuration page resources settings link'); ?>" target="_blank">Documentation<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;|
        &nbsp;<a href="<?php echo WordPressMagicScroll_url('http://www.magictoolbox.com/magicscroll/examples/',' configuration page resources examples link'); ?>" target="_blank">Examples<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;|
        &nbsp;<a href="<?php echo WordPressMagicScroll_url('http://www.magictoolbox.com/contact/','configuration page resources support link'); ?>" target="_blank">Support<span class="dashicons dashicons-share-alt2" style="text-decoration: none;line-height:1.3;margin-left:5px;"></span></a>&nbsp;
    </p><br/>
    <?php
        if (count($magic)) {
    ?>
    <button style="margin-right: 5px; margin-bottom: 5px; <?php if (!count($magic)) { echo 'display: none;'; } ?>" class="button delete-selected">Delete selected</button>
    <?php
        }
    ?>
    <a style="margin-right: 5px; margin-bottom: 5px;" class="button button-primary new-tool" href="<?php echo $pageURL;?>&id=new">Add shortcode</a>
    <table class="shortcodes-list" cellspacing="0" cellpadding="0">
        <thead>
            <tr <?php if (!count($magic)) { echo 'style="display: none;"'; } ?>>
                <td class="t-cb"><input type="checkbox"></td>
                <td class="t-id">ID</td>
                <td class="t-pv" style="width:auto;">Preview</td>
                <td class="t-name" style="width:50%">Name</td>
                <td class="t-sc" style="width:50%">Shortcode</td>
            </tr>
        </thead>
        <tbody>
            <?php
                if (count($magic)) {
                    foreach($magic as $val) {
                        $image = $val->thumbnails;
                        $image = explode(",", $image);
                        $image = $image[0];
                        $url = wp_get_attachment_url( $image );
                        $toolId = $val->id;
            ?>
                <tr id="<?php echo $toolId; ?>">
                    <td class="t-cb"><input type="checkbox"></td>
                    <td class="t-id"><?php echo $toolId; ?></td>
                    <td class="t-pv"><img src="<?php echo $url; ?>" /></td>
                    <td class="t-name">
                        <a href="<?php echo $pageURL;?>&id=<?php echo $toolId; ?>"><?php echo $val->name; ?></a></br>
                        <a href="<?php echo $pageURL;?>&id=<?php echo $toolId; ?>">Edit</a> |
                        <a href="#" class="copy-tool" title="Copy shortcode">Duplicate shortcode</button>
                    </td>
                    <?php
                        $sc = $val->shortcode;
                        if (empty($sc)) {
                            $sc = $val->id;
                        }
                    ?>
                    <td class="t-sc">[magicscroll id="<?php echo $sc; ?>"]</td>
                </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>
    <div id="down-buttons">
        <button style="margin-right: 5px; margin-top: 5px; <?php if (0 == count($magic)) { echo 'display: none;'; } ?>" class="button delete-selected">Delete selected</button>
        <a style="margin-right: 5px; margin-top: 5px; <?php if (0 == count($magic)) { echo 'display: none;'; } ?>" class="button button-primary new-tool" href="<?php echo $pageURL;?>&id=new">Add shortcode</a>
    </div>
</div>
