<?php

namespace Pforret\PfKijkwijzer;

use Pforret\PfKijkwijzer\Format\KijkwijzerResult;
use Pforret\PfPageparser\PfPageparser;

class PfKijkwijzer
{
    const FILTER_ALL = 0;

    const FILTER_GAMES = 3;

    const FILTER_MOVIES = 1;

    const FILTER_OTHER = 4;

    const FILTER_SERIES = 2;

    const DOMAIN_KIJKWIJZER_BE = 'www.kijkwijzer.be';

    const DOMAIN_CINECHECK_BE = 'www.cinecheck.be';

    const DOMAIN_KIJKWIJZER_NL = 'www.kijkwijzer.nl';

    const LANG_DE = 'de';

    const LANG_EN = 'en';

    const LANG_FR = 'fr';

    const LANG_NL = 'nl';

    const USERAGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';

    const LABELS = [
        self::LANG_NL => [
            'alle-leeftijden' => 'Alle leeftijden',
            'leeftijd-6' => 'Leeftijd 6',
            'leeftijd-9' => 'Leeftijd 9',
            'leeftijd-12' => 'Leeftijd 12',
            'leeftijd-14' => 'Leeftijd 14',
            'leeftijd-16' => 'Leeftijd 16',
            'leeftijd-18' => 'Leeftijd 18',
            'geweld' => 'Geweld',
            'angst' => 'Angst',
            'seks' => 'Seks',
            'discriminatie' => 'Discriminatie',
            'drugs' => 'Roken, alcohol en drugs',
            'grof-taalgebruik' => 'Grof taalgebruik',
        ],
        self::LANG_FR => [
            'alle-leeftijden' => 'Tous ages',
            'leeftijd-6' => '6 ans',
            'leeftijd-9' => '9 ans',
            'leeftijd-12' => '12 ans',
            'leeftijd-14' => '14 ans',
            'leeftijd-16' => '16 ans',
            'leeftijd-18' => '18 ans',
            'geweld' => 'Violence',
            'angst' => 'Peur',
            'seks' => 'Sexe',
            'discriminatie' => 'Discrimination',
            'drugs' => 'Drogues, alcool & fumer',
            'grof-taalgebruik' => 'Paroles grossières',
        ],
        self::LANG_EN => [
            'alle-leeftijden' => 'All ages',
            'leeftijd-6' => '6 years',
            'leeftijd-9' => '9 years',
            'leeftijd-12' => '12 years',
            'leeftijd-14' => '14 years',
            'leeftijd-16' => '16 years',
            'leeftijd-18' => '18 years',
            'geweld' => 'Violence',
            'angst' => 'Fear',
            'seks' => 'Sex',
            'discriminatie' => 'Discrimination',
            'drugs' => 'Drugs, alcohol or smoking',
            'grof-taalgebruik' => 'Foul language',
        ],
        self::LANG_DE => [
            'alle-leeftijden' => 'Alle Altersklassen',
            'leeftijd-6' => '6 jahre',
            'leeftijd-9' => '9 jahre',
            'leeftijd-12' => '12 jahre',
            'leeftijd-14' => '14 jahre',
            'leeftijd-16' => '16 jahre',
            'leeftijd-18' => '18 jahre',
            'geweld' => 'Gewalt',
            'angst' => 'Angst',
            'seks' => 'Sex',
            'discriminatie' => 'Diskriminierung',
            'drugs' => 'Drogen, alkohol and rauchen',
            'grof-taalgebruik' => 'Grober sprachgebrauch',
        ],
    ];

    private bool $only_exact_year;

    private string $domain;

    /**
     * @var array|string[]
     */
    private array $mapping;

    public function __construct(string $domain = self::DOMAIN_KIJKWIJZER_BE, string $language = self::LANG_NL, bool $only_exact_year = false)
    {
        $this->domain = $domain;
        $this->only_exact_year = $only_exact_year; // otherwise a release year +- 1 of requested year is allowed
        $this->mapping = self::LABELS[$language] ?? self::LABELS[self::LANG_NL];
    }

    final public function first(string $movie_title, int $movie_year = -1, int $max = 10, int $filter = self::FILTER_ALL): ?KijkwijzerResult
    {
        return $this->search($movie_title, $movie_year, $max, $filter)[0] ?? null;
    }

    final public function search(string $movie_title, int $movie_year = -1, int $max = 10, int $filter = self::FILTER_ALL): array
    {
        // ex: "https://www.kijkwijzer.be/zoeken/?query=avatar&producties=4";
        $url = sprintf('https://%s/zoeken/?query=%s&producties=%d', $this->domain, urlencode(strtolower($movie_title)), $filter);
        $parser = new PfPageparser();
        $parser->load_from_url($url);
        $parser->cleanup_html();
        $parser->trim('<div class="c-search__panels">');
        $parser->split_chunks('<div class="c-search__result">');
        $parser->filter_chunks(['c-search__image']);
        $chunks = $parser->get_chunks();
        $results = [];
        $result_count = 0;
        foreach ($chunks as $chunk) {
            if ($result_count >= $max) {
                break;
            }
            /*
            <div class="c-search__result">
                        <div class="c-search__picture c-search__picture--fallback">
                            <img class="c-search__image" src="/Assets/Images/placeholder-cover.png" alt="">
                        </div>
                    <div class="l-grid__item l-grid__item--xs">
                        <h3 class="l-type__heading l-type__heading--h4 c-search__title">Avatar</h3>
                        <p class="c-search__text">
                            Promo
            (2009)            </p>

                            <div class="c-search__marks">
                                    <span class="c-search__mark c-search__mark--leeftijd-12">
                                        <svg class="c-search__markicon">
                                            <use xlink:href="/Assets/Icons/icons.svg#leeftijd-12"></use>
                                        </svg>
                                    </span>
                                    <span class="c-search__mark c-search__mark--angst">
                                        <svg class="c-search__markicon">
                                            <use xlink:href="/Assets/Icons/icons.svg#angst"></use>
                                        </svg>
                                    </span>
                            </div>
                        <a href="/overige/avatar-1/" class="c-search__hiddenlink"></a>
                    </div>
            </div>
            */
            $movie_title = $this->preg_get('/<h3 [^>]+>([^<]+)<\/h3>/', $chunk);
            if (! $movie_title) {
                continue;
            }
            $type = $this->preg_get('/<p class="c-search__text">([^<]+)<\/p>/', $chunk);
            $year = (int) $this->preg_get('/\((\d+)\)/', $type);
            if ($year) {
                $max_year_diff = $this->only_exact_year ? 0 : 1;
                if ($movie_year > 0 && abs($movie_year - $year) > $max_year_diff) {
                    continue;
                }

                $movie_title = trim(str_replace(["($year)", $year], '', $movie_title));
                $type = trim(str_replace(["($year)", $year], '', $type));
            }
            $url = $this->preg_get('/<a href="([^"]+)"/', $chunk);
            if (str_starts_with($url, '/')) {
                $url = 'https://'.$this->domain.$url;
            }
            preg_match_all('|<use xlink:href="/Assets/Icons/icons.svg#([\w-]+)"></use>|', $chunk, $matches);
            $warnings = [];
            $rating = '';
            $minimum_age = 0;
            foreach ($matches[1] as $label) {
                if (str_starts_with($label, 'leeftijd-') || $label == 'alle-leeftijden') {
                    $rating = $this->mapping[$label] ?? $label;
                    $minimum_age = $label == 'alle-leeftijden' ? 1 : (int) $this->preg_get('/(\d+)/', $label);

                    continue;
                }
                $warnings[] = $this->mapping[$label] ?? $label;
            }
            $result = new KijkwijzerResult();
            $result->title = $this->cleanup_title($movie_title);
            $result->url = $url;
            $result->release_year = $year;
            $result->type = $type;
            $result->rating = $rating;
            $result->minimum_age = $minimum_age;
            $result->warnings = $warnings;
            $results[] = $result;
            $result_count++;

        }

        return $results;

    }

    private function preg_get(string $pattern, string $subject): string
    {
        if (! $pattern) {
            return '';
        }
        $matches = [];
        preg_match($pattern, $subject, $matches);

        return $matches[1] ?? '';
    }

    private function cleanup_title(string $title): string
    {
        return ucwords(strtolower(trim($title)));
    }
}
