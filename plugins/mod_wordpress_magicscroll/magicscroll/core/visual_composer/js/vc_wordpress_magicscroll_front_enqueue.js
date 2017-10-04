(function($) {
    window.InlineShortcodeView_vc_wordpress_magicscroll_shortcode = window.InlineShortcodeView.extend({
        render: function () {
            var name, tool, toolEl, el;
            window.InlineShortcodeView_vc_wordpress_magicscroll_shortcode.__super__.render.call(this);

            name = 'MagicScroll';

            el = this.$el;
            vc.frame_window.vc_iframe.addActivity(function() {
                tool = this[name];
                if (tool) {
                    toolEl = $(el).find('.' + name)[0];
                    if (toolEl) {
                        setTimeout(function() {
                            // tool.stop(toolEl);
                            tool.start(toolEl);
                            // tool.refresh(toolEl);
                        }, 500);
                    }
                }
            });

            return this;
        },

        updated: function () {
            window.InlineShortcodeView_vc_wordpress_magicscroll_shortcode.__super__.updated.call(this);
        },

        parentChanged: function () {
            var name, tool, toolEl;
            window.InlineShortcodeView_vc_wordpress_magicscroll_shortcode.__super__.parentChanged.call(this);

            name = 'MagicScroll';

            tool = vc.frame_window[name];

            if (tool) {
                toolEl = $(this.$el).find('.' + name)[0];
                if (toolEl) {
                    if (tool.refresh) {
                        tool.refresh(toolEl);
                    } else {
                        tool.stop(toolEl);
                        tool.start(toolEl);
                    }
                }
            }
        },

        remove: function () {
            var name, tool, toolEl;
            name = 'MagicScroll';

            tool = vc.frame_window[name];

            if (tool) {
                toolEl = $(this.$el).find('.' + name)[0];
                if (toolEl) {
                    tool.stop(toolEl);
                }
            }

            window.InlineShortcodeView_vc_wordpress_magicscroll_shortcode.__super__.remove.call( this );

            return this;
        }
    });

})(window.jQuery);