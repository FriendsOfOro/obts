/*global define*/
define([
    'oro/datagrid/action/delete-action',
    'orobugtrackingsystem/js/issue-deletion-handler',
    'oroui/js/messenger'
], function (DeleteAction, IssueDeletionHandler, Messenger) {
    'use strict';
    var IssueDeleteAction;
    /**
     * Activate AJAX action, triggers confirmation dialog and delete issue
     *
     * @export oro/datagrid/action/issue-delete-action
     * @class oro.datagrid.action.IssueDeleteAction
     * @extends oro.datagrid.action.DeleteAction
     */
    IssueDeleteAction = DeleteAction.extend({
        execute: function () {
            var datagrid = this.datagrid;

            this.on('issue_deletion_start', function () {
                datagrid.showLoading();
            });
            this.on('issue_deletion_success', function () {
                Messenger.notificationFlashMessage('success', 'Issue deleted');

                datagrid.hideLoading();
                datagrid.collection.fetch({reset: true});
            });
            this.on('issue_deletion_error', function () {
                datagrid.hideLoading();
            });

            IssueDeletionHandler.call(this, this.getLink(), this.model.get('subtasksCount'));
        }
    });
    return IssueDeleteAction;
});