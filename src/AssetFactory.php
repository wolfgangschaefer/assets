<?php

/*
 * This file is part of the Assets package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Inpsyde\Assets;

use Inpsyde\Assets\Loader\PhpFileLoader;
use Inpsyde\Assets\Loader\ArrayLoader;

/**
 * Class AssetFactory
 *
 * @package Inpsyde\Assets
 */
final class AssetFactory
{

    /**
     * @param array $config
     *
     * @return Asset
     * @throws Exception\MissingArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public static function create(array $config): Asset
    {
        self::validateConfig($config);

        $location = $config['location'] ?? Asset::FRONTEND;
        $handle = $config['handle'];
        $url = $config['url'];
        $class = (string) $config['type'];

        $asset = new $class($handle, $url, $location, $config);

        if (! $asset instanceof Asset) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'The given class "%s" is not implementing %s',
                    $class,
                    Asset::class
                )
            );
        }

        return $asset;
    }

    /**
     * @param array $config
     *
     * @throws Exception\MissingArgumentException
     */
    private static function validateConfig(array $config)
    {
        $requiredFields = [
            'type',
            'url',
            'handle',
        ];

        foreach ($requiredFields as $key) {
            if (! isset($config[$key])) {
                throw new Exception\MissingArgumentException(
                    sprintf(
                        'The given config <code>%s</code> is missing.',
                        $key
                    )
                );
            }
        }
    }

    /**
     * @param string $file
     *
     * @return array
     *
     * @throws Exception\FileNotFoundException
     * @deprecated PhpArrayFileLoader::load(string $filePath)
     *
     */
    public static function createFromFile(string $file): array
    {
        $loader = new PhpFileLoader();

        return $loader->load($file);
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws Exception\FileNotFoundException
     * @deprecated ArrayLoader::load(array $data)
     */
    public static function createFromArray(array $data): array
    {
        $loader = new ArrayLoader();

        return $loader->load($data);
    }
}
