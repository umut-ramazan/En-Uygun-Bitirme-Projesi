<?php

namespace App\Services;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class CategoryServices
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;

    }

    /**
     * Get All Category Hierarchy
     *
     * @param $elements /Entity Category
     * @param int $parentId Parent Id
     * @return array
     */
    function buildTree($elements, int $parentId=0) :array
    {
        $branch= array();
        foreach ($elements as $element){
            if ($element->getparentId()==$parentId){

                $children = $this->buildTree($elements,$element->getId());

                if ($children){
                    $element->children = $children;
                }
                else{
                    $element->children =array();
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Removes categories with products of the category you selected
     *
     * @param int $categoryId /Entity/Category
     * @return array|bool[]|false[]
     */
    function categoryRemove(int $categoryId) :array
    {
        $branch= array();
        $elements = $this->categoryRepository->find($categoryId);

        if (empty($elements)){
            return [false];
        }

        $childrens = $this->categoryRepository->findBy(['parentId'=>$elements->getId()]);

        if ($childrens)
        {
            foreach ($childrens as $children){
                $branch[] = $children;
                $subCategory= $this->categoryRemove($children->getId());
            }
        }
        //we update connected products

        $this->categoryRepository->remove($elements);

        return $branch;

    }







}