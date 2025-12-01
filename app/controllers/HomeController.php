<?php

namespace App\Controllers;

use Flight;

class HomeController
{

    static public function main()
    {
        Flight::render('page/main.latte', ['title' => 'Main']);
    }

    static public function mainAside()
    {
        Flight::render('page/main-aside.latte', ['title' => 'Main + Aside']);
        Flight::render('component/aside.latte');
    }

    static public function mainAsideNavbar()
    {
        Flight::render('page/main-aside-navbar.latte', ['title' => 'Main + Aside + Navbar']);
        Flight::render('component/aside.latte');
        Flight::render('component/navbar.latte');
    }

    static public function morphDemo()
    {
        $count = (int) (Flight::request()->query['count'] ?? 0);
        Flight::render('page/morph-demo.latte', ['title' => 'Morph Demo', 'count' => $count]);
    }

}
