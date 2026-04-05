<?php

declare(strict_types=1);

/**
 * Formats raw API character data for display — Portuguese translations,
 * safe fallbacks, wand string assembly.
 */
class CharacterFormatter
{
    private const HOUSE_NAMES = [
        'Gryffindor' => 'Grifinoria',
        'Slytherin' => 'Sonserina',
        'Ravenclaw' => 'Corvinal',
        'Hufflepuff' => 'Lufa-Lufa',
    ];

    private const FALLBACK_IMAGE = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"%3E%3Crect fill="%23111827" width="120" height="120"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" fill="%234b5563" font-family="Inter, sans-serif" font-size="14"%3E%3F%3C/text%3E%3C/svg%3E';

    /**
     * Normalize a character record for template consumption.
     *
     * @param array $raw
     * @return array
     */
    public static function normalize(array $raw): array
    {
        $house = $raw['house'] ?? '';
        $houseData = $house !== '' && isset(self::HOUSE_NAMES[$house])
            ? ['name' => self::HOUSE_NAMES[$house], 'key' => self::toKey($house)]
            : ['name' => $raw['house'] ?: 'Nao informada', 'key' => ''];

        $wand = is_array($raw['wand'] ?? null)
            ? self::formatWand($raw['wand'])
            : 'Desconhecida';

        return [
            'name' => self::field($raw['name'] ?? '', 'Desconhecido'),
            'actor' => self::field($raw['actor'] ?? null, 'Ator desconhecido'),
            'image' => self::field($raw['image'] ?? null, self::FALLBACK_IMAGE),
            'house' => $houseData,
            'species' => self::field($raw['species'] ?? null, 'Nao informada'),
            'gender' => self::field($raw['gender'] ?? null, 'Nao informado'),
            'dateOfBirth' => self::field($raw['dateOfBirth'] ?? null, 'Nao informada'),
            'ancestry' => self::field($raw['ancestry'] ?? null, 'Nao informada'),
            'hairColour' => self::field($raw['hairColour'] ?? null, 'Nao informada'),
            'eyeColour' => self::field($raw['eyeColour'] ?? null, 'Nao informada'),
            'patronus' => self::field($raw['patronus'] ?? null, 'Nao informado'),
            'wand' => $wand,
        ];
    }

    private static function field(?string $value, string $fallback): string
    {
        if ($value === null || trim($value) === '') {
            return $fallback;
        }
        return trim($value);
    }

    private static function toKey(string $house): string
    {
        return 'house-' . strtolower($house);
    }

    private static function formatWand(array $wand): string
    {
        $parts = array_filter([
            $wand['wood'] ?? '',
            $wand['core'] ?? '',
            isset($wand['length']) ? (string) $wand['length'] : '',
        ]);

        return $parts !== []
            ? implode(' \xB7 ', $parts)  // middle dot
            : 'Detalhes desconhecidos';
    }
}
