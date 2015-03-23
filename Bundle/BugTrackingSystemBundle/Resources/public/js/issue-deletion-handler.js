/*global define*/
define([
    'jquery',
    'orotranslation/js/translator',
    'oroui/js/delete-confirmation',
    'oroui/js/messenger'
], function($, __, DeleteConfirmation, Messenger) {
    'use strict';

    /**
     * Issue deletion handler
     *
     * @export  orobugtrackingsystem/js/issue-deletion-handler
     * @class   orobugtrackingsystem.IssueDeletionHandler
     */
    return function(url, subtasksCount) {
        var element = this;

        var subtasksCountMessage = subtasksCount > 0
            ? ' Issue contains ' + subtasksCount + ' Sub-Tasks.'
            : '';

        var confirmIssueDeletion = new DeleteConfirmation({
            content: 'Are you sure you want to delete this Issue?' + subtasksCountMessage
        });

        confirmIssueDeletion.on('ok', function() {
            element.trigger('issue_deletion_start');
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function() {
                    element.trigger('issue_deletion_success');
                },
                error: function() {
                    Messenger.notificationFlashMessage('error', 'You do not have permission to perform this action.');
                    element.trigger('issue_deletion_error');
                }
            })
        });

        confirmIssueDeletion.open();
    }
});
