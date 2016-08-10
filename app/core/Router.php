<?php
/*
   Copyright 2016 Thomas Bollmeier <entwickler@tbollmeier.de>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

namespace tbollmeier\codeschnipsel\core;


class Router
{

    private $handlers;
    private $controllerNS;
    private $defaultCtrl;
    private $defaultAction;

    public function __construct($controllerNS = '',
                                $defaultCtrlAction='Index.index')
    {
        $this->controllerNS = $controllerNS;
        list($defaultCtrl, $defaultAction) = explode('.', $defaultCtrlAction);
        $this->defaultCtrl = $controllerNS . '\\' . $defaultCtrl;
        $this->defaultAction = $defaultAction;
        $this->handlers = [];
    }

    public function route($method, $url)
    {
        $handlers = $this->handlers[$method];

        foreach ($handlers as $handler) {
            list($pattern, $params, $controller, $action) = $handler;
            $controller = $this->controllerNS . '\\' . $controller;
            if (preg_match($pattern, $url, $matches)) {
                $data = [];
                $numParams = count($params);
                for ($paramIdx=0; $paramIdx < $numParams; $paramIdx++) {
                    $data[$params[$paramIdx]] = $matches[$paramIdx+1];
                }
                $ctrl = new $controller();
                call_user_func([$ctrl, $action], $data);
                return;
            }
        }

        // Fallback:
        $ctrl = new $this->defaultCtrl();
        call_user_func([$ctrl, $this->defaultAction], []);
    }

    public function registerAction($method,
                                   $route,
                                   $controllerAction)
    {
        list($controller, $action) = explode('.', $controllerAction);
        list($pattern, $params) = $this->parserRoute($route);

        $handlers = $this->handlers[$method];
        $handlers[] = [$pattern, $params, $controller, $action];
        $this->handlers[$method] = $handlers;

    }

    private function parserRoute($route)
    {
        $segments = [];
        $pattern = '';
        $params = [];

        preg_match_all("/([^\\/]+)*/", $route, $matches);

        if (!empty($matches)) {

            foreach ($matches[0] as $match) {
                if (empty($match)) continue;
                if ($match[0] != ':') {
                    $segments[] = $match;
                } else {
                    $segments[] = "([^\\/]+)";
                    $params[] = substr($match, 1);
                }
            }

            $pattern = '/\\/?' . implode('\\/', $segments) . '\\/?$/';

        }

        return [$pattern, $params];
    }

}