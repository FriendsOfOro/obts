<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Datagrid\Action;

use Oro\Bundle\DataGridBundle\Extension\Action\ActionConfiguration;
use Oro\Bundle\DataGridBundle\Extension\Action\Actions\AjaxAction;

class IssueUnlinkAction extends AjaxAction
{
    /**
     * @return ActionConfiguration
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options['frontend_type'] = 'issue-unlink';

        return $options;
    }
}
