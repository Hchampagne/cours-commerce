<?php

namespace App\Cart;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    // recupère le panier dans la session, cré le panier tableau vide si il n'existe pas
    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    // sauvegarde le panier dans la session
    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    //
    public function remove(int $id)
    {

        $cart = $this->getCart();
        unset($cart[$id]);

        $this->saveCart($cart);
    }


    // fonction enlève un article
    public function decrement(int $id)
    {

        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        $cart[$id]--;
        $this->saveCart($cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    // fonction addition d'un article
    public function add(int $id)
    {
        // recup le panier de la session
        $cart = $this->getCart();

        // test si article ds panier
        if (!array_key_exists($id, $cart)) {
            //si n'existe pas => quantité = 0
            $cart[$id] = 0;
        }
        //si existe => quntité incrémenté de 1
        $cart[$id]++;

        // sauvegarde le panier en session
        $this->saveCart($cart);
    }


    // Calcul total panier
    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->getCart() as $id => $qty) {

            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }
        return $total;
    }

    // panier

    /**
     * Undocumented function
     *
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {

            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }
}
