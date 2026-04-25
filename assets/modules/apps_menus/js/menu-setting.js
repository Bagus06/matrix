$(function() {
    var treeInstance = null;
    var selectedNodeId = null;
    let $menuTree = $('#menus-tree');
    $.select2('select[name="feature_code"]')


    // INIT JSTREE
    $(document).ready(function() {
        $.ajax({
            url: BASE_URL + 'apps_menus/menu_tree',
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                $.loader('hide')
                initJsTree(response.data)
            },
            error: function() {
                $.loader('hide')
            }
        });
    });

    function initJsTree(data) {
        $menuTree.jstree({
            core: {
                check_callback: function(operation, node, parent, position, more) {

                    if (operation === "delete_node") {
                        if (node.original.sys_lock) {
                            $.invyToastr({
                                type: 'warning',
                                message: 'This menu is locked and cannot be deleted.'
                            });
                            return false;
                        } else {
                            deleteMenu(node.original.id)
                        }
                    }

                    return true;
                },
                data: data
            },
            types: {
                root: {
                    icon: "fa fa-sitemap text-primary"
                },
                child: {
                    icon: "fa fa-link text-secondary"
                },
                default: {
                    icon: "fa fa-list"
                }
            },
            plugins: ["dnd", "contextmenu", "types", "unique", "wholerow", "search", "state"],
            dnd: {
                is_draggable: true,
                use_html5: true,
                check_while_dragging: true,
                inside_pos: "last",
                open_on_dnd: true
            },
            contextmenu: {
                items: function(node) {
                    var defaultMenu = $.jstree.defaults.contextmenu.items();
                    delete defaultMenu.rename;

                    if (defaultMenu.rename) defaultMenu.rename.icon = "fa fa-pen text-secondary";
                    if (defaultMenu.remove) defaultMenu.remove.icon = "fa fa-trash text-secondary";
                    if (defaultMenu.create) defaultMenu.create.icon = "fa fa-plus text-secondary";
                    if (defaultMenu.ccp) defaultMenu.ccp.icon = "fa-solid fa-pen-to-square text-secondary";
                    if (defaultMenu.ccp) {
                        if (defaultMenu.ccp.submenu.copy) defaultMenu.ccp.submenu.copy.icon = "fa fa-copy text-secondary";
                        if (defaultMenu.ccp.submenu.cut) defaultMenu.ccp.submenu.cut.icon = "fa fa-scissors text-secondary";
                        if (defaultMenu.ccp.submenu.paste) defaultMenu.ccp.submenu.paste.icon = "fa fa-paste text-secondary";
                    }
                    defaultMenu.advancedEdit = {
                        label: "Advanced Edit",
                        action: function() {
                            openEditModal(node);
                        },
                        icon: "fa fa-gear text-secondary"
                    };
                    return defaultMenu;
                }
            }
        });
    }

    /// GET INSTANCE & APPLY DEFAULT VISIBILITY STATE
    $menuTree.on('ready.jstree', function() {
        treeInstance = $menuTree.jstree(true);
        applyVisibilityState();
    });

    function applyVisibilityState() {
        var data = treeInstance.get_json('#', {
            flat: false
        });

        function loop(data) {
            $.each(data, function(i, n) {
                var node = treeInstance.get_node(n.id);
                node.data = node.data || {};

                var visible = (typeof node.original.visible !== 'undefined') ? node.original.visible : true;
                var status = (typeof node.original.menu_status !== 'undefined') ? node.original.menu_status : true;
                var sys_lock = (typeof node.original.sys_lock !== 'undefined') ? node.original.sys_lock : true;

                var baseTitle = node.original.display_title || node.text.replace(/ <span.*$/, '');
                node.original.display_title = baseTitle;

                // APPLY BADGE IF VISIBLE
                var badgeHtml = (visible) ?
                    " <span class='badge bg-success ms-1'>Visible</span>" :
                    " <span class='badge bg-danger ms-1'>Hidden</span>";

                // APPLY ICON IF LOCK
                var iconLock = (sys_lock) ?
                    " <i class='lock text-primary fa-solid fa-lock'></i>" :
                    "";
                treeInstance.rename_node(node, baseTitle + iconLock + badgeHtml);

                setTimeout(() => {
                    var anchor = $('#' + node.id + ' > a');
                    if (status) {
                        anchor.removeClass('text-muted');
                        anchor.find('i.jstree-themeicon').removeClass('text-muted');
                        anchor.find('span.badge').removeClass('bg-secondary').addClass('bg-success');
                    } else {
                        anchor.addClass('text-muted');
                        anchor.find('i.jstree-themeicon').addClass('text-muted');
                        anchor.find('i.lock').removeClass('text-primary').addClass('text-secondary');
                        if (anchor.find('span.badge').hasClass('bg-success')) {
                            anchor.find('span.badge').removeClass('bg-success').addClass('bg-secondary');
                        } else {
                            anchor.find('span.badge').removeClass('bg-danger').addClass('bg-secondary');
                        }
                    }
                }, 5);

                var baseIcon = node.original.icon || node.icon || null;
                node.original.icon = baseIcon;
                treeInstance.set_icon(node, baseIcon);

                if (n.children && n.children.length > 0) {
                    loop(n.children);
                }
            });
        }

        loop(data);
    }

    // OPEN EDIT MODAL
    function openEditModal(nodeRef) {
        selectedNodeId = nodeRef.id;
        var node = treeInstance.get_node(nodeRef);
        node.data = node.data || {};

        var feature = (typeof node.original.feature_code !== 'undefined') ? node.original.feature_code : false;
        var visible = (typeof node.original.visible !== 'undefined') ? node.original.visible : true;
        var status = (typeof node.original.menu_status !== 'undefined') ? node.original.menu_status : true;
        var sys_lock = (typeof node.original.sys_lock !== 'undefined') ? node.original.sys_lock : true;
        var baseTitle = node.original.display_title || node.text.replace(/ <span.*$/, '');

        $('input[name="display_title"]').val(baseTitle);
        $('input[name="icon"]').val(node.original.icon || '');
        $('textarea[name="description"]').val(node.original.description || '');
        $('textarea[name="description"]').text(node.original.description || '');
        $('input[name="url"]').val(node.original.url || '');

        $('select[name="feature_code"]').val(feature)
        $('select[name="feature_code"]').trigger('change')

        if (visible) {
            $('input[name="visible"]').prop('checked', true);
        } else {
            $('input[name="visible"]').prop('checked', false);
        }

        if (status) {
            $('input[name="status"]').prop('checked', true);
        } else {
            $('input[name="status"]').prop('checked', false);
        }

        if (sys_lock) {
            $('input[name="sys_lock"]').prop('checked', true);
        } else {
            $('input[name="sys_lock"]').prop('checked', false);
        }

        $('#editModal').modal('show');
    }

    // FUNCTION DELETE NODE
    function deleteMenu(id) {
        $.ajax({
            url: BASE_URL + 'apps_menus/delete/' + id,
            method: 'GET',
            dataType: 'json',
            async: true,
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                $.loader('hide')
                renderSidebarMenu()
                if (response.status) {
                    $.invyAlert({
                        title: 'DELETE',
                        text: 'Delete data successfully!',
                        icon: 'success',
                    })
                    return true;
                } else {
                    let errInfo = $.getErrorInfo(response.code)

                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                    return false;
                }
            },
            error: function() {
                return false;
                $.loader('hide')
            }
        });
    }

    // SAVE EDIT IN MODAL
    $('#btnSaveEdit').on('click', function() {
        if (!treeInstance || !selectedNodeId) {
            $('#editModal').modal('toggle');
            return;
        }

        var node = treeInstance.get_node(selectedNodeId);
        if (!node) {
            $('#editModal').modal('toggle');
            return;
        }

        var newTitle = $('input[name="display_title"]').val();
        var newIcon = $('input[name="icon"]').val();
        var newDescription = $('textarea[name="description"]').val();
        var newUrl = $('input[name="url"]').val();
        var newFeature = $('select[name="feature_code"]').val();
        var newVisible = $('input[name="visible"]').prop("checked");
        if (newVisible) {
            $('input[name="visible"]').prop('checked', true);
        } else {
            $('input[name="visible"]').prop('checked', false);
        }

        var newStatus = $('input[name="status"]').prop("checked");
        if (newStatus) {
            $('input[name="status"]').prop('checked', true);
        } else {
            $('input[name="status"]').prop('checked', false);
        }

        var newSysLock = $('input[name="sys_lock"]').prop("checked");
        if (newSysLock) {
            $('input[name="sys_lock"]').prop('checked', true);
        } else {
            $('input[name="sys_lock"]').prop('checked', false);
        }

        node.data = node.data || {};
        node.original.display_title = newTitle;
        node.original.icon = newIcon;
        node.original.description = newDescription;
        node.original.url = newUrl;
        node.original.feature_code = newFeature;
        node.original.visible = newVisible;
        node.original.menu_status = newStatus;
        node.original.sys_lock = newSysLock;

        // APPLY BADGE IF VISIBLE
        var badgeHtml = (newVisible) ?
            " <span class='badge bg-success ms-1'>Visible</span>" :
            " <span class='badge bg-danger ms-1'>Hidden</span>";

        // APPLY ICON IF LOCK
        var iconLock = (newSysLock) ?
            " <i class='lock text-primary fa-solid fa-lock'></i>" :
            "";
        treeInstance.rename_node(node, newTitle + iconLock + badgeHtml);

        // APPLY MUTED COLOR IF INACTIVE
        var anchor = $('#' + node.id + ' > a');
        if (newStatus) {
            anchor.removeClass('text-muted');
            anchor.find('i.jstree-themeicon').removeClass('text-muted');
            anchor.find('span.badge').removeClass('bg-secondary').addClass('bg-success');
        } else {
            anchor.addClass('text-muted');
            anchor.find('i.jstree-themeicon').addClass('text-muted');
            anchor.find('i.lock').removeClass('text-primary').addClass('text-secondary');
            if (anchor.find('span.badge').hasClass('bg-success')) {
                anchor.find('span.badge').removeClass('bg-success').addClass('bg-secondary');
            } else {
                anchor.find('span.badge').removeClass('bg-danger').addClass('bg-secondary');
            }
        }

        var finalIcon = (newIcon && $.trim(newIcon) !== '') ? newIcon : '';
        treeInstance.set_icon(node, finalIcon);

        $('#editModal').modal('toggle');
    });

    // CANCEL EDIT
    $('#btnCancelEdit').on('click', function() {
        $('#editModal').modal('toggle');
    });

    // SAVE TREE JSON (FOR DB)
    $('.btn-save').on('click', function() {
        if (!treeInstance) return;

        var raw = treeInstance.get_json('#', {
            flat: false
        });
        var result = [];
        const formData = new FormData();

        var orderMenu = 0;

        function loop(nodes, parentId) {
            $.each(nodes, function(index, n) {
                var node = treeInstance.get_node(n.id);
                node.data = node.data || {};

                var visible = (typeof node.original.visible !== 'undefined') ? node.original.visible : true;
                var status = (typeof node.original.menu_status !== 'undefined') ? node.original.menu_status : true;
                var sys_lock = (typeof node.original.sys_lock !== 'undefined') ? node.original.sys_lock : true;
                var baseTitle = node.original.display_title || node.text.replace(/ <span.*$/, '');

                formData.append('id[]', n.id);
                formData.append('feature_code[]', node.original.feature_code || null);
                formData.append('parent_menu_id[]', parentId);
                formData.append('order_menu[]', orderMenu);
                formData.append('display_title[]', baseTitle);
                formData.append('icon[]', node.original.icon || null);
                formData.append('description[]', node.original.description || null);
                formData.append('url[]', node.original.url || null);
                formData.append('visible[]', visible);
                formData.append('menu_status[]', status);
                formData.append('sys_lock[]', sys_lock);

                result.push({
                    id: n.id,
                    feature_code: node.original.feature_code || null,
                    parent_menu_id: parentId,
                    order_menu: orderMenu,
                    display_title: baseTitle,
                    icon: node.original.icon || null,
                    description: node.original.description || null,
                    url: node.original.url || null,
                    visible: visible,
                    menu_status: status,
                    sys_lock: sys_lock
                });
                orderMenu++;

                if (n.children && n.children.length > 0) {
                    loop(n.children, n.id);
                }

            });
        }

        loop(raw, null);
        $.ajax({
            url: BASE_URL + 'apps_menus/create_or_edit_menus',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                $.loader('hide')
                response.forEach(item => $.invyToastr({ type: item.level, message: `<b>${item.code}</b><br>${item.message}` }));
                renderSidebarMenu()
            },
            error: function() {
                $.loader('hide')
            }
        });
    });

    // RENDER MENU SIDEBAR
    function renderSidebarMenu() {
        $.ajax({
            url: BASE_URL + 'apps_menus/sidebar_menu?menu_open=' + bin2hex(jsURI[1] + '/' + jsURI[2]),
            method: 'GET',
            async: true,
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                $.loader('hide')
                $('#sidebar-menu').html(response)

                $.refreshMenu()
            },
            error: function() {
                $.loader('hide')
            }
        });
    }
});