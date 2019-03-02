/*jslint nomen:true*/
/*global define*/
define(function (require) {
    'use strict';

    var IssueCreationWidgetComponent,
        BaseComponent = require('oroui/js/app/components/base/component'),
        __ = require('orotranslation/js/translator'),
        widgetManager = require('oroui/js/widget-manager'),
        mediator = require('oroui/js/mediator'),
        messenger = require('oroui/js/messenger');

    IssueCreationWidgetComponent = BaseComponent.extend({
        initialize: function (options) {
            widgetManager.getWidgetInstance(options.wid, function (widget) {
                messenger.notificationFlashMessage('success', __('Issue created successfully'));
                mediator.trigger('widget_success:' + widget.getAlias());
                mediator.trigger('widget_success:' + widget.getWid());
                widget.remove();
            });
        }
    });

    return IssueCreationWidgetComponent;
});
