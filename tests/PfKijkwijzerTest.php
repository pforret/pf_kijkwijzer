<?php

namespace Pforret\PfKijkwijzer\Tests;

use Pforret\PfKijkwijzer\PfKijkwijzer;
use PHPUnit\Framework\TestCase;

final class PfKijkwijzerTest extends TestCase
{
    public function testSearchWithYear(): void
    {
        $obj = new PfKijkwijzer();
        $results = $obj->search('Top Gun', 1986);
        $this->assertNotEmpty($results);
        $this->assertNotEmpty($results[0]);
        $this->assertNotEmpty($results[0]->release_year);
    }

    public function testSearchWithoutYear(): void
    {
        $obj = new PfKijkwijzer();
        $results = $obj->search('Paw Patrol');
        $this->assertNotEmpty($results);
        $this->assertNotEmpty($results[0]);
        $this->assertEquals('Alle leeftijden',$results[0]->rating);
    }

    public function testSearchInEnglish(): void
    {
        $obj = new PfKijkwijzer(PfKijkwijzer::DOMAIN_KIJKWIJZER_BE, PfKijkwijzer::LANG_EN);
        $results = $obj->search('Paw Patrol');
        $this->assertNotEmpty($results);
        $this->assertNotEmpty($results[0]);
        $this->assertEquals('All ages',$results[0]->rating);
    }

    public function testSearchOnlyMovies(): void
    {
        $obj = new PfKijkwijzer();
        $results = $obj->search('Avatar', -1, 5, PfKijkwijzer::FILTER_MOVIES);
        $this->assertNotEmpty($results);
        $this->assertNotEmpty($results[0]);
        $this->assertEquals('Speelfilm', $results[0]->type);
    }

    public function testFirst(): void
    {
        $obj = new PfKijkwijzer();
        $movie = $obj->first('Avatar', -1, 5, PfKijkwijzer::FILTER_MOVIES);
        $this->assertNotEmpty($movie);
        $this->assertEquals('Speelfilm', $movie->type);

    }
}
