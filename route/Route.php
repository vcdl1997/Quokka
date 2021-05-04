<?php

final class Route {

    public static function calling($url)
    {
        $routes = [
            "index" => 'view/users/login.php',
            "enter"     => "controller/AuthenticatorController.php",
            "exit"      => "controller/AuthenticatorController.php",
            "dashboard" => 'view/users/dashboard.php',
            "users"     => [
                "index"             => 'view/users/index.php',
                "new"               => 'view/users/form.php',
                "edit"              => 'view/users/form.php',
                "list"              => 'controller/UserController.php',
                "save"              => 'controller/UserController.php',
                "delete"            => 'controller/UserController.php',
            ],
            "customers" => [
                "index"             => 'view/customers/index.php',
                "new"               => 'view/customers/form.php',
                "edit"              => 'view/customers/form.php',
                "list"              => 'controller/CustomerController.php',
                "save"              => 'controller/CustomerController.php',
                "delete"            => 'controller/CustomerController.php',
            ],
            "schedules" => [
                "index"             => 'view/schedules/index.php',
                "new"               => 'view/schedules/form.php',
                "list"              => 'controller/ScheduleController.php',
                "create"            => 'controller/ScheduleController.php',
                "delete"            => 'controller/ScheduleController.php',
                "confirm"           => 'controller/SchedulingWagesController.php',
            ],
        ];

        $sessionEmpty = empty($_SESSION['user']);
        
        if(count($_GET) == 0 && $sessionEmpty) return $routes['index'];
        else if (count($_GET) == 0 && !$sessionEmpty) return $routes['dashboard'];

        $splitUrl =  array_filter(explode("/", $url['url']), 'strlen');
        $notfound = 'view/404/not-found.php';

        switch(count($splitUrl)){
            case 1:
                $route = $splitUrl[0];

                if(!array_key_exists($route, $routes)) return $notfound;

                if(is_string($routes[$route])){
                    return $route == "dashboard" && !$sessionEmpty || "exit" && !$sessionEmpty ? 
                        $routes[$route] : ($route == "enter" && $sessionEmpty ? $routes[$route] : $routes['index']);
                }
                
                return !$sessionEmpty ? $routes[$route]['index'] : $routes['index'];
            break;

            case 2: case 3:
                $route = $splitUrl[0];
                $method = $splitUrl[1];

                if(!array_key_exists($route, $routes) || !array_key_exists($method, $routes[$route])) return $notfound;
                
                return !$sessionEmpty ? $routes[$route][$method] : $routes['index'];
            break;

            default: return $notfound; break;
        }
    }

}