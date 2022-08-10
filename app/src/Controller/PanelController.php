<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\CategoryServices;



class PanelController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private OrdersRepository $ordersRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository  $productRepository,
        OrdersRepository   $ordersRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->ordersRepository = $ordersRepository;
    }

    /**
     * Returns Home Page
     *
     * @return Response
     * @Route("/panel", name="app_panel")
     */
    public function home(): Response
    {
        return $this->render('panel/home.html.twig', [
            'controller_name' => 'PanelController',
        ]);
    }

    /**
     * Returns the category page and category information
     *
     * @return Response
     * @Route("/panel/category", name="app_category")
     */
    public function category(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $build = new CategoryServices($this->categoryRepository);

        $buildCategory = $build->buildTree($categories);

        return $this->render('panel/categoryProcess.html.twig', [
            'categories' => $categories,
            'buildCategories' => $buildCategory,
            'message' => ''
        ]);
    }

    /**
     * Add Category Process
     *
     * @param Request $request
     * @return Response
     * @Route("/panel/category/add", name="app_categoryAdd",methods={"POST"})
     */
    public function categoryAdd(Request $request): Response
    {
        $message = "Bu Kategori Zaten Ekli";
        $data = $this->categoryRepository->findOneBy(['categoryName' => $request->get('categoryName')]);

        if (!empty($request->get('categoryName')) && !$data && $this->isCsrfTokenValid('categoryAddToken', $request->get('token'))) {

            $category = new Category();
            $category
                ->setCategoryName($request->get('categoryName'))
                ->setParentId($request->get('parentCategory'));

            if ($this->categoryRepository->add($category)) {
                $message = "Succes";
                return $this->redirectToRoute('app_category', (array)$message);
            }
        }

        return $this->redirectToRoute('app_category', (array)$message);
    }

    /**
     * Removes categories with products of the category you selected
     *
     * @param Request $request
     * @return Response
     * @Route("/panel/category/remove", name="app_categoryRemove",methods={"POST"})
     */
    public function categoryRemove(Request $request): Response
    {
        if ($this->isCsrfTokenValid('categoryRemoveToken', $request->get('token'))) {
            $categoryServices = new CategoryServices($this->categoryRepository);
            $product = $this->categoryRepository->find($request->get('id'));

            if ($categoryServices->categoryRemove($product->getId())) {
                return $this->redirectToRoute('app_category', ['message' => 'Kategori Silindi']);
            }

        }
        return $this->redirectToRoute('app_category', ['message' => 'Kategori Bulunamadı Önceden Silinmiş Olabilir']);
    }


    /**
     * Return The Products Page and Products information
     *
     * @return Response
     * @Route("/panel/products", name="app_productList")
     */
    public function productList(): Response
    {
        $products = $this->productRepository->productsFindByAll();
        return $this->render('panel/productList.html.twig', [
            'products' => $products,
            'message' => ''
        ]);
    }


    /**
     * Add Product Process
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     * @Route("/panel/product/add", name="app_productAdd")
     */
    public function productAdd(Request $request): Response
    {
        $message = '';
        $product = new Product();

        $date = new \DateTime('@' . strtotime('now'));
        $categories = $this->categoryRepository->findAll();

        if ($this->isCsrfTokenValid('productAddToken', $request->get('token'))) {

            if ($_FILES["productImg"]["type"] == "image/jpeg" || $_FILES["productImg"]["type"] == "image/png") {
                $file = $request->files->get('productImg');
                $fileName = uniqid() . '-' . $file->getClientOriginalName();

                $product
                    ->setProductName($request->get('productName'))
                    ->setProductContent($request->get('productContent'))
                    ->setProductPrice($request->get('productPrice'))
                    ->setProductPiece($request->get('productPiece'))
                    ->setCreatedAt($date)
                    ->setUpdatedAt($date)
                    ->setImgPath($fileName);


                for ($count = 0; $count < count($request->get('parentCategory')); $count++) {
                    $product
                        ->addCategory($this->categoryRepository->findOneBy(['id' => $request->get('parentCategory')[$count]]));

                    if ($this->productRepository->add($product, true)) {
                        $message = 'Ürün Başarıyla Eklendi';
                    } else {
                        $message = 'Hata! Ürün Eklenmedi';
                    }

                }

                $file->move($this->getParameter('imageDirectory'), $fileName);
            }
        }

        return $this->render('panel/productAdd.html.twig', [
            'categories' => $categories,
            'message' => $message
        ]);

    }


    /**
     * Update Product Process
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws Exception
     * @Route("/panel/product/update/{id}", name="app_productUpdate")
     */
    public function productUpdate($id, Request $request): Response
    {
        $date = new \DateTime('@' . strtotime('now'));
        $products = $this->productRepository->find($id);
        $categories = $this->categoryRepository->findAll();

        if ($this->isCsrfTokenValid('productUpdateToken', $request->get('token'))) {

            $product = $this->productRepository->find($request->get('id'));

            if (!empty($request->files->get('productImg'))) {

                unlink('uploads/img/' . $product->getImgPath());
                $file = $request->files->get('productImg');
                $fileName = uniqid() . '-' . $file->getClientOriginalName();
                $product->setImgPath($fileName);
                $file->move($this->getParameter('imageDirectory'), $fileName);

            }

            $product
                ->setProductName($request->get('productName'))
                ->setProductContent($request->get('productContent'))
                ->setProductPrice($request->get('productPrice'))
                ->setProductPiece($request->get('productPiece'))
                ->setCreatedAt($product->getCreatedAt())
                ->setUpdatedAt($date)
                ->setImgPath($products->getImgPath());

            if ($request->get('parentAddCategory')) {
                for ($count = 0; $count < count($request->get('parentAddCategory')); $count++) {
                    $product
                        ->addCategory($this->categoryRepository->findOneBy(['id' => $request->get('parentAddCategory')[$count]]));;
                }
            }

            if ($request->get('parentRemoveCategory')) {

                for ($count = 0; $count < count($request->get('parentRemoveCategory')); $count++) {
                    $product
                        ->removeCategory($this->categoryRepository->findOneBy(['id' => $request->get('parentRemoveCategory')[$count]]));;
                }
            }

            if ($this->productRepository->update($product, true)) {
                return $this->redirectToRoute('app_productList', ['Succes']);
            }

            return $this->redirectToRoute('app_productList', ['error']);

        }

        return $this->render('panel/productUpdate.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'message' => ''
        ]);
    }


    /**
     * Remove Product Process
     *
     * @param Request $request
     * @return Response
     * @Route("/panel/product/remove", name="app_productRemove", methods={"POST"})
     */
    public function productRemove(Request $request): Response
    {
        if ($this->isCsrfTokenValid('productRemoveToken', $request->get('token'))) {

            $product = $this->productRepository->find($request->get('id'));

            if ($this->productRepository->remove($product, true)) {

                unlink('uploads/img/' . $product->getImgPath());
                return $this->redirectToRoute('app_productList', ['Succes']);

            }
        }

        return $this->redirectToRoute('app_productList', ['Error']);
    }


    /**
     * Return The Products Page and Products information
     *
     * @param Request $request
     * @return Response
     * @Route("/panel/order", name="app_orderList")
     */
    public function orderList(Request $request): Response
    {
        if ($this->isCsrfTokenValid('confirmOrderToken', $request->get('token'))) {

            $data = $this->ordersRepository->find($request->get('orderId'));

            $data->setStatus('confirm');

            if ($this->ordersRepository->update($data, true)) {
                return $this->redirectToRoute('app_orderList', ['Succes']);
            }
            return $this->redirectToRoute('app_orderList', ['Error']);

        }

        $orders = $this->ordersRepository->findByAllOrders();

        return $this->render('panel/orderList.html.twig', [
            'orders' => $orders,
            'message' => ''
        ]);
    }

    /**
     * Adding and removing discounts
     *
     * @param Request $request
     * @return Response
     * @Route("/panel/discount", name="app_discountProcess")
     */
    public function discountProcess(Request $request): Response
    {
        if ($this->isCsrfTokenValid('discountAddToken', $request->get('token'))) {

            $category = $this->categoryRepository->find($request->get('parentCategory'));
            $category->setDiscount(1);
            $this->categoryRepository->update($category);

        }

        if ($this->isCsrfTokenValid('discountRemoveToken', $request->get('token'))) {

            $category = $this->categoryRepository->find($request->get('parentCategory'));
            $category->setDiscount(0);
            $this->categoryRepository->update($category);

        }

        $categories = $this->categoryRepository->findAll();
        return $this->render('panel/discountProcess.html.twig', [
            'categories' => $categories,
            'message' => ''
        ]);
    }
}
