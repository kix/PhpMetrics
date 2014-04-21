<?php

/*
 * (c) Jean-François Lépine <https://twitter.com/Halleck45>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hal\Component\OOP\Reflected;


/**
 * Result (class)
 *
 * @author Jean-François Lépine <https://twitter.com/Halleck45>
 */
class ReflectedClass {

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $name;

    /**
     * Methods
     *
     * @var \SplObjectStorage
     */
    private $methods;

    /**
     * Consolidated dependencies
     *
     * @var array array
     */
    private $dependencies = array();

    /**
     * Map of aliases
     *
     * @var array
     */
    private $aliases = array();

    /**
     * Does the class is abstract ?
     *
     * @var bool
     */
    private $isAbstract = false;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $namespace
     */
    public function __construct($namespace, $name)
    {
        $this->name = (string) $name;
        $this->namespace = (string) $namespace;
        $this->methods = array();
    }

    /**
     * Get fullname (namespace + name)
     *
     * @return string
     */
    public function getFullname() {
        return $this->getNamespace().'\\'.$this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return rtrim($this->namespace, '\\');
    }

    /**
     * @return \SplObjectStorage
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Attach method
     * This method consolidated dependencies
     *
     * @param ReflectedMethod $method
     * @return $this
     */
    public function pushMethod(ReflectedMethod $method) {
        $this->methods[$method->getName()] = $method;

        foreach($method->getArguments() as $argument) {

            $name = $argument->getType();
            if(!in_array($argument->getType(), array(null, $this->getName(), 'array'))) {
                $this->pushDependency($name);
            }
        }

        return $this;
    }

    /**
     * Push dependency
     *
     * @param $name
     * @return $this
     */
    public function pushDependency($name) {
        $real = isset($this->aliases[$name]) ? $this->aliases[$name] : $name;
        array_push($this->dependencies, $real);
        return $this;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param array $aliases
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set abstractness of method
     *
     * @param boolean $bool
     * @return $this
     */
    public function setAbstract($bool) {
        $this->isAbstract = (bool) $bool;
        return $this;
    }

    /**
     * Is Abstract ?
     *
     * @return bool
     */
    public function isAbstract() {
        return $this->isAbstract;
    }
};