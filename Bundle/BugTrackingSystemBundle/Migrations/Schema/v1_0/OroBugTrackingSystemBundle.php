<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @codeCoverageIgnore
 */
class OroBugTrackingSystemBundle implements Migration
{
    const TABLE_PREFIX = 'oro_bts_';

    /**
     * @var string
     */
    private $issueTableName = 'issue';

    /**
     * @var string
     */
    private $issuePriorityTableName = 'issue_priority';

    /**
     * @var string
     */
    private $issuePriorityTranslationTableName = 'issue_priority_trans';

    /**
     * @var string
     */
    private $issueResolutionTableName = 'issue_resolution';

    /**
     * @var string
     */
    private $issueResolutionTranslationTableName = 'issue_resolution_trans';

    /**
     * @var string
     */
    private $issueTypeTableName = 'issue_type';

    /**
     * @var string
     */
    private $issueTypeTranslationTableName = 'issue_type_trans';

    /**
     * @var string
     */
    private $issueCollaboratorsTableName = 'issue_collaborators';

    /**
     * @var string
     */
    private $issueRelationsTableName = 'issue_relations';

    /**
     * @var string
     */
    private $userTableName = 'oro_user';

    /**
     * @var string
     */
    private $organizationTableName = 'oro_organization';

    /**
     * @var string
     */
    private $workflowItemTableName = 'oro_workflow_item';

    /**
     * @var string
     */
    private $workflowStepTableName = 'oro_workflow_step';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createIssueTable($schema);
        $this->createIssuePriorityTable($schema);
        $this->createIssuePriorityTranslationTable($schema);
        $this->createIssueResolutionTable($schema);
        $this->createIssueResolutionTranslationTable($schema);
        $this->createIssueTypeTable($schema);
        $this->createIssueTypeTranslationTable($schema);
        $this->createIssueCollaboratorsTable($schema);
        $this->createIssueRelationsTable($schema);

        $this->addIssueForeignKeys($schema);
        $this->addIssueCollaboratorsForeignKeys($schema);
        $this->addIssueRelationsForeignKeys($schema);
    }

    /**
     * Create Issue Table
     *
     * @param Schema $schema
     */
    private function createIssueTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('summary', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('code', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('issue_type_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['code'], 'uidx_oro_bts_issue_code');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_6D3EA5741023C4EE');
    }

    /**
     * Create IssuePriority table
     *
     * @param Schema $schema
     */
    private function createIssuePriorityTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issuePriorityTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('entity_order', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['name'], 'UNIQ_98C47AC5E237E06');
    }

    /**
     * Create IssuePriorityTranslation table
     *
     * @param Schema $schema
     */
    private function createIssuePriorityTranslationTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issuePriorityTranslationTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('foreign_key', 'string', ['length' => 16]);
        $table->addColumn('content', 'string', ['length' => 255]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);

        $table->setPrimaryKey(['id']);

        $table->addIndex(
            ['locale', 'object_class', 'field', 'foreign_key'],
            'idx_oro_bts_issue_priority_trans'
        );
    }

    /**
     * Create IssueResolution table
     *
     * @param Schema $schema
     */
    private function createIssueResolutionTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueResolutionTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('entity_order', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['name'], 'UNIQ_42796FEC5E237E06');
    }

    /**
     * Create IssueResolutionTranslation table
     *
     * @param Schema $schema
     */
    private function createIssueResolutionTranslationTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueResolutionTranslationTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('foreign_key', 'string', ['length' => 16]);
        $table->addColumn('content', 'string', ['length' => 255]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);

        $table->setPrimaryKey(['id']);

        $table->addIndex(
            ['locale', 'object_class', 'field', 'foreign_key'],
            'idx_oro_bts_issue_resolution_trans'
        );
    }

    /**
     * Create IssueType table
     *
     * @param Schema $schema
     */
    private function createIssueTypeTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueTypeTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('label', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('entity_order', 'integer', ['notnull' => true]);

        $table->setPrimaryKey(['id']);

        $table->addUniqueIndex(['name'], 'UNIQ_3342E0305E237E06');
    }

    /**
     * Create IssueTypeTranslation table
     *
     * @param Schema $schema
     */
    private function createIssueTypeTranslationTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueTypeTranslationTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('foreign_key', 'string', ['length' => 16]);
        $table->addColumn('content', 'string', ['length' => 255]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);

        $table->setPrimaryKey(['id']);

        $table->addIndex(
            ['locale', 'object_class', 'field', 'foreign_key'],
            'idx_oro_bts_issue_type_trans'
        );
    }

    /**
     * @param Schema $schema
     */
    private function addIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE_PREFIX . $this->issueTableName);
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issuePriorityTableName),
            ['issue_priority_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issueResolutionTableName),
            ['issue_resolution_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issueTypeTableName),
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
            $schema->getTable(self::TABLE_PREFIX . $this->issueTableName),
            ['parent_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->organizationTableName),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->workflowItemTableName),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->workflowStepTableName),
            ['workflow_step_id'],
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
        $table = self::TABLE_PREFIX . $this->issueCollaboratorsTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);

        $table->setPrimaryKey(['issue_id', 'user_id']);
    }

    /**
     * Create IssueRelations table
     *
     * @param Schema $schema
     */
    private function createIssueRelationsTable(Schema $schema)
    {
        $table = self::TABLE_PREFIX . $this->issueRelationsTableName;

        if ($schema->hasTable($table)) {
            $schema->dropTable($table);
        }

        $table = $schema->createTable($table);

        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('linked_issue_id', 'integer', []);

        $table->setPrimaryKey(['issue_id', 'linked_issue_id']);
    }

    /**
     * Add IssueCollaborators foreign keys.
     *
     * @param Schema $schema
     */
    private function addIssueCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE_PREFIX . $this->issueCollaboratorsTableName);
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issueTableName),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable($this->userTableName),
            ['user_id'],
            ['id'],
            ['onDelete' => null]
        );
    }

    /**
     * Add IssueRelations foreign keys.
     *
     * @param Schema $schema
     */
    private function addIssueRelationsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE_PREFIX . $this->issueRelationsTableName);
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issueTableName),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable(self::TABLE_PREFIX . $this->issueTableName),
            ['linked_issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
    }
}
