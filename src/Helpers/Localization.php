<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Localization.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 20/09/2024 at 10:14 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Helpers;

use Wanpeninsula\AliDrop\Contracts\LocalizationInterface;
use Wanpeninsula\AliDrop\Traits\LoggerTrait;

class Localization implements LocalizationInterface
{
    use LoggerTrait;
    private static self $instance;

    protected string $language;
    protected string $currency;

    protected string $countryCode;
    private static string $assetPath = __DIR__ . '/../../assets/';

    protected array $languages = [];
    protected array $currencies = [];
    protected array $categories = [];
    protected array $countries = [];
    public function __construct()
    {
        $this->language = 'en';
        $this->currency = 'GHS';
        $this->countryCode = 'GH';

        if (file_exists(self::$assetPath . 'aliexpress_languages.json')) {
            $this->languages = json_decode(file_get_contents(self::$assetPath . 'aliexpress_languages.json'), true);
        }

        if (file_exists(self::$assetPath . 'aliexpress_currencies.json')) {
            $this->currencies = json_decode(file_get_contents(self::$assetPath . 'aliexpress_currencies.json'), true);
        }

        if (file_exists(self::$assetPath . 'aliexpress_countries.json')) {
            $this->countries = json_decode(file_get_contents(self::$assetPath . 'aliexpress_countries.json'), true);
        }

        if (file_exists(self::$assetPath . 'aliexpress_categories.json')) {
            $this->categories = json_decode(file_get_contents(self::$assetPath . 'aliexpress_categories.json'), true);
        }

    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setLanguage(string $language): void
    {
        if (!in_array($language, array_column($this->languages, 'lowercase'), true)) {
            $this->logWarning("Invalid language code: {$language}");
        }

        $this->language = $language;

    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setCurrency(string $currency): void
    {
        if (!in_array($currency, array_column($this->currencies, 'code'), true)) {
            $this->logWarning("Invalid currency code: {$currency}");
        }

        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCountryCode(string $countryCode): void
    {
        if (!in_array($countryCode, array_column($this->countries, 'code'), true)) {
            $this->logWarning("Invalid country code: {$countryCode}");
        }

        $this->countryCode = $countryCode;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Supported languages
     * @return list<array{
     *     lowercase: string,
     *     uppercase: string,
     *     country: string,
     * }>
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Supported currencies
     * @return list<array{
     *     code: string,
     *     name: string,
     *     custom: bool,
     * }>
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * Supported countries
     * @return list<array{
     *     code: string,
     *     country: string,
     *     phone_code: string,
     * }>
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * Supported categories
     * @return list<array{
     *     id: string,
     *     name: string,
     * }>
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get category name by id
     * @param string $categoryId
     * @return string
     */
    public function getCategoryName(string $categoryId): string
    {
        foreach ($this->categories as $category) {
            if ($category['id'] === $categoryId) {
                return $category['name'];
            }
        }
        return '';
    }

    /**
     * Get country codes
     * @return array
     */
    public function getCountryCodes(): array
    {
        return array_column($this->countries, 'code');
    }

    /**
     * Get currency codes
     * @return array
     */
    public function getCurrencyCodes(): array
    {
        return array_column($this->currencies, 'code');
    }

    /**
     * Get language codes
     * @return array
     */
    public function getLanguageCodes(): array
    {
        return array_column($this->languages, 'lowercase');
    }

    /**
     * Get category ids
     * @return array
     */
    public function getCategoryIds(): array
    {
        return array_column($this->categories, 'id');
    }

}
