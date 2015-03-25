/*jslint nomen:true*/
/*global define*/
define(function (require) {
    'use strict';

    var IssueCollaboratorsRefreshComponent,
        BaseComponent = require('oroui/js/app/components/base/component'),
        mediator = require('oroui/js/mediator');

    IssueCollaboratorsRefreshComponent = BaseComponent.extend({
        initialize: function (options) {
            mediator.subscribe('widget_success:note-dialog', function () {
                mediator.trigger('datagrid:doRefresh:collaborators_grid');
            });
        }
    });

    return IssueCollaboratorsRefreshComponent;
});
