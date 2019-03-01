<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Model\Condition;

use Oro\Component\ConfigExpression\Condition\AbstractCondition;
use Oro\Component\ConfigExpression\ContextAccessorAwareInterface;
use Oro\Component\ConfigExpression\ContextAccessorAwareTrait;
use Oro\Component\ConfigExpression\Exception\InvalidArgumentException;

class IsInstanceOf extends AbstractCondition implements ContextAccessorAwareInterface
{
    use ContextAccessorAwareTrait;

    const NAME = 'instanceof';

    /**
     * @var object
     */
    protected $target;

    /**
     * @var string
     */
    protected $className;

    /**
     * Returns TRUE if target is instance of class
     *
     * @param mixed $context
     * @return boolean
     */
    protected function isConditionAllowed($context)
    {
        $value = $this->resolveValue($context, $this->target);

        return $value instanceof $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Initialize target that will be checked for emptiness
     *
     * @param array $options
     *
     * @return IsInstanceOf
     *
     * @throws InvalidArgumentException
     */
    public function initialize(array $options)
    {
        if (2 !== count($options)) {
            throw new InvalidArgumentException(sprintf('Options must have 2 elements, but %d given', count($options)));
        }

        $this->target = array_shift($options);
        $this->className = array_shift($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->convertToArray([$this->target, $this->className]);
    }

    /**
     * {@inheritdoc}
     */
    public function compile($factoryAccessor)
    {
        return $this->convertToPhpCode([$this->target], $factoryAccessor)  .' instanceof ' . $this->className;
    }
}
