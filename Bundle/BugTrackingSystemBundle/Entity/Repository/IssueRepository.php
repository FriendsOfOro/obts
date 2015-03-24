<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

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
        $qb = $this
            ->createQueryBuilder('issue')
            ->select('COUNT(issue.id) AS issue_count', 'workflowStep.label AS status')
            ->leftJoin('issue.workflowStep', 'workflowStep')
            ->groupBy('workflowStep.id');

        return $aclHelper->apply($qb)->getArrayResult();
    }
}
