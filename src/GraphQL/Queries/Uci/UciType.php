<?php

namespace App\GraphQL\Queries\Uci;

use App\Utils\UciCommand;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Class used for load all schema for the UCI System in GraphQL.
 */
class UciType extends ObjectType
{
    /**
     * @var array
     */
    public $forbiddenConfigurations = [
        'ucitrack' => true,
    ];
    /**
     * @var Clousure|function
     */
    private $globalResolver;

    public function __construct()
    {
        $this->globalResolver = require_once 'Resolvers/UciCommand.php';

        $config = [
            'name' => 'uci',
            'description' => 'Router Configuration',
            'fields' => $this->getFields(),
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return ''; //$value->{$info->fieldName};
            },
        ];
        parent::__construct($config);
    }

    /**
     * Return an array with unique keys for each array.
     * @param array
     * @return array
     */
    private static function getUniqueKeys($section): array
    {
        /* Load All Unique Keys for the array */
        $allOptions = [];
        foreach ($section as $options) {
            foreach ($options as $optionName) {
                $allOptions[$optionName] = true;
            }
        }

        return array_keys($allOptions);
    }

    /**
     * @return FieldDefinition[]
     * @throws InvariantViolation
     */
    public function getFields()
    {
        if ($this->fields === null) {
            $fields = $this->getUciFields() ?? [];
            $this->fields = FieldDefinition::defineFieldMap($this, $fields);
        }

        return $this->fields;
    }

    /**
     * Return all fields in the uci configuration using GraphQL sintax.
     * @param void
     * @return array
     */
    public static function getUciFields(): array
    {
        $config = UciCommand::getUciConfiguration();

        $uciFields = [];
        foreach ($config as $configName => $sections) {
            $configFields = [];

            foreach ($sections as $sectionName => $section) {
                $sectionFields = [];
                $isArraySection = is_array($section);

                $allOptions = $isArraySection ? self::getUniqueKeys($section) : $section->options;

                foreach ($allOptions as $optionName) {
                    $sectionFields[$optionName] = self::getOptionType($configName, $sectionName, $optionName);
                }

                $configFields[$sectionName] = [
                    'type' => self::getSectionType($configName, $sectionName, $sectionFields, $isArraySection),
                ];
            }
            $uciFields[$configName] = [
                'type' => self::getConfigurationType($configName, $configFields),
            ];
        }

        return $uciFields;
    }

    private static function getConfigurationType($configName, $configFields)
    {
        return new ObjectType([
            'name' => $configName,
            'description' => "$configName UCI Configuration",
            'fields' => $configFields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $value->{$info->fieldName};
            },
        ]);
    }

    private static function getSectionType($configName, $sectionName, $sectionFields, $isArray)
    {
        $configObject = [
            'name' => $configName . '_' . $sectionName,
            'description' => "Section $sectionName for $configName",
            'fields' => $sectionFields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return ''; // $value->{$info->fieldName};
            },
        ];
        $configArray = [
            /* Plural section */
            'name' => 'listOf' . ucfirst($sectionName),
            'description' => "List of $sectionName section for $configName",
            'type' => Type::listOf(new ObjectType($configObject)),
        ];

        return new ObjectType($isArray ? $configArray : $configObject);
    }

    private static function getOptionType($configName, $sectionName, $optionName)
    {
        return [
            'name' => $optionName,
            'description' => "Option $optionName for $sectionName in $configName configuration",
            'type' => Type::string(),
            'resolve' => require_once 'Resolvers/UciCommand.php', //self::$globalResolver,
        ];
    }
}
