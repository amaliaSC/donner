<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subcategory", name="subcategory_")
 */
class SubCategoryController extends AbstractController
{
    /**
     * @Route("/bycategory/{id}", name="bycategory")
     */
    public function showByCategory(int $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $subcategories = $this->getDoctrine()->getRepository(SubCategory::class)->findBy(["category" => $category],
         ["id" => "ASC"]);


        return $this->render('sub_category/showByCategory.html.twig', [
            'subcategories' => $subcategories,
        ]);
    }
}
