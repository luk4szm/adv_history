<?php

declare(strict_types=1);

namespace App\Service\Otodom;

use App\Dto\OtodomAdvertisementDataDto;

class FetchOtodomAdvertisementDataService
{
    public array $ad;

    /**
     * Read the website source, extract the offer data and save it in dto
     *
     * @param string $siteContent
     * @return OtodomAdvertisementDataDto
     * @throws \Exception
     */
    public function fetch(string $siteContent): OtodomAdvertisementDataDto
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($siteContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $data     = json_decode($dom->getElementById('__NEXT_DATA__')->textContent, true);
        $this->ad = $ad = $data['props']['pageProps']['ad'];

        return new OtodomAdvertisementDataDto(
            $ad['status'],
            $ad['url'],
            $ad['title'],
            $ad['location']['address']['city']['name'],
            $ad['target']['Price'],
            (float)$ad['target']['Area'],
            isset($ad['target']['Terrain_area']) ? (float)$ad['target']['Terrain_area'] : null,
            isset($ad['target']['Build_year']) ? (int)$ad['target']['Build_year'] : null,
            $ad['target']['Rooms_num'],
            $ad['target']['Building_type'] ?? null,
            $ad['target']['Extras_types'] ?? null,
            $ad['target']['Heating_types'] ?? null,
            $ad['target']['Media_types'] ?? null,
            $ad['owner'],
            new \DateTime($ad['createdAt']),
            $ad['modifiedAt'] ? new \DateTime($ad['modifiedAt']) : null,
        );
    }
}
