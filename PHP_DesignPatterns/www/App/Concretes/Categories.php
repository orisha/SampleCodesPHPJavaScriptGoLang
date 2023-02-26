<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\Categories as ContractsCategories;

class Categories implements ContractsCategories
{
    public function __construct(protected array $categories)
    {
        return $this;
    }
    public function categories(): array
    {
        return $this->categories;
    }
}
