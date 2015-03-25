/*jslint nomen:true*/
/*global define*/
define(function (require) {
    'use strict';

    var IssueDeletionComponent,
        BaseComponent = require('oroui/js/app/components/base/component'),
        $ = require('jquery'),
        mediator = require('oroui/js/mediator'),
        IssueDeletionHandler = require('orobugtrackingsystem/js/issue-deletion-handler');

    IssueDeletionComponent = BaseComponent.extend({
        initialize: function (options) {
            $('#'+options.idButton).on('click', function(e) {
                e.preventDefault();
                var el = $(this);
                el.on('issue_deletion_success', function() {
                    mediator.once('page:afterChange', function() {
                        mediator.execute('showFlashMessage', 'success', 'Issue deleted');
                    });
                    mediator.execute('redirectTo', {url: options.url});
                });
                IssueDeletionHandler.call(el, el.prop('href'), options.subTasksCount);
            });
        }
    });

    return IssueDeletionComponent;
});
