(function($) {
    var mi, ts, tool, options, newTool = true,
        globalOptions = '', globalMobileOptions = '', toolId,
        lockFlag = false,
        isCustomOptions = false,
        shortcodeAjax = null,
        shortcodeTimer = null,
        nameNode,
        shortcodeNode,
        defImgSizeNode,
        thumbImgSizeNode,
        saveButtonNodes,
        saveAndCloseButtonNodes,
        closeButtonNodes,
        lockButtonNodes,
        refrashButtonNode,
        d_iWidthNode, d_iHeightNode, t_iWidthNode, t_iHeightNode,
        thumbnailPositionNode,
        defaultRadio,
        customRadio,
        errorNodes = {};

    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function getName() {
        var n = nameNode.val();
        if ('' === $.trim(n)) {
            errorNodes.name.css('display', 'inline-block');
            nameNode.addClass('its-error');
            n = null;
        }
        return n;
    }

    function shortcodeIsValid(str) {
        if ('' === $.trim(str)) { return true; }
        return /^[A-Za-z][A-Za-z0-9_-]*$/.test(str);
    }

    function getShortCode() {
        var n = $.trim(shortcodeNode.val());

        if (!shortcodeIsValid(n)) {
            errorNodes.shortcode.css('display', 'inline-block');
            shortcodeNode.addClass('its-error');
            n = null;
        }

        return n;
    }

    function lock(unlock) {
        lockFlag = !unlock;

        $.each([
            nameNode,
            shortcodeNode,
            defImgSizeNode,
            thumbImgSizeNode,

            d_iWidthNode,
            d_iHeightNode,
            t_iWidthNode,
            t_iHeightNode,

            saveButtonNodes,
            saveAndCloseButtonNodes,

            defaultRadio,
            customRadio,

            refrashButtonNode
        ], function(i, n) {
            n.prop('disabled', lockFlag);
        });

        mi.lock();
        ts.lock();

        if (isCustomOptions) {
            options.lock();
        }
    }

    function getAdditionalOptions() {
        var result = '', size, w, h;

        size = defImgSizeNode.val();
        w = d_iWidthNode.val();
        h = d_iHeightNode.val();

        if ('custom' == size) {
            if (parseInt(w) <= 0 || parseInt(h) <= 0) {
                size = 'medium';
            }
        }

        result += ( 'image_size:' + size + ';' );
        result += ( 'image_width:' + w + ';' );
        result += ( 'image_height:' + h + ';' );


        size = thumbImgSizeNode.val();
        w = t_iWidthNode.val();
        h = t_iHeightNode.val();

        if ('custom' == size) {
            if (parseInt(w) <= 0 || parseInt(h) <= 0) {
                size = 'thumbnail';
            }
        }

        result += ( 'thumbnail_size:' + size + ';' );
        result += ( 'thumbnail_width:' + w + ';' );
        result += ( 'thumbnail_height:' + h + ';' );
        result += ( 'thumbnails_position:' + thumbnailPositionNode.val() + ';' );

        result += ( 'settings:' + (isCustomOptions ? 'custom' : 'default') + ';' );


        return result;
    }

    function parseOptions(str) {
        var result = {}, k, v;
        str = str.split(";");

        $.each(str, function(i, v) {
            var value = $.trim(v);
            if ('' !== value) {
                value = value.split(':');
                k = value.shift();
                v = value.join(':');
                // result[$.trim(value[0])] = $.trim(value[1]);
                result[$.trim(k)] = $.trim(v);
            }
        });
        return result;
    }

    function save(callback) {
        var _id, _name,
            _shortcode,
            _image, _thumbnails,
            _options, _mobileOptions,
            _additional_options;

        _name = getName();
        _shortcode = getShortCode();

        if (null === _name || null === _shortcode) {
            callback('parameters_are_invalid');
            return;
        }

        _image = '';
        _thumbnails = ts.getData();

        _id = toolId;
        _image += '';
        _options = options.getString(false, true);
        _mobileOptions = options.getString(true, true);
        _additional_options = getAdditionalOptions();

        if (!_thumbnails || '' == $.trim(_thumbnails)) {
            errorNodes.thumbnailsImages.css('display', 'block');
            callback('parameters_are_invalid');
            return;
        }

        saveButtonNodes.parent().addClass('loading');

        lock();

        $.post(magictoolbox_WordPress_MagicScroll_admin_modal_object.ajax, {
            action: "WordPress_MagicScroll_save",
            nonce: magictoolbox_WordPress_MagicScroll_admin_modal_object.nonce,
            id: _id,
            name: _name,
            shortcode: _shortcode,
            image: _image,
            thumbnails: _thumbnails,
            options: _options, // 'default' || json
            mobile_options: _mobileOptions, // 'default' || json
            additional_options: _additional_options
        })
        .success(function(_data) {
            _data = JSON.parse(_data);
            callback(null, _data);
        })
        .error(function(e) { callback(e); });
    }

    function Options() {
        this.container = $('#custom-options-container');
        this.selects = this.container.find('select').not('select[data-mobile="true"]');
        this.inputs = this.container.find('input').not('input[data-mobile="true"]');
        this.mobSelects = this.container.find('select[data-mobile="true"]');
        this.mobInputs = this.container.find('input[data-mobile="true"]');

        // this.setSelectEvents(this.selects);
        // this.setSelectEvents(this.mobSelects);
        // this.setInputEvents(this.inputs);
        // this.setInputEvents(this.mobInputs);
    }

    $.extend(Options.prototype, {
        changeState: function(state, parent) {
            var element = parent.find('input'), key, value;
            element.length || (element = parent.find('select'));

            if ('lock' === state) {
                key = element.attr('name');
                value = element.attr('data-mobile') === 'true' ? globalMobileOptions[key] : globalOptions[key];
                element.val(value);
            }

            $(element)
                .prop('disabled', 'lock' === state)
                .attr('data-state', state);
        },

        // setSelectEvents: function(selects) {
        //     selects.on('change', function() {
        //         tool.setTool();
        //     });
        // },
        //
        // setInputEvents: function(inputs) {
        //     inputs.on('keypress', function() {
        //         tool.setTool();
        //     });
        // },

        getJSON: function(isMobile, save) {
            var opt = {}, defOpt = isMobile ? globalMobileOptions : globalOptions,
                nodes = [this.selects, this.inputs],
                fixValue = function(value) {
                    if (/yes/i.test(value)) {
                        value = true;
                    } else if (/no/i.test(value)) {
                        value = false;
                    }
                    return value;
                };

            if (isMobile) {
                nodes = [this.mobSelects, this.mobInputs];
            }

            $.each(nodes, function(i, n) {
                $.each(n, function(i2, el) {
                    var name, state, _save = false;
                    el = $(el);
                    name = el.attr('name');
                    state = el.attr('data-state');
                    if (isCustomOptions && 'lock' !== state || _save) {
                        if (save) {
                            opt[name] = el.val();
                        } else {
                            opt[name] = fixValue(el.val());
                        }
                    } else if (!save) {
                        opt[name] = fixValue(defOpt[name]);
                    }
                });
            });

            return opt;
        },

        getString: function(isMobile, save, toolOnly) {
            var opt = '', separator = save ? '' : ' ';

            $.each(this.getJSON(isMobile, save), function(k, v) {
                var write = true;
                if (toolOnly) {
                    if ('background' === k) {
                        write = false;
                    }
                }
                if (write) {
                    opt += (k + ':' + separator + v + ';' + separator);
                }
            });
            return opt;
        },

        lock: function() {
            var _lock = function(arr) {
                $.each(arr, function(i, el) {
                    el = $(el);
                    if ('lock' !== el.attr('data-state')) {
                        el.prop('disabled', lockFlag);
                    }
                });
            };

            _lock(this.selects);
            _lock(this.inputs);
            _lock(this.mobSelects);
            _lock(this.mobInputs);
        }
    });

    function Tool() {
        var self = this;

        this.toolId = 'MagicScroll-constructor';
        this.container = $('.tool-container');

        refrashButtonNode.on('click', function(e) {
            self.setTool();
        });
    }

    $.extend(Tool.prototype, {
        clean: function() {
            var mTool = window['MagicScroll'];
            if (mTool) { mTool.stop(); }
            this.container[0].innerHTML = '';
        },
        createStyle: function(nameOfStyle, value) {
            var result = null;
            if (value && '' !== $.trim(value)) {
                result = "";
                result += "<style type='text/css'> ";
                result += ".MagicScroll { " + nameOfStyle + ": " + value + "; }";
                result += "</style>";
                result = $(result);
            }
            return result;
        },
        getScrollImgNodes: function(opt, callback) {
            var load, self = this,
                images = [], loaded = 0;

            if (ts) {
                ts.getUrls(function(err, result) {
                    var tNode;
                    if (err) {
                        callback(null);
                    } else {
                        $.each(result, function(i, thumb) {
                            if (opt['show-image-title']) {
                                tNode = $('<a>').attr('href', '#');
                                tNode.append($('<img>').attr('src', thumb.thumbnail).attr('alt', thumb.alt).attr('title', thumb.title));
                                tNode.append($('<span>').text(thumb.title))
                            } else {
                                tNode = $('<img>').attr('src', thumb.thumbnail).attr('alt', thumb.alt);
                            }
                            images.push(tNode);
                        });

                        callback(images);
                    }
                });
            } else {
                callback(null);
            }
        },

        setTool: function() {
            var self = this,
                mTool = window['MagicScroll'],
                opt = options.getJSON();

            refrashButtonNode.prop('disabled', true);
            refrashButtonNode.addClass('loading');

            this.getScrollImgNodes(opt, function(nodes) {
                var toolContainer, style;

                if (mTool) { mTool.stop(); }
                self.container[0].innerHTML = '';

                if (nodes.length) {
                    toolContainer = $('<div>');
                    toolContainer
                        .addClass('MagicScroll')
                        .attr('data-options', options.getString(false, false, true));

                    if ('' !== $.trim(opt['scroll-extra-styles'])) {
                        toolContainer.addClass($.trim(opt['scroll-extra-styles']));
                    }

                    $.each(nodes, function(i, n) {
                        toolContainer.append(n);
                    });

                    style = self.createStyle('background-color', opt['background']);

                    if (style) {
                        self.container.append(style);
                    }

                    self.container.append(toolContainer);
                    if (mTool) { mTool.start(); }
                }
            });

            refrashButtonNode.prop('disabled', false);
            refrashButtonNode.removeClass('loading');
        }
    });

    function ImageLoader(src, callback) {
        var container = $('<div>'),
            img = $('<img>', {}, {'max-width': 'none', 'max-height': 'none'});

        img.attr('src', src);

        container.css({
            'top': '-100000px',
            'left': '-100000px',
            'width': '10px',
            'height': '10px',
            'position': 'absolute',
            'overflow': 'hidden'
        }).append(img);

        img.on('load', function() {
            callback(null, { url: src, size: { width: img.width(), height: img.height()} });
            container.remove();
        });

        img.on('error', function() {
            callback(src);
            container.remove();
        });

        container.appendTo(document.body);
    }

    function MainImage() {
        var self = this,
            button = $('#main-image-button');

        this.node = $('#tool-image');
        this.imgId = null;
        this.url = null;
        this.isLoading = false;
        this.timer = false;
        this.imgData = null;
        this.button = button;
        this.img = null;
        this.addButton = $('#add-main-image-button');

        this.chooseImgEvent = function(e) {
            var wp_media;

            errorNodes.defImage.css('display', '');
            errorNodes.thumbnailsImages.css('display', '');
            errorNodes.loadImage.css('display', '');

            if (self.isLoading || lockFlag) { return; }

            wp_media = wp.media({
                title: 'Image for Magic Scroll',
                library: { type: 'image' },
                multiple: false,
                button: { text: 'Select' }
            });

            wp_media.on('select', function() { self.load(wp_media.state().get('selection').toJSON()[0]); });
            wp_media.open();
        };

        this.addButton.on('click', this.chooseImgEvent);

        button.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (lockFlag) { return; }
            self.imgId = null;
            self.url = null;
            self.imgData = null;
            if (self.img) {
                self.img.remove();
            }
            self.img = null;
            self.node.addClass('empty');
            if (ts) {
                // ts.bEnable(false);
                ts.setActive();
            }

            self.switchBtn();

            errorNodes.defImage.css('display', '');
            errorNodes.thumbnailsImages.css('display', '');
            errorNodes.loadImage.css('display', '');

            if (!ts.images.length) {
                refrashButtonNode.css('display', 'none');
            }

            tool.clean();
        });
    }

    $.extend(MainImage.prototype, {
        switchBtn: function(hide) {
            if (hide) {
                this.addButton.css('display', 'none');
                this.node.parent().on('click', this.chooseImgEvent);
            } else {
                this.addButton.css('display', '');
                this.node.parent().off('click', this.chooseImgEvent);
            }
        },
        load: function(img, callback) {
            var self = this, url;

            img || (img = window.main_image);

            if ('' === img.url) {
                if (callback) {
                    callback();
                }
                return;
            }

            this.node.removeClass('empty');

            this.isLoading = true;

            if (ts) {
                ts.bEnable();
            }

            if (this.url) {
                this.img.remove();
            }

            this.switchBtn(true);

            saveButtonNodes.prop('disabled', true);
            saveAndCloseButtonNodes.prop('disabled', true);

            this.timer = setTimeout(function() {
                clearTimeout(self.timer);
                self.node.addClass('loading');
            }, 250);

            url = img.sizes.medium ? img.sizes.medium.url : img.sizes.full.url;

            new ImageLoader(url, function(err, result) {
                self.isLoading = false;
                clearTimeout(self.timer);
                self.node.removeClass('loading');
                if (err) {
                    if (!self.url) {
                        self.node.addClass('empty');
                    }
                    errorNodes.loadImage.css('display', 'block');
                    // refrashButtonNode.css('display', 'none');
                } else {
                    self.imgData = img;
                    self.imgData.sizes['origin'] = { url: img.url };
                    self.imgId = img.id;
                    self.url = url;
                    self.img = $('<img>').attr('src', self.url);
                    self.node.append(self.img);
                    if (ts) {
                        ts.bEnable(true);
                        ts.setActive();
                    }
                }
                saveButtonNodes.prop('disabled', false);
                saveAndCloseButtonNodes.prop('disabled', false);
                refrashButtonNode.css('display', 'inline-block');
                // tool.setTool();
                if (callback) {
                    callback();
                }
            });
        },

        lock: function() {
            this.button.prop('disabled', lockFlag);
        },

        isLoaded: function(size) {
            return this.imgData.sizes[size]
        },

        _getUrlFromServer: function(id, size, callback) {
            var self = this,
                sizeName = $.isArray(size) ? (size[0] + 'x' + size[1]) : size;

            if (!this.imgData.sizes[sizeName]) {
                getUrlFromServer([{id: id, sizes: { medium: size }}], function(err, result) {
                    if (err) {
                        callback(err);
                    } else {
                        self.imgData.sizes[sizeName] = { url: result[0].medium};
                        result.origin = self.imgData.sizes[sizeName].origin;
                        callback(null, result);
                    }
                });
            } else {
                callback(null, [{
                    medium: this.imgData.sizes[sizeName].url,
                    origin: this.imgData.sizes[sizeName].origin
                }]);
            }
        },

        getUrl: function(callback) {
            var self = this, w, h,
                size = defImgSizeNode.val(),
                sizeName = size;

            if ( 'custom' === size ) {
                w = d_iWidthNode.val();
                h = d_iHeightNode.val();
                if (parseInt(w) > 0 && parseInt(h) > 0) {
                    size = [w, h];
                    sizeName = size[0] + 'x' + size[1];
                } else {
                    size = 'medium';
                    sizeName = size;
                }
            }

            if (this.imgData) {
                this._getUrlFromServer(this.imgData.id, size, function(err, arr) {
                    if (err) {
                        callback(err);
                    } else {
                        if (!self.imgData.sizes[sizeName].loaded) {
                            new ImageLoader(arr[0].medium, function(err, result) {
                                if (err) {
                                    callback(err);
                                } else {
                                    self.imgData.sizes[sizeName].loaded = true;
                                    callback(null, {
                                        alt:    self.imgData.alt,
                                        title:  self.imgData.title,
                                        medium: self.imgData.sizes[sizeName].url,
                                        origin: self.imgData.sizes.origin.url
                                    });
                                }
                            });
                        } else {
                            callback(null, {
                                alt:    self.imgData.alt,
                                title:  self.imgData.title,
                                medium: self.imgData.sizes[sizeName].url,
                                origin: self.imgData.sizes.origin.url
                            });
                        }
                    }
                });
            } else {
                callback(null, null);
            }
        }
    });

    function getUrlFromServer(arr, callback) {
        $.post(magictoolbox_WordPress_MagicScroll_admin_modal_object.ajax, {
            action: "WordPress_MagicScroll_get_img_urls",
            nonce: magictoolbox_WordPress_MagicScroll_admin_modal_object.nonce,
            ids: arr
        })
        .success(function(data) {
            data = JSON.parse(data);
            callback(data.error, data.urls);
        })
        .error(function(e) { callback(e); });
    }

    function Thumbnails() {
        var self = this;

        this.container = $('#thumbnails');
        this.addButton = $('#add-thumbnails-button');
        this.removeButton = $('#remove-thumbnails-button');
        this.imageWrapper = this.container.find('div.thumbnails-image-wrapper');
        this.images = [];

        this.ImageClass = function(imgData, size) {
            var self = this,
                img = $('<img>'),
                rb = $('<button>');

            imgData.sizes['origin'] = { url: imgData.url };

            this.isDefault = false;
            this.imgData = imgData;
            this.url = imgData.sizes.thumbnail.url;
            this.imgId = imgData.id;
            this.size = size;
            this.wrapper = $('<div>');
            this.radio = $('<div>').addClass('mt-radio').attr('title', 'Default image');
            this.imgCont = $('<div>').css({'position': 'relative'});

            rb.addClass('remove');

            this.wrapper.addClass('thumbnail-img');

            img
                .attr('src', this.url)
                .attr('data-id', this.imgId);

            this.imgCont
                .append(img)
                .append(rb);

            this.wrapper.append(this.imgCont);

            rb.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (lockFlag) { return; }
                $(self).trigger('remove-thumbnail', { id: self.imgId });
            });
        };


        this.addButton.on('click', function(e) {
            var wp_media;

            if (lockFlag) { return; }

            wp_media = wp.media({
                title: 'Thumbnail images',
                library: { type: 'image' },
                multiple: true,
                button: { text: 'Select' }
            });

            wp_media.on('select', function() { self.load(wp_media.state().get('selection').toJSON()); });
            wp_media.open();
        });

        this.removeButton.on('click', function(e) {
            if (lockFlag) { return; }
            self.removeImage();
            // tool.setTool();
        });
    }

    $.extend(Thumbnails.prototype, {
        getWidth: function() {
            var typeOfSize = thumbImgSizeNode.val();
            if ('custom' === typeOfSize) {
                return t_iWidthNode.val();
            } else {
                return this.images[0].imgData.sizes[typeOfSize].width;
            }
        },
        getImg: function(id) {
            for (var i = 0; i < this.images.length; i++) {
                if (this.images[i].imgData.id === id) {
                    return this.images[i];
                }
            }
            return null;
        },

        _getUrlFromServer: function(size, defSize, callback) {
            var self = this,
                thumbSizeName = $.isArray(size) ? (size[0] + 'x' + size[1]) : size,
                defSizeName = $.isArray(defSize) ? (defSize[0] + 'x' + defSize[1]) : defSize,
                data = [];

            $.each(this.images, function(i, img) {
                var _sizes = {};
                if (!img.imgData.sizes[thumbSizeName]) {
                    if (!img.imgData.sizes[thumbSizeName]) {
                        _sizes.thumbnail = size;
                    }
                    data.push({
                        id: img.imgData.id,
                        alt: img.imgData.alt,
                        title: img.imgData.title,
                        sizes: _sizes
                    });
                }
            });

            if (data.length) {
                getUrlFromServer(data, function(err, result) {
                    var arr = [];
                    if (err) {
                        callback(err);
                    } else {
                        $.each(result, function(i, img) {
                            var _img = self.getImg(parseInt(img.id));
                            if (img.thumbnail && '' !== $.trim(img.thumbnail)) {
                                _img.imgData.sizes[thumbSizeName] = { url: img.thumbnail };
                            }

                            if (img.medium && '' !== $.trim(img.medium)) {
                                _img.imgData.sizes[defSizeName] = { url: img.medium };
                            }
                        });

                        $.each(self.images, function(i, img) {
                            arr.push({
                                id: img.imgData.id,
                                alt: img.imgData.alt,
                                title: img.imgData.title,
                                thumbnail: img.imgData.sizes[thumbSizeName].url,
                                origin: img.imgData.sizes['origin'].url
                            })
                        });

                        callback(null, arr);
                    }
                });
            } else {
                var urls = [];
                $.each(this.images, function(i, img) {
                    if (img.imgData.sizes[thumbSizeName]) {
                        urls.push({
                            id: img.imgData.id,
                            alt: img.imgData.alt,
                            title: img.imgData.title,
                            thumbnail: img.imgData.sizes[thumbSizeName].url,
                            origin: img.imgData.sizes['origin'].url
                        });
                    }
                });

                callback(null, urls);
            }
        },

        getUrls: function(callback) {
            var self = this, w, h,
                defSize = defImgSizeNode.val(),
                defSizeName = size,
                size = thumbImgSizeNode.val(),
                sizeName = size;

            if (!this.images.length) {
                return callback(null, []);
            }

            if ( 'custom' === defSize ) {
                w = d_iWidthNode.val();
                h = d_iHeightNode.val();
                if (parseInt(w) > 0 && parseInt(h) > 0) {
                    defSize = [w, h];
                    defSizeName = defSize[0] + 'x' + defSize[1];
                } else {
                    defSize = 'medium';
                    defSizeName = defSize;
                }
            }

            if ( 'custom' === size ) {
                w = t_iWidthNode.val();
                h = t_iHeightNode.val();
                if (parseInt(w) > 0 && parseInt(h) > 0) {
                    size = [w, h];
                    sizeName = size[0] + 'x' + size[1];
                } else {
                    size = 'thumbnail';
                    sizeName = size;
                }
            }

            this._getUrlFromServer(size, defSize, function(err, arr) {
                if (err) {
                    callback(err);
                } else {
                    if (!self.images[0].imgData.sizes[sizeName].loaded) {
                        self.loadImages(arr, sizeName, function(imgArr) {
                            callback(null, imgArr);
                        });
                    } else {
                        callback(null, arr);
                    }
                }
            });
        },

        loadImages: function(images, sizeName, callback) {
            var self = this, arr = [];
            var load = function() {
                if (!images.length) {
                    callback(arr);
                    return;
                }
                var img = images.shift();

                new ImageLoader(img.thumbnail, function(err, result) {
                    if (!err) {
                        self.getImg(parseInt(img.id)).imgData.sizes[sizeName].loaded = true;
                        arr.push(img);
                    }
                    load();
                });
            };

            load();
        },

        load: function(imgs, callback) {
            var self = this,
                curtain = self.container.find('div.curtain');

            imgs || (imgs = window.thumbnail_images);

            var load = function() {
                var img = imgs.shift();

                if (img) {
                    self.addToContainer($.extend({}, img), function() {
                        curtain.css({'height': self.container.height() + 'px'});
                        load();
                    });
                } else {
                    self.stopLoading();
                    self.setActive();
                    // tool.setTool();
                    if (self.images.length) {
                        errorNodes.thumbnailsImages.css('display', '');
                        refrashButtonNode.css('display', 'inline-block');
                    }

                    if (callback) {
                        callback();
                    }
                }
            };

            if (imgs.length) {
                self.startLoading();
            }

            load();
        },

        lock: function() {
            this.removeButton.prop('disabled', lockFlag);
        },

        setActive: function() {
        },

        removeImage: function(id) {
            var i, index;

            if (!isNaN(parseInt(id))) {
                for (i = 0; i < this.images.length; i++) {
                    var _img = this.images[i];
                    index = null;

                    if (_img.imgId === id) {
                        index = i;
                        _img.wrapper.remove();
                        break;
                    }
                }

                if (null !== index) {
                    this.images.splice(index, 1);
                }
            } else {
                $.each(this.images, function(i, img) {
                    img.wrapper.remove();
                });
                this.images = [];
            }
            if (!this.images.length) {
                this.container.addClass('empty');
            }
            tool.clean();
        },

        bEnable: function(value) {
        },

        createNode: function(imgData, size) {
            var self = this;
            var image = new this.ImageClass(imgData, size);

            $(image).on('remove-thumbnail', function(e, data) {
                self.removeImage(data.id);
                // tool.setTool();
            });

            this.images.push(image);

            return image.wrapper;
        },

        checkImg: function(url) {
            for (var i = 0; i < this.images.length; i++) {
                if (this.images[i].url === url) {
                    return true;
                }
            }
            return false;
        },

        addToContainer: function(imageData, callback) {
            var self = this;

            if (this.checkImg(imageData.sizes.thumbnail.url)) {
                callback();
            } else {
                new ImageLoader(imageData.sizes.thumbnail.url, function(err, result) {
                    if (!err) {
                        self.imageWrapper.append(self.createNode(imageData, result.size));
                        self.container.removeClass('empty');
                    }
                    callback();
                });
            }
        },

        getData: function() {
            var self = this,
                str = '';

            $.each(this.images, function(i, img) {
                str += img.imgId;
                if (i !== self.images.length - 1) {
                    str += ',';
                }
            });

            return str;
        },

        startLoading: function() {
            saveButtonNodes.prop('disabled', true);
            saveAndCloseButtonNodes.prop('disabled', true);
            this.addButton.addClass('loading');
            this.bEnable(false);
            this.container.addClass('loading');
        },

        stopLoading: function() {
            saveButtonNodes.prop('disabled', false);
            saveAndCloseButtonNodes.prop('disabled', false);
            this.addButton.removeClass('loading');
            this.bEnable(true);
            this.container.removeClass('loading');
        }
    });

    function checkShortCode (id, shortcode, callback) {
        if (!toolId || '' === toolId) {
            toolId = 'null';
        }

        clearTimeout(shortcodeTimer);
        shortcodeTimer = setTimeout(function() {
            shortcodeNode.parent().addClass('loading');
        }, 300);

        shortcodeAjax = $.post(magictoolbox_WordPress_MagicScroll_admin_modal_object.ajax, {
            action: "WordPress_MagicScroll_check_shortcode",
            nonce: magictoolbox_WordPress_MagicScroll_admin_modal_object.nonce,
            id: id,
            shortcode: shortcode
        })
        .success(function(_data) {
            _data = JSON.parse(_data);

            if (callback) {
                callback(null, _data);
            }
            clearTimeout(shortcodeTimer);
        })
        .error(function(e) {
            if (callback) {
                callback(e);
            }
            clearTimeout(shortcodeTimer);
        });
    }

    function setEvents() {
        var optNode = $('#custom-options-container'),
            refrashTimer, last;

        nameNode.on('focus', function() {
            errorNodes.name.css('display', '');
            nameNode.removeClass('its-error');
            nameNode.removeClass('its-ok');
        });

        nameNode.on('blur', function() {
            if ('' === $.trim(nameNode.val())) {
                nameNode.addClass('its-error');
            } else {
                nameNode.addClass('its-ok');
            }
        });

        shortcodeNode.on('keyup', function(e) {
            if (shortcodeIsValid($.trim(shortcodeNode.val()))) {
                errorNodes.shortcode.css('display', '');
                errorNodes.notUnique.css('display', '');
            } else {
                errorNodes.shortcode.css('display', 'inline-block');
            }
        });

        shortcodeNode.on('focus', function() {
            last = $.trim(shortcodeNode.val());
            shortcodeNode.removeClass('its-error');
            shortcodeNode.removeClass('its-ok');
        });

        shortcodeNode.on('blur', function() {
            var v = $.trim(shortcodeNode.val());
            if (shortcodeIsValid(v)) {
                if (shortcodeAjax) {
                    shortcodeAjax.abort();
                }
                if ('' !== $.trim(v) && last !== v) {
                    saveButtonNodes.prop('disabled', true);
                    saveAndCloseButtonNodes.prop('disabled', true);
                    checkShortCode(toolId, v, function(err, result) {
                        if (err) {
                            if ('abort' !== err.statusText) {
                                shortcodeNode.addClass('its-error');
                                shortcodeNode.removeClass('its-ok');
                            }
                        } else {
                            switch(result.error) {
                                case 'verification_failed':
                                case 'not_unique':
                                    console.log('error: ' + result.error);
                                    shortcodeNode.addClass('its-error');
                                    shortcodeNode.removeClass('its-ok');
                                    errorNodes.shortcode.css('display', '');
                                    errorNodes.notUnique.css('display', ('not_unique' === result.error) ? 'inline-block' : '');
                                    break;
                                default:
                                    shortcodeNode.removeClass('its-error');
                                    shortcodeNode.addClass('its-ok');
                            }
                        }
                        shortcodeAjax = null;
                        if (!err || 'abort' !== err.statusText) {
                            saveButtonNodes.prop('disabled', false);
                            saveAndCloseButtonNodes.prop('disabled', false);
                        }
                        shortcodeNode.parent().removeClass('loading');
                    });
                }
            } else {
                errorNodes.shortcode.css('display', 'inline-block');
            }
        });

        defImgSizeNode.on('change', function(e) {
            var v = $(this).val();

            $.each([d_iWidthNode, d_iHeightNode], function(i, n) {
                n.parent().css('display', 'custom' === v ? 'inline-block' : 'none');
            });
            // tool.setTool();
        });

        thumbImgSizeNode.on('change', function(e) {
            var v = $(this).val();

            $.each([t_iWidthNode, t_iHeightNode], function(i, n) {
                n.parent().css('display', 'custom' === v ? 'inline-block' : 'none');
            });
            // tool.setTool();
        });

        defaultRadio.on('change', function() {
            isCustomOptions = false;
            optNode.css('display', 'none');
            // tool.setTool();
        });

        customRadio.on('change', function() {
            isCustomOptions = true;
            optNode.css('display', 'block');
            // tool.setTool();
        });

        isCustomOptions = ('custom-options' === jQuery("input[type='radio'][name='settings']:checked").val());

        saveButtonNodes.on('click', function(e) {
            save(function(err, data) {
                saveButtonNodes.parent().removeClass('loading');
                if (!err) {
                    if (isNaN(parseInt(toolId))) {
                        toolId = data.id;
                    }
                    $('#new-tool').css('display', 'none');
                    $.each($('#exist-tool').find('span'), function(i, el) {
                        el = $(el);
                        if (!i) {
                            el.html(toolId);
                        } else {
                            el.html(nameNode.val());
                        }
                    });
                    $('#exist-tool').css('display', 'block');

                    if (newTool) {
                        if (history.pushState) { history.pushState(null, null, '#' + toolId); }
                        else { location.hash = '#' + toolId; }
                    }
                }
                lock(true);
            });
        });

        saveAndCloseButtonNodes.on('click', function(e) {
            save(function(err) {
                if (err) {
                    lock(true);
                } else {
                    window.location.href = window.location.origin + window.location.pathname + window.location.search.split('&')[0];
                }
            });
        });

        closeButtonNodes.on('click', function(e) {
            window.location.href = window.location.origin + window.location.pathname + window.location.search.split('&')[0];
        });

        lockButtonNodes.on('click', function() {
            var isLock = $(this).hasClass('dashicons-lock'),
                elementState = isLock ? 'unlock' : 'lock',
                parent = $(this).parent().parent();

            if (!isLock) {
                $(this).removeClass('dashicons-unlock');
                $(this).addClass('dashicons-lock');
                $(this).attr('title', 'Unlock');
            } else {
                $(this).removeClass('dashicons-lock');
                $(this).addClass('dashicons-unlock');
                $(this).attr('title', 'Lock');
            }

            options.changeState(elementState, parent);
        });
    }

    $(document).ready(function() {
        var container = $(document.body).find('.shortcode-container');

        globalOptions       = parseOptions(container.attr('data-default-options'));
        globalMobileOptions = parseOptions(container.attr('data-default-mobile-options'));
        toolId              = container.attr('data-tool-id');

        nameNode                = $('#tool-name-param');
        shortcodeNode           = $('#tool-shortcode-param');
        defImgSizeNode          = $('#default-img-size-param');
        thumbImgSizeNode        = $('#thumbnail-img-size-param');
        saveButtonNodes         = $('.save-button');
        saveAndCloseButtonNodes = $('.save-and-close-button');
        closeButtonNodes        = $('.close-button');
        defaultRadio            = $('#use-def-opt');
        customRadio             = $('#use-cus-opt');
        lockButtonNodes         = $('#custom-options-container').find('button');
        refrashButtonNode       = $('#refresh-button');
        d_iWidthNode            = $('#default-image-width-param');
        d_iHeightNode           = $('#default-image-height-param');
        t_iWidthNode            = $('#thumbnail-image-width-param');
        t_iHeightNode           = $('#thumbnail-image-height-param');
        thumbnailPositionNode   = $('#thumbnail-position-param');

        errorNodes.name             = $('#name-error');
        errorNodes.shortcode        = $('#shortcode-error');
        errorNodes.notUnique        = $('#shortcode-is-not-unique');
        errorNodes.defImage         = $('#default-image');
        errorNodes.loadImage        = $('#loading-error');
        errorNodes.thumbnailsImages = $('#thumbnails-images');

        mi            = new MainImage();
        ts            = new Thumbnails();
        tool          = new Tool();
        options       = new Options();

        setEvents();

        if (toolId && 'new' !== toolId && window.main_image) {
            newTool = false;
            lock();
            nameNode.val(container.attr('data-tool-name') || '');
            nameNode.blur();
            shortcodeNode.val(container.attr('data-tool-shortcode') || '');
            shortcodeNode.blur();
            ts.load(null, function() {
                tool.setTool();
                lock(true);
            });
        }
    });
})(jQuery);
