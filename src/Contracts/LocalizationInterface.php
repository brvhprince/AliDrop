<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: LocalizationInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 20/09/2024 at 10:40 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Contracts;

interface LocalizationInterface
{
    static public function getInstance();
    public function setLanguage(string $language);
    public function getLanguage(): string;
    public function setCurrency(string $currency);
    public function getCurrency(): string;
    public function setCountryCode(string $countryCode);
    public function getCountryCode(): string;
    public function getLanguages();
    public function getCurrencies();
    public function getCountries();
    public function getCategories();
    public function getCategoryName(string $categoryId): string;
    public function getCountryCodes(): array;
    public function getCurrencyCodes() : array;
    public function getLanguageCodes() : array;
    public function getCategoryIds();
    public function getCountry( string $countryCode): array;

}
