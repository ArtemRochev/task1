<?php

namespace App\DoctrineType;

use App\Enum\TaskStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LogicException;

abstract class AbstractEnumType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $enums = [];

        foreach (TaskStatus::cases() as $status) {
            $enums[] = "'" . $status->value . "'";
        }

        return "ENUM(" . implode(", ", $enums) . ")";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (enum_exists($this->getEnumsClass()) === false) {
            throw new LogicException("This class should be an enum");
        }

        $enum = $this::getEnumsClass()::tryFrom($value);

        return $enum ? $enum->value : null;
    }

    abstract public static function getEnumsClass(): string;

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return false;
    }
}