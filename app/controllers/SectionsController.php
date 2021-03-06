<?php

use Phalcon\Mvc\Controller;

class SectionsController extends Controller {
  public function showAction($slug) {
    $tree = $this->di->getTree();
    $section = $tree->getBySlug($slug);
    $products = [];

    if(!$section) {
      $this->response->setStatusCode(404, "Not Found");
      // $this->view->pick("static/route404");
      $this->response->setContent("Sorry, the page doesn't exist");
      return $this->response;
    }

    $this->tag->prependTitle($section->NAME);
    $this->metatag->setByLink('canonical', ['href' => $this->url->get('catalog/' . mb_strtolower($section->CODE))]);
    $this->metatag->setByName('description', mb_substr(str_replace("\n", '', htmlspecialchars(strip_tags($section->DESCRIPTION))), 0, 300));
    $section_childs = $tree->getChilds($section->ID);
    $void = count($section_childs) == 0;

    $Parsedown = new Parsedown();

    $section->DESCRIPTION_HTML = $Parsedown->text($section->DESCRIPTION);

    if(!$void) {
      foreach($section_childs as $cs) {
        $in = $tree->getAllChilds($cs->ID);
        $in[] = $cs->ID;

        $results = Products::query()
          ->columns('Products.ID, IblockElements.NAME, IblockElements.DETAIL_PICTURE, ProductPrices.PRICE, ProductPrices.CURRENCY, IblockElements.SORT')
          ->innerJoin('IblockSectionElements', 'IblockSectionElements.IBLOCK_ELEMENT_ID = Products.ID')
          ->innerJoin('IblockElements', 'IblockElements.ID = Products.ID')
          ->innerJoin('ProductPrices', 'ProductPrices.PRODUCT_ID = Products.ID')
          ->inWhere('IblockSectionElements.IBLOCK_SECTION_ID', $in)
          ->andWhere('IblockElements.ACTIVE = :active:', ['active' => 'Y'])
          ->orderBy('IblockElements.SORT ASC, ProductPrices.PRICE ASC')
          ->limit(9)
          ->execute();

        $cs->PRODUCTS = array();
        foreach($results as $result) {
          $cs->PRODUCTS[] = $result;
        }

        $cs->PRODUCT = count($cs->PRODUCTS) > 5 ? array_shift($cs->PRODUCTS) : false;

      }
    } else {
      $products = Products::query()
        ->columns('Products.ID, IblockElements.NAME, IblockElements.DETAIL_PICTURE, ProductPrices.PRICE, ProductPrices.CURRENCY, IblockElements.SORT')
        ->innerJoin('IblockSectionElements', 'IblockSectionElements.IBLOCK_ELEMENT_ID = Products.ID')
        ->innerJoin('IblockElements', 'IblockElements.ID = Products.ID')
        ->innerJoin('ProductPrices', 'ProductPrices.PRODUCT_ID = Products.ID')
        ->where('IblockSectionElements.IBLOCK_SECTION_ID = :section:', ['section' => $section->ID])
        ->andWhere('IblockElements.ACTIVE = :active:', ['active' => 'Y'])
        ->orderBy('IblockElements.SORT ASC, ProductPrices.PRICE ASC')
        ->execute();
    }
    // die;

    // $products_all = array();




    $this->view->section = $section;
    $this->view->section_childs = $section_childs;
    $this->view->products = $products;
    $this->view->void = $void;
  }
}
