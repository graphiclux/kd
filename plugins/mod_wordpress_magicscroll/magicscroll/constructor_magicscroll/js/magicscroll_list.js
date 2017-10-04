(function($) {
    var removeButtonNodes,
        copyNodes,
        checkBoxNodes,
        newToolNodes,
        linkNodes,
        lockFlag = false;

    function Modal_Window(_content, callback) {
        var wrapper = $('<div>'),
            content = $('<div>'),
            ok = $('<button>'),
            no = $('<button>');

        ok.addClass('button button-primary').text('Yes');
        no.addClass('button').text('No');

        wrapper.addClass('mt-modal-window');
        wrapper.append($('<div>').addClass('curtain').on('click', function() { wrapper.remove(); }));

        content.addClass('modal-content')
            .html('<div class="mt-modal-content">' + _content + '</div>')
            .append(
                $('<div>')
                    .addClass('mt-modal-controls')
                    .append(ok)
                    .append(no)
            );

        $.each([ok, no], function(i, b) {
            $(b).on('click', function(e) {
                wrapper.remove();
                callback($(this).text() == 'Yes');
            });
        });

        wrapper.append(content);
        wrapper.appendTo($(document.body));
    }

    function getToolId(node) {
        var result = null;
        node = $(node);

        while(node[0].tagName.toLowerCase() !== 'tr') {
            node = node.parent();
        }

        result = node.attr('id');

        return result;
    }

    function getTr(arr) {
        var tr = [];
        $.each(arr, function(i, id) {
            tr.push($('tr[id="' + id + '"]')[0]);
        });
        return tr;
    }

    function lock(arr, unlock) {
        var nodes = getTr(arr);

        lockFlag = !unlock;
        $.each(getTr(arr), function(i, el) {
            if (unlock) {
                $(el).removeClass('lock')
                    .find('a').not('.copy-tool').off('click', function(e) { return false; });
            } else {
                $(el).addClass('lock')
                    .find('a').not('.copy-tool').on('click', function(e) { return false; });
            }
        });
        removeButtonNodes.prop('disabled', lockFlag);
        checkBoxNodes.prop('disabled', lockFlag);
        newToolNodes.prop('disabled', lockFlag);
    }

    function removeElements(arr) {
        $.each(getTr(arr), function(i, el) {
            $(el).remove();
        });
    }

    function removeSelectedTools(e) {
        var tools = [];

        $.each($('tbody input[type="checkbox"]'), function(i, el) {
            el = $(el);
            if (el.prop("checked")) {
                tools.push(parseInt(el.parent().parent().attr('id')));
            }
        });

        if (!tools.length) { return; }

        new Modal_Window('Are you sure you want to delete ' + tools.length + ' tool' + (tools.length > 1 ? 's' : '') + '?', function(isOk) {
            if (isOk) {
                removeButtonNodes.addClass('loading');
                removeButtonNodes.prop('disabled', true);
                lock(tools);
                $.post(magictoolbox_WordPress_MagicScroll_admin_modal_object.ajax, {
                    action: "WordPress_MagicScroll_remove_tools",
                    nonce: magictoolbox_WordPress_MagicScroll_admin_modal_object.nonce,
                    ids: tools
                })
                .success(function(data) {
                    data = JSON.parse(data);
                    if (!data.error) {
                        removeElements(tools);
                        removeButtonNodes.removeClass('loading');
                        removeButtonNodes.prop('disabled', false);
                        if (!$('tbody tr').length) {
                            $('thead tr').css('display', 'none');
                            $('#down-buttons').css('display', 'none');
                        }
                        lock(tools, true);
                    } else {
                        console.log(data.error);
                        lock(tools, true);
                    }
                })
                .error(function(e) {
                    console.log(e);
                    lock(tools, true);
                    removeButtonNodes.removeClass('loading');
                    removeButtonNodes.prop('disabled', false);
                });
            }
        });
    }

    function copy(e) {
        var self = this;
        e.preventDefault();
        e.stopPropagation();

        if (lockFlag) { return; }

        $.post(magictoolbox_WordPress_MagicScroll_admin_modal_object.ajax, {
            action: "WordPress_MagicScroll_copy_tool",
            nonce : magictoolbox_WordPress_MagicScroll_admin_modal_object.nonce,
            id    : getToolId(self)
        })
        .success(function(_data) {
            _data = JSON.parse(_data);

            var href = window.location.href;
            href = href.split('?')[0];
            href = href.split('wp-admin')[0];
            href = href + 'wp-admin/admin.php?page=WordPressMagicScroll-shortcodes-page&id='+_data.id;
            window.location.href = href;
        })
        .error(function() { });
    }

    function setEvents() {
        $('thead input[type="checkbox"]').on('change', function(e) {
            $('tbody input[type="checkbox"]').prop("checked", $(this).prop("checked"));
        });

        removeButtonNodes.on('click', removeSelectedTools);
        copyNodes.on('click', copy);
    }

    $(document).ready(function() {
        removeButtonNodes = $('.delete-selected');
        copyNodes = $('.copy-tool');
        checkBoxNodes = $('table').find('input[type="checkbox"]');
        newToolNodes = $('.new-tool');
        linkNodes = $('td.t-name a');

        setEvents();
    });
})(jQuery);
