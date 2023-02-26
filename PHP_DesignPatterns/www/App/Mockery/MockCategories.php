<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Mockery;

use App\Concretes\Categories as CategoriesModel;
use App\Contracts\Mockery;

class MockCategories implements Mockery
{
    public array $categories = [];
    public function add(array $data): void
    {
        $this->categories[] = new CategoriesModel($data['categories']);
    }
}
