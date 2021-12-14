<?php

namespace App\Controller\Purchases;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PurchasesListController extends AbstractController
{

    /**
     * @Route("/purchases", name="purchase_index")
     * @isGranted("ROLE_USER", message="Vous devez être connecté pour avoir accès à vos commandes ")
     */
    public function index()
    {

        /**
         * @var User
         */
        $user = $this->getUser();

        return $this->render('purchases/index.html.twig', [

            'purchases' => $user->getPurchases()
        ]);
    }
}
