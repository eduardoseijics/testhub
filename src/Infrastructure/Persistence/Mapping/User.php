<?php

namespace App\Identity\Infrastructure\Persistence\Mapping;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

final class User
{
  public static function loadMetadata(ClassMetadata $metadata): void
  {
    $builder = new ClassMetadataBuilder($metadata);

    $builder->setTable('users');

    $builder->createField('id', 'string')
      ->columnName('id')
      ->length(36)
      ->makePrimaryKey()
      ->generatedValue('NONE')
      ->build();

    $builder->addField('name', 'string', ['length' => 255]);

    $builder->addField('email', 'string', [
      'length' => 255,
      'unique' => true,
    ]);

    $builder->addField('hashedPassword', 'string', [
      'columnName' => 'hashed_password',
      'length'     => 255,
    ]);

    $builder->addField('profile', 'string', ['length' => 50]);
    $builder->addField('status', 'string', ['length' => 20]);
    $builder->addField('permissions', 'json');

    $builder->addField('createdAt', 'datetime_immutable', [
      'columnName' => 'created_at',
    ]);
  }
}
