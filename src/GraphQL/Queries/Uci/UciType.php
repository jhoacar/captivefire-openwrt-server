<?php

namespace App\GraphQL\Queries\Uci;

use App\Utils\Command;
use App\Utils\UciCommand;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class UciType extends ObjectType
{
    /**
     * @var array
     */
    public $forbiddenConfigurations = [
        'ucitrack' => true
    ];

    public function __construct()
    {
        $config = [
            'name' => 'uci',
            'description' => 'Router Configuration',
            'fields' => $this->getFields(),
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $value->{$info->fieldName};
            }
        ];
        parent::__construct($config);
    }

    /**
     * @return FieldDefinition[]
     *
     * @throws InvariantViolation
     */
    public function getFields()
    {
        if ($this->fields === null) {
            $fields       = $this->getUciFields() ?? [];
            $this->fields = FieldDefinition::defineFieldMap($this, $fields);
        }

        return $this->fields;
    }

    /**
     * Return all fields in the uci configuration using GraphQL sintax
     */
    public static function getUciFields(): array
    {
        $config = UciCommand::getUciConfiguration();

        $uciFields = [];
        foreach ($config as $configName => $sections) {
            $sectionFields = [];

            foreach ($sections as $section) {
                if (is_array($section)) {
                } elseif (is_object($section)) {
                }
            }
            $uciFields[$configName] = [
                'type' => self::getConfigurationField($configName, $sectionFields)
            ];
        }

        return $uciFields;
    }

    private static function getConfigurationField($configName, $sectionFields)
    {
        return new ObjectType([
            'name' => $configName,
            'description' => "UCI Configuration $configName",
            'fields' => $sectionFields,
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return $value->{$info->fieldName};
            }
        ]);
    }

    private function getSectionField($sectionName, $isArray)
    {
    }

    private function getOptionField($optionName)
    {
    }

    private function getOptionsConfigurations()
    {
    }

    /**
     * Only load the uci sections that not match with forbiddenConfigurations
     */
    private function getSectionsConfigurations()
    {
        // $this->loadUciConfigurations();

        $commandExtractSections = "uci show";
        foreach ($this->uciFields as $nameConfig) {
            $commandToExecute = str_replace('{{name}}', $nameConfig, $commandExtractSections);
            $sections = explode(PHP_EOL, Command::execute($commandToExecute));
            $forbiddenSections = $this->forbiddenConfigurations[$nameConfig] ?? [];
            foreach ($sections as $nameSection) {
                $forbidden = false;
                foreach ($forbiddenSections as $forbiddenName => $options) {
                    if ($nameSection === $forbiddenName) {
                        $forbidden = true;
                    }
                }
                if (!$forbidden) {
                    $this->uciFields[$nameConfig][$nameSection] = [];
                }
            }
        }
    }
}
