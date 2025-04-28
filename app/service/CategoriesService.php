<?php

include __DIR__.'/../model/Categories.php';

function listCategories() {
    return getCategoriesList();
}

function categoryExists($categoriaDescricao) {
    $categorias = listCategories();

    if (!$categorias) return false;

    foreach ($categorias as $categoria) {
        if (strtolower(trim($categoria['categoria_descricao'])) === strtolower(trim($categoriaDescricao))) {
            return true;
        }
    }

    return false;
}