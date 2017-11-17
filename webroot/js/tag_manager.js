var TagManager = {
    /** A tree-shaped object provided to init() **/
    tags: [],

    /** A one-dimensional array populated by createTagList() */
    tag_list: [],

    /** An object with tag_name: tag_id pairs populated by createTagList() */
    tags_ids: {},

    /** Used by preselectTags() */
    selected_tags: [],

    /** The minimum string length to begin searching for autocomplete results */
    minLengthForSearch: 2,

    container: null,
    show_tree: true,
    show_list: false,

    init: function (options) {
        if (options.hasOwnProperty('container')) {
            this.container = $(options.container);
        } else {
            this.container = $('#available_tags');
        }

        if (options.hasOwnProperty('show_tree')) {
            this.show_tree = options.show_tree;
        }

        if (options.hasOwnProperty('show_list')) {
            this.show_list = options.show_list;
        }

        if (! this.checkRequirements()) {
            return;
        }

        if (options.hasOwnProperty('tags')) {
            this.tags = options.tags;
        }

        if (this.show_tree) {
            this.createTagTree();
        }

        if (this.show_list) {
            this.createTagList();
        }

        if (this.show_tree && this.show_list) {
            this.setupTabs();
        }

        if (options.hasOwnProperty('selected_tags')) {
            this.selected_tags = options.selected_tags;
        }

        if (this.selected_tags.length > 0) {
            TagManager.preselectTags(this.selected_tags);
        }

        $('#new_tag_rules_toggler').click(function (event) {
            event.preventDefault();
            $('#new_tag_rules').slideToggle(200);
        });

        $('#example_selectable_tag').click(function (event) {
            event.preventDefault();
        });
    },

    checkRequirements: function () {
        var passes = true;
        var msg = '';
        if (! window.jQuery) {
            this.showError('Error: The tag manager requires jQuery.');
            passes = false;
        } else {
            if (! $.effects || ! $.effects.effect.transfer) {
                msg = 'Error: The jQuery UI transfer effect is required for the tag manager but has not been loaded.';
                this.showError(msg);
                passes = false;
            }
            if (! $.isFunction($.fn.autocomplete)) {
                msg = 'Error: The jQuery UI autocomplete widget is required for the tag manager ' +
                    'but has not been loaded.';
                this.showError(msg);
                passes = false;
            }
            if (! $.isFunction($.fn.tabs)) {
                msg = 'Error: The jQuery UI tabs widget is required for the tag manager but has not been loaded.';
                this.showError(msg);
                passes = false;
            }
        }
        return passes;
    },

    setupTabs: function () {
        var tabs = $('<ul></ul>');
        tabs.append($('<li><a href="#available_tags_tree">Tree</a></li>'));
        tabs.append($('<li><a href="#available_tags_list">List</a></li>'));
        this.container.prepend(tabs);
        this.container.tabs();
    },

    showError: function (message) {
        this.container.prepend('<p style="color: red;">' + message + '</p>');
    },

    createTagTree: function () {
        var treeContainer = $('<div id="available_tags_tree"></div>');
        this.container.append(treeContainer);
        this.createTagTreeBranch(this.tags, treeContainer);
    },

    /**
     * Appends to container a branch of the tag tree
     *
     * @param {Object[]} data - An array of tag objects
     * @param {Object} container - $('#container_id')
     */
    createTagTreeBranch: function(data, container) {
        var list = $('<ul></ul>');
        for (var i = 0; i < data.length; i++) {
            var tagId = data[i].id;
            var tagName = data[i].name;
            var children = data[i].children;
            var hasChildren = (children.length > 0);
            var isSelectable = data[i].selectable;
            var listItem = $('<li data-tag-id="' + tagId + '"></li>');
            var row = $('<div class="single_row"></div>');
            listItem.append(row);
            list.append(listItem);

            if (isSelectable) {
                var tagLink = $('<a href="#" title="Click to select" data-tag-id="' + tagId + '"></a>');
                tagLink.addClass('available_tag');
                tagLink.append(tagName);
                (function(tagId) {
                    tagLink.click(function (event) {
                        event.preventDefault();
                        var link = $(this);
                        var tagName = link.html();
                        var listItem = link.closest('li');
                        TagManager.selectTag(tagId, tagName, listItem);
                    });
                })(tagId);
                tagName = tagLink;
            }

            // Bullet point
            if (hasChildren) {
                var collapsedIcon = $('<a href="#" title="Click to expand/collapse"></a>');
                var img = $('<img src="/data_center/img/icons/menu-collapsed.png" class="expand_collapse" />');
                collapsedIcon.append(img);
                (function(children) {
                    collapsedIcon.click(function(event) {
                        event.preventDefault();
                        var icon = $(this);
                        var iconContainer = icon.parent('div');
                        var childrenContainer = iconContainer.next('.children');

                        // Populate list if it is empty
                        if (childrenContainer.is(':empty')) {
                            TagManager.createTagTreeBranch(children, childrenContainer);
                        }

                        // Open/close
                        childrenContainer.slideToggle(200, function() {
                            var iconImage = icon.children('img.expand_collapse');
                            if (childrenContainer.is(':visible')) {
                                iconImage.prop('src', '/data_center/img/icons/menu-expanded.png');
                            } else {
                                iconImage.prop('src', '/data_center/img/icons/menu-collapsed.png');
                            }
                        });
                    });
                })(children);

                row.append(collapsedIcon);
            } else {
                row.append('<img src="/data_center/img/icons/menu-leaf.png" class="leaf" />');
            }

            row.append(tagName);

            // Tag and submenu
            if (hasChildren) {
                var childrenContainer = $('<div style="display: none;" class="children"></div>');
                row.after(childrenContainer);
            }

            // If tag has been selected
            if (isSelectable && this.tagIsSelected(tagId)) {
                tagName.addClass('selected');
                if (! hasChildren) {
                    listItem.hide();
                }
            }
        }
        container.append(list);
    },

    createTagList: function () {
        this.processTagList(this.tags);
        this.tag_list.sort();
        var list = $('<ul></ul>');
        for (var i = 0; i < this.tag_list.length; i++) {
            var tagName = this.tag_list[i];
            var tagId = this.tags_ids[tagName];
            var listItem = $('<li data-tag-id="'+tagId+'"></li>');

            var tagLink = $('<a href="#" class="available_tag" title="Click to select" data-tag-id="'+tagId+'"></a>');
            tagLink.append(tagName);
            (function(tagId) {
                tagLink.click(function (event) {
                    event.preventDefault();
                    var link = $(this);
                    var tagName = link.html();
                    var listItem = link.closest('li');
                    TagManager.selectTag(tagId, tagName, listItem);
                });
            })(tagId);
            listItem.append(tagLink);
            list.append(listItem);
        }

        var listContainer = $('<div id="available_tags_list"></div>');
        listContainer.append(list);
        this.container.append(listContainer);
    },

    processTagList: function (data) {
        for (var i = 0; i < data.length; i++) {
            var tagId = data[i].id;
            var tagName = data[i].name;
            var children = data[i].children;
            var hasChildren = (children.length > 0);
            var isSelectable = data[i].selectable;
            if (isSelectable) {
                this.tag_list.push(tagName);
                this.tags_ids[tagName] = tagId;
            }
            if (hasChildren) {
                this.processTagList(children);
            }
        }
    },

    tagIsSelected: function(tagId) {
        var selectedTags = $('#selected_tags').find('a');
        for (var i = 0; i < selectedTags.length; i++) {
            var tag = $(selectedTags[i]);
            if (tag.data('tagId') === tagId) {
                return true;
            }
        }
        return false;
    },

    preselectTags: function(selectedTags) {
        if (selectedTags.length === 0) {
            return;
        }
        $('#selected_tags_container').show();
        for (var i = 0; i < selectedTags.length; i++) {
            TagManager.selectTag(selectedTags[i].id, selectedTags[i].name);
        }
    },

    unselectTag: function(tagId, unselectLink) {
        var availableTagLinks = this.container.find('a[data-tag-id="' + tagId + '"]');

        // If available tag has not yet been loaded, then simply remove the selected tag
        if (availableTagLinks.length === 0) {
            TagManager.removeUnselectLink(unselectLink);
            return;
        }

        availableTagLinks.each(function () {
            var link = $(this).removeClass('selected');
            var li = link.closest('li');
            var openTab = link.closest('#available_tags_tree, #available_tags_list');

            // If this link is in an unopened tab, don't animate anything
            if (! openTab.is(':visible')) {
                li.show();

                /* Only remove the unselect link if this is the only iteration of this loop.
                 * Otherwise, the link in the opened tab needs the unselect link present for the transfer effect. */
                if (availableTagLinks.length === 1) {
                    TagManager.removeUnselectLink(unselectLink);
                }
                return;
            }

            var transferEffect = function () {
                // Don't show the transfer effect if there's no visible link to transfer to
                if (! TagManager.availableTagIsVisible(link, openTab)) {
                    TagManager.removeUnselectLink(unselectLink);
                    return;
                }
                var options = {
                    to: link,
                    className: 'ui-effects-transfer'
                };
                unselectLink.effect('transfer', options, 200, function () {
                    TagManager.removeUnselectLink(unselectLink);
                });
            };

            // If the link container doesn't need to be revealed
            if (li.is(':visible')) {
                transferEffect();

                // If the link container needs to be revealed (and would be visible during the reveal)
            } else if (li.parent().is(':visible')) {
                li.slideDown(200, function () {
                    transferEffect();
                });

            } else {
                li.show();
                TagManager.removeUnselectLink(unselectLink);
            }
        });
    },

    availableTagIsVisible: function (link, scrollableArea) {
        if (! link.is(':visible')) {
            return false;
        }
        return (link.position().top + link.height() > 0 && link.position().top < scrollableArea.height());
    },

    removeUnselectLink: function (unselectLink) {
        unselectLink.fadeOut(200, function () {
            unselectLink.remove();
            if ($('#selected_tags').children().length === 0) {
                $('#selected_tags_container').slideUp(200);
            }
        });
    },

    selectTag: function(tagId, tagName) {
        var selectedContainer = $('#selected_tags_container');
        if (! selectedContainer.is(':visible')) {
            selectedContainer.slideDown(200);
        }

        // Do not add tag if it is already selected
        if (this.tagIsSelected(tagId)) {
            return;
        }

        // Add tag
        var listItem = $('<a href="#" title="Click to remove" data-tag-id="' + tagId + '"></a>');
        listItem.append(tagName);
        listItem.append('<input type="hidden" name="data[Tag][]" value="' + tagId + '" />');
        listItem.click(function (event) {
            event.preventDefault();
            var unselectLink = $(this);
            var tagId = unselectLink.data('tagId');
            TagManager.unselectTag(tagId, unselectLink);
        });
        listItem.hide();
        $('#selected_tags').append(listItem);
        listItem.fadeIn(200);

        // If available tag has not yet been loaded, then there's no need to mess with its link
        if ($('li[data-tag-id="' + tagId + '"]').length === 0) {
            return;
        }

        // Hide/update links to add tag
        var links = this.container.find('a[data-tag-id="' + tagId + '"]');
        links.each(function () {
            var link = $(this);
            var callback = function() {
                link.addClass('selected');
                var parentLi = link.closest('li');
                var children = parentLi.children('.children');
                if (children.length === 0) {
                    if (parentLi.is(':visible')) {
                        parentLi.slideUp(200);
                    } else {
                        parentLi.hide();
                    }
                }
            };
            if (link.is(':visible')) {
                var options = {
                    to: '#selected_tags a[data-tag-id="' + tagId + '"]',
                    className: 'ui-effects-transfer'
                };
                link.effect('transfer', options, 200, callback);
            } else {
                callback();
            }
        });
    },

    setupAutosuggest: function(selector) {
        $(selector).bind('keydown', function (event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            source: function(request, response) {
                $.getJSON('/tags/auto_complete', {
                    term: TagManager.extractLast(request.term)
                }, response);
            },
            delay: 0,
            search: function() {
                var term = TagManager.extractLast(this.value);
                if (term.length < 2) {
                    return false;
                }
                $(selector).siblings('img.loading').show();
            },
            response: function() {
                $(selector).siblings('img.loading').hide();
            },
            focus: function() {
                return false;
            },
            select: function(event, ui) {
                var tagName = ui.item.label;
                var terms = TagManager.split(this.value);
                terms.pop();
                terms.push(tagName);

                // Add placeholder to get the comma-and-space at the end
                terms.push('');

                this.value = terms.join(', ');
                return false;
            }
        });
    },

    setupCustomTagInput: function(selector) {
        if (! selector) {
            selector = '#custom_tag_input';
        }
        $(selector).bind('keydown', function (event) {
            // Prevent navigation away from the field on tab when selecting an item
            if (event.keyCode === $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            source: function(request, response) {
                $.getJSON('/tags/auto_complete', {
                    term: TagManager.extractLast(request.term)
                }, response);
            },
            delay: 0,
            search: function() {
                var term = TagManager.extractLast(this.value);
                if (term.length < TagManager.minLengthForSearch) {
                    return;
                }
                $('#tag_autosuggest_loading').show();
            },
            response: function() {
                $('#tag_autosuggest_loading').hide();
            },
            focus: function() {
                // Prevent value inserted on focus
                return false;
            },
            select: function(event, ui) {
                // Add the selected term to 'selected tags'
                var tagName = ui.item.label;
                var tagId = ui.item.value;
                TagManager.selectTag(tagId, tagName);

                var terms = TagManager.split(this.value);

                // Remove the term being typed from the input field
                terms.pop();

                if (terms.length > 0) {
                    // Add placeholder to get the comma-and-space at the end
                    terms.push('');
                }

                this.value = terms.join(', ');

                return false;
            }
        });
    },

    split: function (val) {
        return val.split(/,\s*/);
    },

    extractLast: function (term) {
        return this.split(term).pop();
    }
};
