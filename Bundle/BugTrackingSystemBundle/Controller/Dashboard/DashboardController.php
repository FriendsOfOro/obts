<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issue_status/chart/{widget}",
     *      name="oro_bug_tracking_system_dashboard_issue_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OroBugTrackingSystemBundle:Dashboard:issueByStatus.html.twig")
     * @param string $widget
     * @return array
     */
    public function issueByStatusAction($widget)
    {
        $items = $this
            ->getDoctrine()
            ->getRepository('OroBugTrackingSystemBundle:Issue')
            ->getIssuesByStatus($this->get('oro_security.acl_helper'));

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this
            ->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                [
                    'name' => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'status'],
                        'value' => ['field_name' => 'issue_count']
                    ],
                    'settings' => ['xNoTicks' => 4]
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
