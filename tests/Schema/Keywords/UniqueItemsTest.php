<?php

declare(strict_types=1);

namespace OpenAPIValidationTests\Schema\Keywords;

use OpenAPIValidation\Schema\Exception\KeywordMismatch;
use OpenAPIValidation\Schema\SchemaValidator;
use OpenAPIValidationTests\Schema\SchemaValidatorTest;

final class UniqueItemsTest extends SchemaValidatorTest
{
    public function testItValidatesUniqueItemsNoValidationGreen() : void
    {
        $spec = <<<SPEC
schema:
  type: array
  items:
    type: integer
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = [1, 1];

        (new SchemaValidator())->validate($data, $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesUniqueItemsGreen() : void
    {
        $spec = <<<SPEC
schema:
  type: array
  items:
    type: integer
  uniqueItems: true
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = [1, 2];

        (new SchemaValidator())->validate($data, $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesUniqueItemsRed() : void
    {
        $spec = <<<SPEC
schema:
  type: array
  items:
    type: integer
  uniqueItems: true
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = [1, 1];

        try {
            (new SchemaValidator())->validate($data, $schema);
            $this->fail('Exception expected');
        } catch (KeywordMismatch $e) {
            $this->assertEquals('uniqueItems', $e->keyword());
        }
    }
}
