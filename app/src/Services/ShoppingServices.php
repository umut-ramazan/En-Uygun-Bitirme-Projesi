<?php

namespace App\Services;

use App\Entity\Orders;
use App\Entity\ShoppingCart;
use App\Repository\CategoryRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class ShoppingServices
{

    private RequestStack $requestStack;
    private ProductRepository $productRepository;
    private ShoppingCartRepository $shoppingCartRepository;
    private OrdersRepository $ordersRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        RequestStack           $requestStack,
        ProductRepository      $productRepository,
        ShoppingCartRepository $shoppingCartRepository,
        OrdersRepository       $ordersRepository,
        CategoryRepository     $categoryRepository
    )
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->ordersRepository = $ordersRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Adding a product from the cart
     *
     * @param $productId
     * @return int|mixed
     */
    public function addToCart($productId)
    {
        $totalPrice = 0.0;
        $totalCount = 0;

        $session = $this->requestStack->getSession();
        $data = $this->productRepository->shopping($productId);


        $data[0]['count'] = 1;

        if ($session->get('shoppingCart') && $session->get('shoppingCartSummery')) {
            $shoppingCart = $session->get('shoppingCart');
            $shoppingSummery = $session->get('shoppingCartSummery');
            $products = $shoppingCart;
            $summers = $shoppingSummery;
        } else {
            $products = array();
        }

        if (array_key_exists($data[0]['id'], $products)) {
            $products[$productId][0]['count'] += 1;
        } else {
            $products[$data[0]['id']] = $data;
        }

        foreach ($products as $product) {

            $product[0]['totalPrice'] = $product[0]['count'] * $product[0]['productPrice'];
            $totalPrice += $product[0]['totalPrice'];
            $totalCount += $product[0]['count'];
            $products[$productId][0]['total'] = $products[$productId][0]['count'] * $products[$productId][0]['productPrice'];

        }

        $summers['totalCount'] = $totalCount;
        $summers['totalPrice'] = $totalPrice;

        $session->set('shoppingCart', $products);
        $session->set('shoppingCartSummery', $summers);

        return $totalCount;
    }

    /**
     * Deleting a product from the cart
     *
     * @param $productId
     * @return bool
     */
    public function removeFromCart($productId): bool
    {
        $totalPrice = 0.0;
        $totalCount = 0;
        $session = $this->requestStack->getSession();

        if ($session->get('shoppingCart') && $session->get('shoppingCartSummery')) {
            $shoppingCart = $session->get('shoppingCart');
            $shoppingSummery = $session->get('shoppingCartSummery');
            $products = $shoppingCart;
            $summers = $shoppingSummery;

            if (array_key_exists($productId, $products)) {
                unset($products[$productId]);

            }

            foreach ($products as $product) {

                $product[0]['totalPrice'] = $product[0]['count'] * $product[0]['productPrice'];
                $totalPrice += $product[0]['totalPrice'];
                $totalCount += $product[0]['count'];

            }

            $summers['totalCount'] = $totalCount;
            $summers['totalPrice'] = $totalPrice;

            $session->set('shoppingCart', $products);
            $session->set('shoppingCartSummery', $summers);

            return true;
        }
        return false;
    }

    /**
     * Increase the number of products
     *
     * @param $productId
     * @return int
     */
    public function incCount($productId): int
    {
        $session = $this->requestStack->getSession();


        if ($session->get('shoppingCart') && $session->get('shoppingCartSummery')) {
            $shoppingCart = $session->get('shoppingCart');
            $shoppingSummery = $session->get('shoppingCartSummery');
            $products = $shoppingCart;
            $summers = $shoppingSummery;

            if (array_key_exists($productId, $products)) {
                $products[$productId][0]['count'] += 1;

            }

            //sepet hesaplama
            $totalPrice = 0.0;
            $totalCount = 0;


            foreach ($products as $product) {
                $product[0]['totalPrice'] = $product[0]['count'] * $product[0]['productPrice'];
                $totalPrice += $product[0]['totalPrice'];
                $totalCount += $product[0]['count'];
                $products[$productId][0]['total'] = $products[$productId][0]['count'] * $products[$productId][0]['productPrice'];

            }


            $summers['totalCount'] = $totalCount;
            $summers['totalPrice'] = $totalPrice;

            $session->set('shoppingCart', $products);
            $session->set('shoppingCartSummery', $summers);

            return $totalCount;

        }

        return false;

    }

    /**
     * Reducing the number of products
     *
     * @param $productId
     * @return bool
     */
    public function decCount($productId): bool
    {
        $totalPrice = 0.0;
        $totalCount = 0;
        $session = $this->requestStack->getSession();

        if ($session->get('shoppingCart') && $session->get('shoppingCartSummery')) {
            $shoppingCart = $session->get('shoppingCart');
            $shoppingSummery = $session->get('shoppingCartSummery');
            $products = $shoppingCart;
            $summers = $shoppingSummery;

            if (array_key_exists($productId, $products)) {

                if ($products[$productId][0]['count'] > 1) {
                    $products[$productId][0]['count'] -= 1;
                } else {
                    unset($products[$productId]);

                }

            }

            foreach ($products as $product) {

                $product[0]['totalPrice'] = $product[0]['count'] * $product[0]['productPrice'];
                $totalPrice += $product[0]['totalPrice'];
                $totalCount += $product[0]['count'];

            }

            $summers['totalCount'] = $totalCount;
            $summers['totalPrice'] = $totalPrice;

            $session->set('shoppingCart', $products);
            $session->set('shoppingCartSummery', $summers);

            return true;
        }
        return false;

    }


    /**
     * Order Control order processing
     *
     * @param $orderObjet
     * @param $user
     * @return bool
     * @throws Exception
     */
    public function orderProduct($orderObjet, $user): bool
    {
        $pattern = '/5[0,3,4,5,6][0-9]\d\d\d\d\d\d\d$/';

        $session = $this->requestStack->getSession();

        if (preg_match($pattern, $orderObjet['0']['phone'])) {

            if ($orderObjet[0]['orderOption'] == 'paymentByDoor') {

                if ($session->get('shoppingCart') && $session->get('shoppingCartSummery')) {
                    $date = new \DateTime('@' . strtotime('now'));
                    $shopping = new ShoppingCart();
                    $orders = new Orders();

                    $shoppingCart = array_values($session->get('shoppingCart'));

                    $discount = [];
                    $discountCategory = [];
                    $discountCategories = $this->categoryRepository->findDiscount();


                    foreach ($shoppingCart as $data) {
                        $summers = $session->get('shoppingCartSummery');

                        $product = $this->productRepository->find($data[0]['id']);
                        $product
                            ->setProductPiece($product->getProductPiece() - $data[0]['count']);

                        $this->productRepository->update($product, true);

                        /** Check out 50% discount on 2nd product*/
                        if (count($shoppingCart) == 2) {
                            $discount[] = $data[0]['productPrice'];
                        }

                        /** Check out  3 pay 1 camping product*/
                        if (count($shoppingCart) == 3) {

                            $productByCategory = $this->productRepository->findProductByCategories($data[0]['id']);

                            foreach ($productByCategory as $item) {

                                foreach ($item->getCategories()->getValues() as $category){

                                    if (!empty($discountCategories) && array_key_exists($category->getCategoryName() , $discountCategories )) {
                                        $discountCategory[] = $data[0]['productPrice'];
                                    }

                                }

                            }

                        }

                    }

                   
                    if (count($discount) === 2) {
                        $summers['totalPrice'] = $summers['totalPrice'] - min($discount) / 2;
                    }

                    if (count($discountCategory) == 3) {

                       $summers['totalPrice'] = max($discountCategory);
                    }

                    $shopping
                        ->setProductIds($shoppingCart)
                        ->setSummersIds($summers);

                    if ($this->shoppingCartRepository->add($shopping, true)) {

                        $orders
                            ->setUserId($user->getId())
                            ->setAddress($orderObjet[0]['address'])
                            ->setOrderOption($orderObjet[0]['orderOption'])
                            ->setPhoneNumber($orderObjet[0]['phone'])
                            ->setShoppingCartId($shopping)
                            ->setStatus('process')
                            ->setCreatedAt($date);

                        if ($this->ordersRepository->add($orders, true)) {
                            $session->set('shoppingCart', []);
                            $session->set('shoppingCartSummery', []);
                        }

                    }

                }

            }

            return false;
        }

        return false;
    }
}
