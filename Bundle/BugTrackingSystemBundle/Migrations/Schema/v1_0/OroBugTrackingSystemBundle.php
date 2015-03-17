<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroBugTrackingSystemBundle implements Migration
{
    /**
     * @var string
     */
    private $issueTableName = 'obts_issue';

    /**
     * @var string
     */
    private $issuePriorityTableName = 'obts_issue_priority';

    /**
     * @var string
     */
    private $issueResolutionTableName = 'obts_issue_resolution';

    /**
     * @var string
     */
    private $issueTypeTableName = 'obts_issue_type';

    /**
     * @var string
     */
    private $issueCollaboratorsTableName = 'obts_issue_collaborators';

    /**
     * @var string
     */
    private $userTableName = 'oro_user';

    /**
     * @var string
     */
    private $organizationTableName = 'oro_organization';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createIssueTable($schema);

        $this->createIssuePriorityTable($schema);
        $this->createIssueResolutionTable($schema);
        $this->createIssueTypeTable($schema);

        $this->addIssueForeignKeys($schema);

        $this->createIssueCollaboratorsTable($schema);
    }

    /**
     * Create Issue Table
     *
     * @param Schema $schema
     */
    private function createIssueTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueTableName)) {
            $schema->dropTable($this->issueTableName);
        }

        $table = $schema->createTable($this->issueTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('summary', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('code', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('description', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('issue_type_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['code'], 'uidx_obts_issue_code');
    }

    /**
     * Create IssuePriority table
     *
     * @param Schema $schema
     */
    private function createIssuePriorityTable(Schema $schema)
    {
        if ($schema->hasTable($this->issuePriorityTableName)) {
            $schema->dropTable($this->issuePriorityTableName);
        }

        $table = $schema->createTable($this->issuePriorityTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('`order`', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * Create IssueResolution table
     *
     * @param Schema $schema
     */
    private function createIssueResolutionTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueResolutionTableName)) {
            $schema->dropTable($this->issueResolutionTableName);
        }

        $table = $schema->createTable($this->issueResolutionTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('`order`', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * Create IssueType table
     *
     * @param Schema $schema
     */
    private function createIssueTypeTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueTypeTableName)) {
            $schema->dropTable($this->issueTypeTableName);
        }

        $table = $schema->createTable($this->issueTypeTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('`order`', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    private function addIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable($this->issueTableName);
        $table->addForeignKeyConstraint(
            $schema->getTable($this->issuePriorityTableName),
            ['issue_priority_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->issueResolutionTableName),
            ['issue_resolution_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->issueTypeTableName),
            ['issue_type_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->userTableName),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->userTableName),
            ['owner_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->organizationTableName),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
    }

    /**
     * Create IssueCollaborators table
     *
     * @param Schema $schema
     */
    private function createIssueCollaboratorsTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueCollaboratorsTableName)) {
            $schema->dropTable($this->issueCollaboratorsTableName);
        }

        $table = $schema->createTable($this->issueCollaboratorsTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['issue_id', 'user_id'], 'uidx_obts_collaborators_issue_id_user_id');
    }
}
