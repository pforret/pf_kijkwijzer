<?php

namespace Pforret\PfKijkwijzer\Format;

class KijkwijzerResult
{
    public string $title = '';

    public string $url = '';

    public ?int $release_year = null;

    public string $type = '';

    public string $rating = '';

    public ?int $minimum_age = null;

    public array $warnings = [];
}
