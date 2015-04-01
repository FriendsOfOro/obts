/*global define*/
define([
    'jquery',
    'orotranslation/js/translator',
    'oroui/js/delete-confirmation',
    'oroui/js/messenger'
], function($, __, DeleteConfirmation, Messenger) {
    'use strict';

    /**
     * Issue unlink handler
     *
     * @export  orobugtrackingsystem/js/issue-unlink-handler
     * @class   orobugtrackingsystem.IssueUnlinkHandler
     */
    return function(url) {
        var element = this;

        var confirmIssueUnlink = new DeleteConfirmation({
            content: 'Are you sure you want to unlink this Issue?',
            okText: 'Yes, Unlink'
        });

        confirmIssueUnlink.on('ok', function() {
            element.trigger('issue_unlink_start');
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function() {
                    element.trigger('issue_unlink_success');
                },
                error: function() {
                    Messenger.notificationFlashMessage('error', 'You do not have permission to perform this action.');
                    element.trigger('issue_unlink_error');
                }
            })
        });

        confirmIssueUnlink.open();
    }
});
