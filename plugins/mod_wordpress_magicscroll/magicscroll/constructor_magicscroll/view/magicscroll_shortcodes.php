<?php
    function magictoolbox_WordPress_MagicScroll_get_select_option_value($value) {
        $v = $value;
        if ('tl' == $v) {
            $v = 'top-left';
        } elseif ('tr' == $v) {
            $v = 'top-right';
        } elseif ('bl' == $v) {
            $v = 'bottom-left';
        } elseif ('br' == $v) {
            $v = 'bottom-right';
        }

        return $v;
    }
    function magictoolbox_WordPress_MagicScroll_set_custom_options($defaultOpt, $customOpt) {
        foreach ($customOpt as $key => $value) {
            $defaultOpt[$key] = $value;
        }
        return $defaultOpt;
    }

    function magictoolbox_WordPress_MagicScroll_correct_mobile_options($o) {
        $opt = array();
        foreach($o as $key => $value) {
            $option = array();
            if (preg_match('/(.+)ForMobile/', $key, $option)) {
                $opt[$option[1]] = $value;
            } else if ('slideMobileEffect' == $key) {
                $opt[$key] = $value;
            }
        }
        return $opt;
    }

    function magictoolbox_WordPress_MagicScroll_to_string($o) {
        $str = '';
        foreach($o as $key => $value) {
            $str .= ($key.':'.$value.';');
        }
        return $str;
    }

    function magictoolbox_WordPress_MagicScroll_normalize_option($o) {
        $opt = array();
        foreach($o as $key => $value) {
            $opt[$key] = magictoolbox_WordPress_MagicScroll_get_option($value);
        }
        return $opt;
    }

    function magictoolbox_WordPress_MagicScroll_options_filter($o, $group) {
        $opt = array();
        foreach($o as $key => $value) {
            if ( $group === $value['scope']) {
                $opt[$key] = $value;
            }
            if ('module' == $value['scope'] && ('scroll-extra-styles' == $value['id'] || 'show-image-title' == $value['id'])) {
                $opt[$key] = $value;
            }

            if ('module' == $value['scope']) {
                if (in_array($value['id'], array('scroll-extra-styles', 'show-image-title'))) {
                    $opt[$key] = $value;
                }
                if ('background' == $value['id']) {
                    $opt[$key] = $value;
                }
            }
        }
        return $opt;
    }

    function magictoolbox_WordPress_MagicScroll_get_script_part($arr, $id) {
        $str = '{';
        $str .= ('id:'.$id.',');
        $str .= ('url:"'.wp_get_attachment_url($id).'",');
        $str .= ('title:"'.get_the_title($id).'",');
        $str .= ('alt:"'.get_post_meta($id, '_wp_attachment_image_alt', true).'",');
        if (count($arr)) {
            $str .= ('sizes: {');
            foreach ($arr as $key => $value) {
                if ('custom' == $key) continue;
                $url = wp_get_attachment_image_src($id, $key);
                $url = $url[0];
                $str .= ('"'.$key.'":{');
                $str .= ('url:"'.$url.'",');
                $str .= ('width:'.$value['width'].',');
                $str .= ('height:'.$value['height']);
                $str .= '},';
            }
            $str .= '}';
        }
        $str .= '}';
        return $str;
    }

    function magictoolbox_WordPress_MagicScroll_get_option_states($o, $co) {
        $opt = array();
        foreach ($o as $key => $value) {
            $el_state = empty($co[$key]) ? 'lock' : 'unlock';
            $opt[$key]['element_state'] = $el_state;
            $opt[$key]['button_state'] = ('lock' == $el_state) ? 'unlock' : 'lock';
        }
        return $opt;
    }

    function magictoolbox_WordPress_MagicScroll_get_correct_sizes_of_images($width, $height) {
        $result = '';
        $result .= ('('. $width);

        if ('0' == $height.'' || preg_match('/^9+$/', $height.'')) {
            $result .= ' width';
        } else {
            $result .= ('x'.$height);
        }
        $result .= ')';
        return $result;
    }

    function magictoolbox_WordPress_MagicScroll_sort_groups($opt, $groups) {
        $result = array();

        foreach ($groups as $value) {
            if (array_key_exists($value, $opt)) {
                $result[$value] = $opt[$value];
            }
        }

        foreach ($opt as $key => $value) {
            if (!array_key_exists($key, $groups)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    $tool = null;
    $toolId = null;
    $toolName = null;
    $toolShortcode = null;
    $toolImage = '';
    $toolThumbnails = '';
    $toolOptions = array();
    $toolMobileOptions = array();
    $toolAdditionalOptions = null;
    $script = '';

    $options = get_option("WordPressMagicScrollCoreSettings");
    $options = $options['default'];

    $mobileOptions = magictoolbox_WordPress_MagicScroll_options_filter($options, 'magicscroll-mobile');
    $options = magictoolbox_WordPress_MagicScroll_options_filter($options, 'magicscroll');

    $mobileOptions = magictoolbox_WordPress_MagicScroll_correct_mobile_options($mobileOptions);

    $optionValues = magictoolbox_WordPress_MagicScroll_normalize_option($options);
    $mobileOptionValues = magictoolbox_WordPress_MagicScroll_normalize_option($mobileOptions);

    $defaultOtpions = magictoolbox_WordPress_MagicScroll_to_string($optionValues);
    $defaultMobileOtpions = magictoolbox_WordPress_MagicScroll_to_string($mobileOptionValues);

    $defaultImageSize = 'medium';
    $defaultImageWidth = "400";
    $defaultImageHeight = "400";
    $thumbnailImageSize = 'thumbnail';
    $thumbnailImageWidth = "100";
    $thumbnailImageHeight = "100";
    $thumbnailPosition = "bottom";
    $wordpressImageSizes = magictoolbox_WordPress_MagicScroll_get_wordpress_image_sizes();
    $wordpressImageSizes['custom'] = array();
    $isUsingDefaultOptions = true;

    if (array_key_exists('id', $_GET) && 'new' != $_GET['id']) {
        $toolId = $_GET['id'];
        $tool = magictoolbox_WordPress_MagicScroll_get_data("id", $toolId);
        $tool = $tool[0];

        $toolName = $tool->name;
        $toolShortcode = $tool->shortcode;

        $toolImage = $tool->image;
        $toolThumbnails = $tool->thumbnails;

        $toolAdditionalOptions = magictoolbox_WordPress_MagicScroll_parse_option_from_string($tool->additional_options);

        $isUsingDefaultOptions = ('default' === $toolAdditionalOptions['settings']);

        if ('' != $tool->options) {
            $toolOptions = magictoolbox_WordPress_MagicScroll_parse_option_from_string($tool->options);
            $optionValues = magictoolbox_WordPress_MagicScroll_set_custom_options($optionValues, $toolOptions);
        }
        if ('' != $tool->mobile_options) {
            $toolMobileOptions = magictoolbox_WordPress_MagicScroll_parse_option_from_string($tool->mobile_options);
            $mobileOptionValues = magictoolbox_WordPress_MagicScroll_set_custom_options($mobileOptionValues, $toolMobileOptions);
        }

        $defaultImageSize = $toolAdditionalOptions['image_size'];
        $defaultImageWidth = $toolAdditionalOptions['image_width'];
        $defaultImageHeight = $toolAdditionalOptions['image_height'];
        $thumbnailImageSize = $toolAdditionalOptions['thumbnail_size'];
        $thumbnailImageWidth = $toolAdditionalOptions['thumbnail_width'];
        $thumbnailImageHeight = $toolAdditionalOptions['thumbnail_height'];
        $thumbnailPosition = $toolAdditionalOptions['thumbnails_position'];

        $script .= '<script>';
        $script .= 'var main_image = '.magictoolbox_WordPress_MagicScroll_get_script_part($wordpressImageSizes, (int)$toolImage).';';
        $tmp = explode(',', $toolThumbnails);
        $script .= 'var thumbnail_images = [';
        foreach ($tmp as $value) {
            if ('' != trim($value)) {
                $script .= magictoolbox_WordPress_MagicScroll_get_script_part($wordpressImageSizes, (int)$value);
                $script .= ',';
            }
        }
        $script .= '];';
        $script .= '</script>';
    }

    $states = magictoolbox_WordPress_MagicScroll_get_option_states($optionValues, $toolOptions);
    $mobileStates = magictoolbox_WordPress_MagicScroll_get_option_states($mobileOptionValues, $toolMobileOptions);
?>

<div class="magicscroll-tool shortcode-container"
     data-tool-id="<?php echo $toolId; ?>"
     data-tool-name="<?php echo $toolName; ?>"
     data-tool-shortcode="<?php echo $toolShortcode; ?>"
     data-default-options="<?php echo $defaultOtpions; ?>"
     data-default-mobile-options="<?php echo $defaultMobileOtpions; ?>">

    <?php echo $script; ?>

    <h1 style="display:<?php echo (null == $toolId) ? 'none' : 'block'; ?>;" id="exist-tool">Edit Magic Scroll ID #<span><?php echo $toolId; ?></span>, '<span><?php echo $toolName; ?></span>'</h1>
    <h1 style="display:<?php echo (null == $toolId) ? 'block' : 'none'; ?>;" id="new-tool">Add new Magic Scroll</h1>

    <div class="save-container">
        <button class="button save-button">Save</button>
        <button class="button button-primary save-and-close-button">Save and close</button>
        <button class="button close-button">Close</button>
    </div>

    <div class="horizontal-block">
        <div class="flex-container">
            <div class="title-of-field">
                <span>Name (short title to describe shortcode)<span class="important-field" title="Obligatory input field">*</span></span>
            </div>
            <div class="value-of-field">
                <input id="tool-name-param" type="text" value="" placeholder="e.g. iPhone 6S">
                <span class="mt-error" id="name-error">Name is not specified!</span>
            </div>
        </div>
    </div>

    <div class="horizontal-block">
        <div class="flex-container">
            <div class="title-of-field">
                <span>Custom shortcode string</span>
            </div>
            <div class="value-of-field">
                <span><input id="tool-shortcode-param" type="text" value=""><div class="mt-loader"></div></span>
                <span class="mt-error" id="shortcode-error">Shortcode name must be maximum 50 characters, with no spaces. It can only contain letters, numbers, underscores and dashes.</span>
                <span class="mt-error" id="shortcode-is-not-unique">Is not unique.</span>
                <div class="">(optional, if you want to use a custom name instead of a number)</div>
            </div>
        </div>
    </div>


    <div class="horizontal-block">
        <div class="flex-container">
            <div class="title-of-field">
                <span>Scroll image size</span>
            </div>
            <div class="value-of-field">
                <select id="thumbnail-img-size-param" style="vertical-align: top;">
                    <?php foreach($wordpressImageSizes as $wpImgSize => $value) { ?>
                    <option <?php echo $wpImgSize == $thumbnailImageSize ? 'selected' : ''; ?> value="<?php echo $wpImgSize; ?>"><?php echo $wpImgSize; ?><?php echo count($value) ? (' '.magictoolbox_WordPress_MagicScroll_get_correct_sizes_of_images($value['width'], $value['height'])) : ''; ?></option>
                    <?php } ?>
                </select>
                <div style="display: inline-block; margin-left: 20px;">
                    <div style="display: <?php echo 'custom' == $thumbnailImageSize ? 'inline-block' : 'none'; ?>;">
                        <input id="thumbnail-image-width-param" min="1" type="number" value="<?php echo $thumbnailImageWidth; ?>" placeholder="width (px)" title="Max width"><br/>
                        <span>max width (px)</span>
                    </div>
                    <div style="display: <?php echo 'custom' == $thumbnailImageSize ? 'inline-block' : 'none'; ?>;">
                        <input id="thumbnail-image-height-param" min="1" type="number" value="<?php echo $thumbnailImageHeight; ?>" placeholder="height (px)" title="Max height"><br/>
                        <span>max height (px)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="horizontal-block">
        <div class="flex-container">
            <div class="images-container">
                <h2>Images</h2>
                <div class="thumbnails-container-wrapper">
                    <fieldset>
                        <legend>Gallery images</legend>
                        <div class="thumbnails-container empty" id="thumbnails">
                            <div class="curtain"></div>
                            <div class="thumbnails-controll-panel"><button type="button" class="button" id="remove-thumbnails-button">Remove all images</button></div>
                            <div class="thumbnails-image-wrapper"></div>
                        </div>
                        <button type="button" id="add-thumbnails-button" class="button">Add images</button>
                        <div class="mt-error" id="thumbnails-images">Please, choose images.</div>
                    </fieldset>
                </div>
            </div>
            <div class="preview-container">
                <h2>Preview</h2>
                <button type="button" id="refresh-button" class="button button-primary" style="display: none;">Refresh</button>
                <div class="tool-container"></div>
            </div>
        </div>
    </div>
    <div class="horizontal-block">
        <h2>Settings</h2>
        <div style="padding: 10px;">
            <label>
                <?php $checked = $isUsingDefaultOptions ? 'checked="checked"' : ''; ?>
                <input type="radio" id="use-def-opt" name="settings" <?php echo $checked; ?> value="default-options">Use default settings
            </label>
            <label>
                <?php $checked = !$isUsingDefaultOptions ? 'checked="checked"' : ''; ?>
                <input type="radio" id="use-cus-opt" name="settings" <?php echo $checked; ?> value="custom-options">Use custom settings &gt;
            </label>
        </div>
        <?php $checked = $isUsingDefaultOptions ? 'checked="checked"' : ''; ?>
        <div id="custom-options-container" <?php echo $isUsingDefaultOptions ? 'style="display: none;"' : 'style="display: block;"' ;?>>
            <div class="left-side">
            <?php
                $map = WordPressMagicScroll_getParamsMap();
                $map = $map['default'];


                foreach ($map as $key => $value) {
                    if ('General' == $key) { continue; }
                    if ('Watermark' == $key || 'Positioning and Geometry' == $key || 'Miscellaneous' == $key || 'WP Gallery Settings' == $key) { continue; }
                    $currentOptions = $options;
                    $currentOptionValues = $optionValues;
                    $currentStates = $states;
                    if ('Mobile' == $key) {
                        $currentOptions = $mobileOptions;
                        $currentOptionValues = $mobileOptionValues;
                        $currentStates = $mobileStates;
                    }
            ?>
                <fieldset>
                    <legend><?php echo $key; ?></legend>
                <div class="left-side-table">
                <table>
                <?php
                    for ($i = 0; $i < count($value); $i++) {
                        $param = $value[$i];
                        if (!isset($currentOptions[$param])) { continue; }
                ?>
                    <tr>
                        <td>
                            <b><?php echo $currentOptions[$param]['label']; ?></b>
                            <!-- <div title="<?php echo $param;?>"><?php echo $param; ?></div> -->
                        </td>
                        <td>
                            <?php if ('array' == $currentOptions[$param]['type']) {?>
                            <select name="<?php echo $param; ?>" data-state="<?php echo $currentStates[$param]['element_state']; ?>" <?php echo ('lock' == $currentStates[$param]['element_state']) ? 'disabled' : ''; ?> <?php echo ('Mobile' == $key) ? 'data-mobile="true"' : ''; ?>>
                                <?php foreach($currentOptions[$param]['values'] as $sel) {
                                    $paramValue = $sel;
                                ?>
                                <option value="<?php echo $paramValue; ?>" <?php echo ($sel == $currentOptionValues[$param]) ? 'selected' : ''; ?>><?php echo magictoolbox_WordPress_MagicScroll_get_select_option_value($sel); ?></option>
                                <?php } ?>
                            </select>
                            <?php } else { ?>
                            <input type="text" name="<?php echo $param; ?>" data-state="<?php echo $currentStates[$param]['element_state']; ?>" <?php echo ('lock' == $currentStates[$param]['element_state']) ? 'disabled' : ''; ?> value="<?php echo $currentOptionValues[$param]; ?>" <?php echo ('Mobile' == $key) ? 'data-mobile="true"' : ''; ?>>
                            <?php if ('scroll-extra-styles' == $param) { ?>
                            <div style="color: #bebebe;">mcs-rounded | mcs-shadows | bg-arrows | mcs-border</div>
                            <?php } ?>
                            <?php } ?>
                        </td>
                        <td><button type="button" title="<?php echo ucfirst($currentStates[$param]['button_state']); ?>" class="button dashicons dashicons-<?php echo $currentStates[$param]['button_state'] == 'lock' ? 'unlock' : 'lock'; ?>"></button></td>
                    </tr>
                    <?php if (round(count($value) / 2) - 1 == $i) { ?> </table></div><div class="right-side-table"><table> <?php } ?>
                <?php } ?>
                </table>
                </div>
                </fieldset>
            <?php  } ?>
            </div>
        </div>
    </div>
    <div class="save-container">
        <button class="button save-button">Save</button>
        <button class="button button-primary save-and-close-button">Save and close</button>
        <button class="button close-button">Close</button>
    </div>
</div>
