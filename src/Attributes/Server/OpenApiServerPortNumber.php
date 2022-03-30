<?php
declare(strict_types=1);

namespace PrinsFrank\JsonapiOpenapiSpecGenerator\Attributes\Server;

use Attribute;

#[Attribute]
class OpenApiServerPortNumber implements OpenApiServerAttribute
{
    /** @var int[] */
    public array $portNumbers;

    public function __construct(int ... $portNumbers)
    {
        $this->portNumbers = $portNumbers;
    }
}
