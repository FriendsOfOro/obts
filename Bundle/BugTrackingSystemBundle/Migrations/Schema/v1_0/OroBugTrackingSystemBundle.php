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
    protected $issueTableName = 'obts_issue';

    /**
     * @var string
     */
    protected $issuePriorityTableName = 'obts_issue_priority';

    /**
     * @var string
     */
    protected $issueResolutionTableName = 'obts_issue_resolution';

    /**
     * @var string
     */
    protected $issueTypeTableName = 'obts_issue_type';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createIssueTable($schema);

        $this->createIssuePriorityTable($schema);
        $this->createIssueResolutionTable($schema);
        $this->createIssueTypeTable($schema);
    }

    /**
     * Create Issue Table
     *
     * @param Schema $schema
     */
    public function createIssueTable(Schema $schema)
    {
        if ($schema->hasTable($this->issueTableName)) {
            $schema->dropTable($this->issueTableName);
        }

        $table = $schema->createTable($this->issueTableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('summary', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('description', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('issue_type_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);

        $table->setPrimaryKey(['id']);
    }

    /**
     * Create IssuePriority table
     *
     * @param Schema $schema
     */
    public function createIssuePriorityTable(Schema $schema)
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
    public function createIssueResolutionTable(Schema $schema)
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
    public function createIssueTypeTable(Schema $schema)
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
}
