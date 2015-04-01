/*global define*/
define([
    'oro/datagrid/action/delete-action',
    'orobugtrackingsystem/js/issue-unlink-handler',
    'oroui/js/messenger'
], function (DeleteAction, IssueUnlinkHandler, Messenger) {
    'use strict';
    var IssueUnlinkAction;
    /**
     * Activate AJAX action, triggers confirmation dialog and unlink issue
     *
     * @export oro/datagrid/action/issue-unlink-action
     * @class oro.datagrid.action.IssueUnlinkAction
     * @extends oro.datagrid.action.DeleteAction
     */
    IssueUnlinkAction = DeleteAction.extend({
        execute: function () {
            var datagrid = this.datagrid;

            this.on('issue_unlink_start', function () {
                datagrid.showLoading();
            });
            this.on('issue_unlink_success', function () {
                Messenger.notificationFlashMessage('success', 'Issue unlinked successfully');

                datagrid.hideLoading();
                datagrid.collection.fetch({reset: true});
            });
            this.on('issue_unlink_error', function () {
                datagrid.hideLoading();
            });

            IssueUnlinkHandler.call(this, this.getLink());
        }
    });
    return IssueUnlinkAction;
});