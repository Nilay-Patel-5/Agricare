<?php

namespace MongoDB\BSON;

class Regex
{
    public function __construct(string $pattern, string $flags = "") {}
}

class ObjectId
{
    public function __construct(?string $id = null) {}
}

class UTCDateTime
{
    public function __construct($milliseconds = null) {}
}