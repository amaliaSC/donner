<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Status;
use App\Entity\SubCategory;
use App\Form\AdFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ad", name="ad_")
 */
class AdController extends AbstractController
{

    /**
     * @Route("/", name="list_last")
     */
    public function last(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $listSix = $this->getDoctrine()->getRepository(Ad::class)->findBy(
            [],
            ["createdAt" => "DESC"],
            8,
            0
        );


        return $this->render("default/index.html.twig", ["list" => $listSix, "categories" => $categories]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        if ($this->getUser()) {

            $ad = new Ad;
            $adForm = $this->createForm(AdFormType::class, $ad);
            $adForm->handleRequest($request);

            if ($adForm->isSubmitted() && $adForm->isValid()) {
                $tabInputCity = explode(" - ", $_POST['searchInput']);
                $cityZipCode = $tabInputCity[0];
                $cityName = $tabInputCity[1];
                $city = new City;
                $city = $this->getDoctrine()->getRepository(City::class)->findBy(['zipCode' => $cityZipCode, 'name' => $cityName], null,  1, 0);

                $status = $this->getDoctrine()->getRepository(Status::class)->find(1);

                $ad->setCity($city[0]);
                $ad->setCreatedAt(new \DateTime());
                $ad->setUser($this->getUser());
                $ad->setStatus($status);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ad);
                $entityManager->flush();

                return $this->redirectToRoute('user_profil', ['id' => $this->getUser()->getId()]);
            }

            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

            return $this->render('ad/new.html.twig', [
                "adForm" => $adForm->createView(),
                "categories" => $categories
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifier(Request $request, int $id): Response
    {
        if ($this->getUser()) {

            $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
            $adForm = $this->createForm(AdFormType::class, $ad);
            $adForm->handleRequest($request);


            $adForm = $this->createForm(AdFormType::class, $ad);
            $adForm->handleRequest($request);

            if ($adForm->isSubmitted() && $adForm->isValid()) {
                $tabInputCity = explode(" - ", $_POST['searchInput']);
                $cityZipCode = $tabInputCity[0];
                $cityName = $tabInputCity[1];
                $city = new City;
                $city = $this->getDoctrine()->getRepository(City::class)->findBy(['zipCode' => $cityZipCode, 'name' => $cityName], null,  1, 0);

                $ad->setCity($city[0]);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('ad_show_one', ['id' => $ad->getId()]);
            }

            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

            return $this->render('ad/edit.html.twig', [
                "adForm" => $adForm->createView(),
                "categories" => $categories,
                "ad" => $ad
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/show/{id}", name="show_one")
     */
    public function showOne(int $id): Response
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        return $this->render("ad/show_one.html.twig", ["ad" => $ad]);
    }

    /**
     * @Route("/all", name="list_all")
     */
    public function index(): Response
    {
        $listAll = $this->getDoctrine()->getRepository(Ad::class)->findAll();

        return $this->render("ad/index.html.twig", ["list" => $listAll]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(int $id): Response
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        return $this->render("ad/show_one.html.twig", ["ad" => $ad]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(int $id): Response
    {
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ad);
        $entityManager->flush();

        $this->addFlash('annonce_delete_success', 'Annonce Suprimé');

        return $this->redirectToRoute('user_profil', ["id" => $ad->getUser()->getId()]);
    }

    /**
     * @Route("/reserve/{id}", name="reserve")
     */
    public function reserve(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        //Modification du statut de l'annonce
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        $status = $this->getDoctrine()->getRepository(Status::class)->find(2);
        $ad->setStatus($status);
        $ad->setRecipientUser($this->getUser());

        //Modification du nombre de smiley de receveur
        $this->getUser()->setSmileyNumber($this->getUser()->getSmileyNumber() - 1);

        //Augmentation du nombre de smiley du donneur
        $ad->getUser()->setSmileyNumber($ad->getUser()->getSmileyNumber() + 1);

        $entityManager->flush();

        return $this->redirectToRoute("ad_show_one", ['id' => $ad->getId()]);
    }

    /**
     * @Route("/search/{page}", name="search")
     */
    public function search(int $page): Response
    {
        $search = (isset($_POST['search']) ? $_POST['search'] : "");
        $search_location = (isset($_POST['search_location']) ? $_POST['search_location'] : "");
        // Pas encore la possibilité de choisir le nombre d'éléments par pages, il faudra faire les tests nécessaires sur intval($_POST['numppage']) avant de s'en servir $numberPerPage = (isset($_POST['numppage']) ? intval($_POST['numppage']) : 8);
        $numberPerPage = 8;
        $start = ($page - 1) * $numberPerPage;

        $keyWord = "%" . str_replace(" ", "%", trim($search)) . "%";
        $location = "%" . str_replace(" ", "%", trim($search_location)) . "%";

        $number = $this->getDoctrine()->getRepository(Ad::class)->countByKeyWord($keyWord, $location);
        $resultsNum = $number['count(ad.id)'];

        $isPrevious = ($page == 1 ? false : true);
        $isNext = ($resultsNum > $page * $numberPerPage ? true : false);

        $ads = $this->getDoctrine()->getRepository(Ad::class)->findByKeyWordOrderByAntechrono($keyWord, $location, $start, $numberPerPage);

        foreach ($ads as $key => $ad) {
            if (iconv_strlen($ad['title']) > 24) {
                $shortTitle = substr($ad['title'], 0, 24) . "...";
                $ads[$key]['title'] = $shortTitle;
            }
        }

        return $this->render("ad/search_results.html.twig", ["ads" => $ads, "search" => $search, "resultsNum" => $resultsNum, "next" => $isNext, "previous" => $isPrevious, "actualPage" => $page]);
    }

    /**
     * @Route("/category/{id}", name="by_category")
     */
    public function byCategory(int $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $subcategories = $this->getDoctrine()->getRepository(SubCategory::class)->findBy(
            ["category" => $category]
        );
        $ads = [];
        foreach ($subcategories as $key => $subcategory) {
            $ads[] = $this->getDoctrine()->getRepository(Ad::class)->findBy(
                ["subCategory" => $subcategory],
                ["createdAt" => "DESC"]
            );
        }

        // dd($ads);

        return $this->render("ad/show_by_category.html.twig", ["adsSubCat" => $ads, "category" => $category]);
    }

    /**
     * @Route("/all/{page}", name="show_all")
     */
    public function showAll(int $page): Response
    {
        // Pas encore la possibilité de choisir le nombre d'éléments par pages, il faudra faire les tests nécessaires sur intval($_POST['numppage']) avant de s'en servir $numberPerPage = (isset($_POST['numppage']) ? intval($_POST['numppage']) : 8);
        $numberPerPage = 8;
        $start = ($page - 1) * $numberPerPage;

        $number = $this->getDoctrine()->getRepository(Ad::class)->findBy([], ["createdAt" => "ASC"]);
        $resultsNum = count($number);

        $isPrevious = ($page == 1 ? false : true);
        $isNext = ($resultsNum > $page * $numberPerPage ? true : false);

        $ads = $this->getDoctrine()->getRepository(Ad::class)->findByKeyWordOrderByAntechrono("%", "%", $start, $numberPerPage);

        foreach ($ads as $key => $ad) {
            if (iconv_strlen($ad['title']) > 24) {
                $shortTitle = substr($ad['title'], 0, 24) . "...";
                $ads[$key]['title'] = $shortTitle;
            }
        }

        return $this->render("ad/show_all.html.twig", ["ads" => $ads, "resultsNum" => $resultsNum, "next" => $isNext, "previous" => $isPrevious, "actualPage" => $page]);
    }
}
