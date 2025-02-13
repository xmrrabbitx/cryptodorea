<?php

namespace Cryptodorea\DoreaCashback\controllers;

use Cryptodorea\DoreaCashback\abstracts\productAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class productController extends productAbstract
{
    /**
     * list product categories of woocommerce
     * @return void
     */
    public function listCategories():array
    {
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
        ));

        $categoriesList  = [];
        // Check if there are any categories
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $categoriesList[] = $category->name;
            }
        }

        return $categoriesList;
    }
}