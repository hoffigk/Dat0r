<?php

namespace Dat0r\Runtime\Module;

use Dat0r\Common\Object;
use Dat0r\Common\Error\InvalidTypeException;
use Dat0r\Common\Error\RuntimeException;
use Dat0r\Runtime\Document\IDocument;
use Dat0r\Runtime\Field\Type\AggregateField;
use Dat0r\Runtime\Field\Type\ReferenceField;
use Dat0r\Runtime\Field\IField;
use Dat0r\Runtime\Field\FieldMap;

/**
 * Base class that all Dat0r modules should extend.
 */
abstract class Module extends Object implements IModule
{
    /**
     * Holds a list of IModule implementations that are pooled by type.
     *
     * @var array $instances
     */
    private static $instances = array();

    /**
     * Holds the module's name.
     *
     * @var string $name
     */
    private $name;

    /**
     * Holds the module's fields.
     *
     * @var FieldMap $fields
     */
    private $fields;

    /**
     * Holds the field'S options.
     *
     * @var array $options
     */
    private $options = array();

    /**
     * Holds the module's prefix.
     *
     * @var string $prefix
     */
    private $prefix;

    /**
     * Returns the class(name) to use when creating new entries for this module.
     *
     * @return string
     */
    abstract protected function getDocumentImplementor();

    /**
     * Returns the pooled instance of a specific module.
     * Each module is pooled exactly once, making this a singleton style (factory)method.
     * This method is used to provide a convenient access to generated domain module instances.
     *
     * @return IModule
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            $module = new static();
            self::$instances[$class] = $module;
        }

        return self::$instances[$class];
    }

    public function getDocumentType()
    {
        return $this->getDocumentImplementor();
    }

    /**
     * Returns the name of the module.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the module's prefix (techn. identifier).
     *
     * @return string
     */
    public function getPrefix()
    {
        if (!$this->prefix) {
            preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $this->getName(), $match_results);
            $matches = $match_results[0];
            foreach ($matches as &$match) {
                $match = ($match == strtoupper($match)) ? strtolower($match) : lcfirst($match);
            }
            $this->prefix = implode('_', $matches);
        }

        return $this->prefix;
    }

    /**
     * Returns the module's field collection.
     *
     * @param array $fieldnames A list of fieldnames to filter for.
     *
     * @return FieldMap
     */
    public function getFields(array $fieldnames = array(), $types = array())
    {
        $fields = array();

        if (empty($fieldnames)) {
            $fields = $this->fields->toArray();
        } else {
            foreach ($fieldnames as $fieldname) {
                $fields[$fieldname] = $this->getField($fieldname);
            }
        }

        if (!empty($types)) {
            $fields = array_filter(
                $fields,
                function ($field) use ($types) {
                    return in_array(get_class($field), $types);
                }
            );
        }

        return new FieldMap($fields);
    }

    /**
     * Returns a certain module field by name.
     *
     * @param string $name
     *
     * @return IField
     *
     * @throws InvalidFieldException
     */
    public function getField($name)
    {
        if (mb_strpos($name, '.')) {
            return $this->getFieldByPath($name);
        }
        if (($field = $this->fields->getItem($name))) {
            return $field;
        } else {
            throw new RuntimeException("Module has no field: " . $name);
        }
    }

    /**
     * Creates a new IDocument instance.
     *
     * @param array $data Optional data for initial hydration.
     *
     * @return IDocument
     *
     * @throws InvalidTypeException
     */
    public function createDocument(array $data = array())
    {
        $implementor = $this->getDocumentImplementor();

        if (!class_exists($implementor, true)) {
            throw new InvalidTypeException(
                "Unable to resolve the given document implementor upon document creation request."
            );
        }

        return new $implementor($this, $data);
    }

    /**
     * Returns a module option by name if it exists.
     * Otherwise an optional default is returned.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return $this->hasOption($name) ? $this->options[$name] : $default;
    }

    /**
     * Tells if a module currently owns a specific option.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Constructs a new Module.
     *
     * @param string $name
     * @param array $fields
     */
    protected function __construct($name, array $fields = array(), array $options = array())
    {
        $this->name = $name;
        $this->options = $options;

        $this->fields = new FieldMap($this->getDefaultFields());

        foreach ($fields as $field) {
            $this->fields->setItem($field->getName(), $field);
        }
    }

    public function getDefaultFieldnames()
    {
        return array_keys($this->getDefaultFields());
    }

    protected function getDefaultFields()
    {
        return array();
    }

    protected function getFieldByPath($field_path)
    {
        $path_parts = explode('.', $field_path);
        if ($path_parts % 2 === 0) {
            throw new RuntimeException(
                "Invalid fieldpath(fieldname) given. Fieldparts must be made up of " .
                "'fieldname.module_prefix.fieldname' parts with a single final fieldname."
            );
        }

        $path_tuples = array();
        $next_tuple = array();
        for ($i = 1; $i <= count($path_parts); $i++) {
            $next_tuple[] = $path_parts[$i - 1];
            if ($i % 2 === 0) {
                $path_tuples[] = $next_tuple;
                $next_tuple = array();
            }
        }

        $destination_field = end($path_parts);
        $current_module = $this;
        foreach ($path_tuples as $path_tuple) {
            $current_field = $current_module->getField($path_tuple[0]);
            if ($current_field instanceof AggregateField) {
                $current_module = $current_field->getAggregateModuleByPrefix($path_tuple[1]);
            } elseif ($current_field instanceof ReferenceField) {
                $current_module = $current_field->getReferencedModuleByPrefix($path_tuple[1]);
            } else {
                throw new RuntimeException(
                    "Invalid field-type given within field-path. Only Reference- and AggregateFields are supported."
                );
            }
        }

        return $current_module->getField($destination_field);
    }
}
