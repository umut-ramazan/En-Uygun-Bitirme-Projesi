<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Services\CategoryServices;
use App\Services\ShoppingServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Exception;

class ClientController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private RequestStack $requestStack;
    private ProductRepository $productRepository;
    private ShoppingCartRepository  $shoppingCartRepository;
    private OrdersRepository $ordersRepository;
    private Security $security;

    public function __construct(
        CategoryRepository $categoryRepository,
        RequestStack $requestStack ,
        ProductRepository $productRepository ,
        ShoppingCartRepository  $shoppingCartRepository ,
        OrdersRepository $ordersRepository,
        Security $security
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->ordersRepository = $ordersRepository;
        $this->security = $security;
    }

    /**
     * @return array
     */
    private function BuildCategory(): array
    {
        $categories = $this->categoryRepository->findAll();
        $build= new CategoryServices($this->categoryRepository);

      return  $buildCategory= $build->buildTree($categories);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/client", name="app_client")
     */
    public function index(Request $request): Response
    {
        $categoryName = 'Tüm Ürünler';
        $this->BuildCategory();
        $products = $this->productRepository->productsFindByAll();
         if ($request->get('k')){

             $products = $this->productRepository->productsFindByCategory($request->get('k'));
             $categoryName = $request->get('k');
         }
         return $this->render('client/index.html.twig', [
             'controller_name' => $categoryName,
             'buildCategories'=>$this->BuildCategory(),
             'products'=> $products

         ]);
    }


    /**
     * @Route("/client/shopping-cart", name="app_shoppingCart")
     */
    public function shoppingCart(): Response
    {
        $this->BuildCategory();

        return $this->render('client/shoppingCart.html.twig', [
            'controller_name' => 'ClientController',
            'buildCategories'=>$this->BuildCategory()

        ]);
    }

    /**
     * @Route("/client/shopping/process", name="app_shoppingprocess")
     * @throws Exception
     */
    public function shoppingProcess(Request $request): Response
    {
        $shoppingServices = new ShoppingServices(
            $this->requestStack,
            $this->productRepository,
            $this->shoppingCartRepository,
            $this->ordersRepository,
            $this->categoryRepository
        );

        if ($request->get('p')=="addToCart"){
              ob_start();
              echo $shoppingServices->addToCart($request->get("productId"));
        }
        if ($request->get('p')=="incCount"){
            if($shoppingServices->incCount($request->get('productId'))){
                return  $this->redirectToRoute('app_shoppingCart');
            }
        }

        if ($request->get('p')=="decCount"){
            if($shoppingServices->decCount($request->get('productId'))){
                return  $this->redirectToRoute('app_shoppingCart');
            }
        }

        if ($request->get('p')=="removeFromCart"){

            if($shoppingServices->removeFromCart($request->get('productId'))){
                return  $this->redirectToRoute('app_shoppingCart');
            }

        }

        if ($this->isCsrfTokenValid('orderAddToken', $request->get('token')) )
        {
            $orderObjet = array([
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'orderOption' => $request->get('orderOption')
                ]);

            if (!$shoppingServices->orderProduct($orderObjet,$this->security->getUser())){
                return  $this->redirectToRoute('app_shoppingCart');
            }

        }

        return new Response('');

    }

    /**
     * @Route("/client/orders", name="app_orders")
     */
    public function orders(): Response
    {
        $this->BuildCategory();
        $user = $this->security->getUser();
        $orders = $this->ordersRepository->findByUserOrders($user->getId());

        return $this->render('client/ordersCart.html.twig', [
            'controller_name' => 'ClientController',
            'buildCategories'=> $this->BuildCategory(),
            'Orders' => $orders
        ]);
    }
}
