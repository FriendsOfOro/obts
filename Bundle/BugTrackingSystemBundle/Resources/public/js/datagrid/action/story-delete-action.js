/*global define*/
define([
    'oro/datagrid/action/delete-action',
    'orobugtrackingsystem/js/story-deletion-handler'
], function (DeleteAction, StoryDeletionHandler) {
    'use strict';
    var StoryDeleteAction;
    /**
     * Activate AJAX action, triggers confirmation dialog and delete story definition
     *
     * @export oro/datagrid/action/story-delete-action
     * @class oro.datagrid.action.StoryDeleteAction
     * @extends oro.datagrid.action.DeleteAction
     */
    StoryDeleteAction = DeleteAction.extend({
        execute: function () {
            alert('test');

            var datagrid = this.datagrid;

            StoryDeletionHandler.call(this, this.getLink());

            //this.getConfirmDialog(_.bind(this.doDelete, this)).open();
        }
    });
    return StoryDeleteAction;
});