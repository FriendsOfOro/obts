<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;

class IssueRepository extends EntityRepository
{
    /**
     * Get issues by status
     *
     * @param $aclHelper AclHelper
     * @return array
     */
    public function getIssuesByStatus(AclHelper $aclHelper)
    {
        $statuses = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('workflow.name, workflow.label')
            ->from('OroWorkflowBundle:WorkflowStep', 'workflow')
            ->innerJoin('workflow.definition', 'definition')
            ->where('definition.relatedEntity = ?1')
            ->setParameter(1, Issue::class)
            ->orderBy('workflow.stepOrder', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $result = [];

        foreach ($statuses as $status) {
            $statusName = $status['name'];

            $result[$statusName] = [
                'name'        => $statusName,
                'status'      => $status['label'],
                'issue_count' => 0,
            ];
        }

        $qb = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(item.id) AS issue_count', 'step.name AS status_name')
            ->from('OroWorkflowBundle:WorkflowItem', 'item')
            ->leftJoin('item.currentStep', 'step')
            ->groupBy('step.id');

        $issues = $aclHelper->apply($qb)->getArrayResult();

        foreach ($issues as $issue) {
            $status = $issue['status_name'];
            $count = (int) $issue['issue_count'];

            if ($count) {
                $result[$status]['issue_count'] = $count;
            }
        }

        return $result;
    }
}
