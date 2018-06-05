<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ApiController
 *
 * @Route("/api")
 */

class ApiController extends Controller
{
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {
    	print_r('hola');
    	exit();
    }
}
