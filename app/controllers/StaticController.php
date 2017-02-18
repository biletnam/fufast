<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class StaticController extends Controller {
  public function logoAction() {
    $this->tag->prependTitle('Нанесение логотипов на спецодежду');

  }

  public function indexAction() {
    $this->tag->setTitle('Фуфайка — интернет-магазин спецодежды, спецобуви, перчаток, рукавиц и средств защиты');
    $this->metatag->setByLink('canonical', ['href' => $this->url->get('')]);

    $sections = [
      [
        'section' => [
          'title' => 'Спецодежда',
          'button' => 'Коллекции спецодежды',
          'url' => 'overall',
        ],
        'goods' => [2577, 2030, 2646, 2499, 1677, 2639, 1682, 1513]
      ],
      [
        'section' => [
          'title' => 'Спецобувь',
          'button' => 'Коллекции спецобуви',
          'url' => 'footwear',
        ],
        'goods' => [2127, 792, 795, 762, 731, 737, 663, 805]
      ],
      [
        'section' => [
          'title' => 'Средства защиты',
          'button' => 'Коллекции средств защиты',
          'url' => 'remedies',
        ],
        'goods' => [1744, 1731, 949, 1007, 2101, 2097, 1781, 1687]
      ],
      [
        'section' => [
          'title' => 'Перчатки и рукавицы',
          'button' => 'Коллекции перчаток',
          'url' => 'gloves',
        ],
        'goods' => [1711, 1709, 1301, 1796, 1301, 1796, 1803, 1261]
      ]
    ];

    foreach($sections as $k => $v) {
      $sections[$k]['goods'] = Products::query()
        ->columns('Products.ID, IblockElements.NAME, IblockElements.DETAIL_PICTURE, ProductPrices.PRICE, ProductPrices.CURRENCY')
        ->innerJoin('IblockSectionElements', 'IblockSectionElements.IBLOCK_ELEMENT_ID = Products.ID')
        ->innerJoin('IblockElements', 'IblockElements.ID = Products.ID')
        ->innerJoin('ProductPrices', 'ProductPrices.PRODUCT_ID = Products.ID')
        ->inWhere('Products.ID', $v['goods'])
        ->distinct(true)
        ->execute()
        ->toArray();

      $sections[$k]['goods'] = array_chunk($sections[$k]['goods'], 2);
    }


    $this->view->sections = $sections;

  }

  public function catalogAction() {
    $this->response->redirect('/');
  }

  public function aboutAction() {
    $this->tag->prependTitle('О компании «Поволжье-спецодежда»');
  }

  public function contactsAction() {
    $this->tag->prependTitle('Контакты');
  }

  public function route404Action() {
    // $this->view->pick("error/index");
    // $this->tag->prependTitle('Ошибка 404');

    // $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
    // $this->response->setStatusCode(404, "Not Found");
    // return $this->response;
  }
}