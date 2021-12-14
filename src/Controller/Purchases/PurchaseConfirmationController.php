<?php

namespace App\Controller\Purchases;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{


    protected $cartService;
    protected $em;
    protected $persister;

    public function __construct(

        CartService $cartService,
        EntityManagerInterface $em,
        PurchasePersister $persister
    ) {

        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }


    /**
     * @Route("/purchases/confirm", name="purchases_confirm" )
     * @IsGranted("ROLE_USER", message="Vous devez etre connecté pour confirmer votre commande")
     */
    public function Confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', "Vous devez remplir le formulaire pour passer commande ! ");
            $this->redirectToRoute('cart_show');
        }

        //$user = $this->getUser();

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', "Votre panier est vide, vous ne pouvez pas confirmer de commande ! ");
            $this->redirectToRoute('cart_show');
        }

        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        $this->cartService->empty();

        $this->addFlash('sucess', "La commande a bien été enregistrée. ");
        return $this->redirectToRoute('purchase_index');
    }
}
