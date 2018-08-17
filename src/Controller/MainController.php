<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 23.01.2018
 * Time: 22:35
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    public function homepageAction()
    {
        return $this->render('main/homepage.html.twig');
    }

}