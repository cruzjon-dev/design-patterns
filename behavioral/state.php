<?php

/****************************************************************
 * STATE
 * - A behavioral design pattern that allows an object to change the behavior when its internal state changes.
 * - The State pattern suggests that you create new classes for all possible states of an object and extract all state-specific behaviors into these classes.
 * - Instead of implementing all behaviors on its own, the original object, called context, stores a reference to one of the state objects that represents its current state, and delegates all the state-related work to that object.
 * - This structure may look similar to the Strategy pattern, but there’s one key difference. In the State pattern, the particular states may be aware of each other and initiate transitions from one state to another, whereas strategies almost never know about each other.
****************************************************************/


/**
 * The Context defines the interface of interest to clients. It also maintains a
 * reference to an instance of a State subclass, which represents the current
 * state of the Context.
 */
class Context
{
    /**
     * @var State A reference to the current state of the Context.
     */
    private $state;

    public function __construct(State $state)
    {
        $this->transitionTo($state);
    }

    /**
     * The Context allows changing the State object at runtime.
     */
    public function transitionTo(State $state): void
    {
        echo "Context: Transition to " . get_class($state) . ".<br/>";
        $this->state = $state;
        $this->state->setContext($this);
    }

    /**
     * The Context delegates part of its behavior to the current State object.
     */
    public function request1(): void
    {
        $this->state->handle1();
    }

    public function request2(): void
    {
        $this->state->handle2();
    }
}

/**
 * The base State class declares methods that all Concrete State should
 * implement and also provides a backreference to the Context object, associated
 * with the State. This backreference can be used by States to transition the
 * Context to another State.
 */
abstract class State
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    abstract public function handle1(): void;

    abstract public function handle2(): void;
}

/**
 * Concrete States implement various behaviors, associated with a state of the
 * Context.
 */
class ConcreteStateA extends State
{
    public function handle1(): void
    {
        echo "ConcreteStateA handles request1.<br/>";
        echo "ConcreteStateA wants to change the state of the context.<br/>";
        $this->context->transitionTo(new ConcreteStateB());
    }

    public function handle2(): void
    {
        echo "ConcreteStateA handles request2.<br/>";
    }
}

class ConcreteStateB extends State
{
    public function handle1(): void
    {
        echo "ConcreteStateB handles request1.<br/>";
    }

    public function handle2(): void
    {
        echo "ConcreteStateB handles request2.<br/>";
        echo "ConcreteStateB wants to change the state of the context.<br/>";
        $this->context->transitionTo(new ConcreteStateA());
    }
}

/**
 * The client code.
 */
$context = new Context(new ConcreteStateA());
$context->request1();
$context->request2();