<?php

namespace App\Project\Infrastructure\Persistence\Mapping;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

final class Project
{
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('projects');

        $builder->createField('id', 'string')
            ->columnName('id')
            ->length(36)
            ->makePrimaryKey()
            ->generatedValue('NONE')
            ->build();

        $builder->addField('name', 'string', ['length' => 255]);

        $builder->addField('slug', 'string', [
            'length' => 255,
            'unique' => true,
        ]);

        $builder->addField('description', 'string', [
            'length'   => 1000,
            'nullable' => true,
        ]);

        $builder->addField('ownerId', 'string', [
            'columnName' => 'owner_id',
            'length'     => 36,
        ]);

        $builder->addField('status', 'string', ['length' => 20]);

        $builder->addField('members', 'json');

        $builder->addField('createdAt', 'datetime_immutable', [
            'columnName' => 'created_at',
        ]);
    }
}
