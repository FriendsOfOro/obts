<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Model\Condition;

use Oro\Bundle\WorkflowBundle\Exception\ConditionException;
use Oro\Bundle\WorkflowBundle\Model\Condition\AbstractCondition;
use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;

class IsInstanceOf extends AbstractCondition
{
    /**
     * @var ContextAccessor
     */
    protected $contextAccessor;

    /**
     * @var object
     */
    protected $target;

    /**
     * @var string
     */
    protected $className;

    /**
     * Constructor
     *
     * @param ContextAccessor $contextAccessor
     */
    public function __construct(ContextAccessor $contextAccessor)
    {
        $this->contextAccessor = $contextAccessor;
    }

    /**
     * Returns TRUE if target is instance of class
     *
     * @param mixed $context
     * @return boolean
     */
    protected function isConditionAllowed($context)
    {
        $value = $this->contextAccessor->getValue($context, $this->target);

        return $value instanceof $this->className;
    }

    /**
     * Initialize target that will be checked for emptiness
     *
     * @param array $options
     * @return IsInstanceOf
     * @throws ConditionException
     */
    public function initialize(array $options)
    {
        if (2 !== count($options)) {
            throw new ConditionException(sprintf('Options must have 2 elements, but %d given', count($options)));
        }

        $this->target = array_shift($options);
        $this->className = array_shift($options);

        return $this;
    }
}
