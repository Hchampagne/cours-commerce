<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{name}", name="hello", methods={"GET"})
     */

    public function hello($name = "World")
    {
        return $this->render('hello.html.twig', [
            'name' => $name,
        ]);
    }
}
