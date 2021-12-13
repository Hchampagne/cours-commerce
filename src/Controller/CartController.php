<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use SessionIdInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var CartService
     */
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartSercice)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartSercice;
    }






    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements= {"id":"\d+"} )
     */
    public function add($id, request $request)
    {
        // condition si le produit n'existe pas
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        $this->cartService->add($id);


        $this->addFlash('success', "Le produit a été ajouté.");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }



    /**
     * @Route ("/cart", name="cart_show")
     */
    public function show()
    {

        $total = $this->cartService->getTotal();

        $detailedCart = $this->cartService->getDetailedCartItems();

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }



    /**
     * @Route("/cart/delete/{id}", name= "cart_delete", requirements={"id":"\d+"} )
     */
    public function delete($id)
    {

        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Ce produit $id n'existe pas et ne peut etre supprimé !");
        }

        $this->cartService->remove($id);

        $this->addFlash('success', "Ce produit a bien été supprimer du panier.");

        return $this->redirectToRoute('cart_show');
    }



    /**
     * @route("/cart/decrement/{id}", name="cart_decrement", requirements= {"id":"\d+"})
     */
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Ce produit $id n'existe pas et la quantitée ne peut etre décrémentée !");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success', "La quantité a bien été décrémentée.");

        return $this->redirectToRoute('cart_show');
    }
}
