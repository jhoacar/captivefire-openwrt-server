<?php

namespace App\GraphQL\Queries\Uci;

use App\Utils\UciCommand;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use stdClass;

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
     * @var array
     */
    private $uciInfo = [];

    public function __construct()
    {
        $config = [
            'name' => 'uci',
            'description' => 'Router Configuration',
            'fields' => $this->getFields(),
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $this->uciInfo[$info->fieldName];
            },
        ];
        parent::__construct($config);
    }

    /**
     * Return an array with unique keys for each array.
     * @param array
     * @return array
     */
    private function getUniqueKeys($section): array
    {
        /* Load All Unique Keys for the array */
        $allOptions = [];
        foreach ($section as $options) {
            foreach ($options as $optionName => $content) {
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
    private function getUciFields(): array
    {
        $this->uciInfo = UciCommand::getUciConfiguration();

        $uciFields = [];
        foreach ($this->uciInfo as $configName => $sections) {
            $configFields = [];

            foreach ($sections as $sectionName => $section) {
                $sectionFields = [];
                $isArraySection = is_array($section);

                $allOptions = $isArraySection ? $this->getUniqueKeys($section) : array_keys($section->options);

                foreach ($allOptions as $optionName) {
                    $sectionFields[$optionName] = $this->getOptionType($configName, $sectionName, $optionName);
                }

                $configFields[$sectionName] = $this->getSectionType($configName, $sectionName, $sectionFields, $isArraySection);
            }
            $uciFields[$configName] = $this->getConfigurationType($configName, $configFields);
        }

        return $uciFields;
    }

    private function getConfigurationType($configName, $configFields)
    {
        return [
            'description' => "$configName UCI Configuration",
            'type' => new ObjectType([
                'name' => $configName,
                'fields' => $configFields,
                'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                    return $value[$info->fieldName];
                },
            ]),
        ];
    }

    private function getSectionType($configName, $sectionName, $sectionFields, $isArray)
    {
        $configObject = [
            'name' => $configName . '_' . $sectionName,
            'fields' => $sectionFields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                if ($value instanceof stdClass) {
                    return $value->options[$info->fieldName];
                } else {
                    return $value[$info->fieldName] ?? null;
                }
            },
        ];

        $configArray = [
            'name' => $sectionName,
            'description' => "List of $sectionName section for $configName",
            'type' => Type::listOf(new ObjectType($configObject)),
            'resolve' => function ($value, $args, $context, ResolveInfo $info) {
                return $value[$info->fieldName];
            },
        ];

        return $isArray ? $configArray : [
            'description' => "Section $sectionName for $configName",
            'type' => new ObjectType($configObject),
        ];
    }

    private function getOptionType($configName, $sectionName, $optionName)
    {
        return [
            'name' => $optionName,
            'description' => "Option $optionName for $sectionName in $configName configuration",
            'type' => Type::listOf(Type::string()),
        ];
    }
}
