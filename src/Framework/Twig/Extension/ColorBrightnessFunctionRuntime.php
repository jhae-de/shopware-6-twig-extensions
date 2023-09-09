<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Framework\Twig\Extension;

use InvalidArgumentException;
use function array_map;
use function ceil;
use function dechex;
use function filter_var;
use function implode;
use function ltrim;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_pad;
use function str_split;
use function strlen;
use function strtoupper;

/**
 * Color brightness function runtime class
 */
class ColorBrightnessFunctionRuntime
{
    public const HEX_COLOR_REGEXP_PATTERN = '/^#?(?:[A-Fa-f0-9]{3}){1,2}$/';

    /**
     * Adjust hex color brightness
     *
     * @throws \InvalidArgumentException
     */
    public function adjustHexColorBrightness(string $color, int $percent): string
    {
        $this->validateHexColor($color);
        $this->validatePercent($percent);

        $withHashSymbol = $color[0] === '#';
        $color = ltrim($color, '#');

        if (strlen($color) === 3) {
            $color = preg_replace('/(.)/', '$1$1', $color);
        }

        $rgbValues = array_map('\hexdec', str_split($color, 2));
        $hexValues = [];

        foreach ($rgbValues as $rgbValue) {
            $adjustmentLimit = $percent < 0 ? $rgbValue : 255 - $rgbValue;
            $adjustmentValue = (int)ceil($adjustmentLimit * $percent / 100);

            $hexValues[] = str_pad(dechex($rgbValue + $adjustmentValue), 2, '0', STR_PAD_LEFT);
        }

        return sprintf('%s%s', $withHashSymbol ? '#' : '', strtoupper(implode($hexValues)));
    }

    /**
     * Validate hex color
     *
     * @throws \InvalidArgumentException
     */
    protected function validateHexColor(string $value): void
    {
        if (preg_match(self::HEX_COLOR_REGEXP_PATTERN, $value)) {
            return;
        }

        throw new InvalidArgumentException('The color must be a 3-digit or 6-digit hexadecimal value.');
    }

    /**
     * Validate percent
     *
     * @throws \InvalidArgumentException
     */
    protected function validatePercent(int $value): void
    {
        $filterOptions = [
            'options' => [
                'min_range' => -100,
                'max_range' => 100,
            ],
        ];

        if (filter_var($value, FILTER_VALIDATE_INT, $filterOptions) !== false) {
            return;
        }

        throw new InvalidArgumentException('The percent value must be an integer between -100 and 100.');
    }
}
