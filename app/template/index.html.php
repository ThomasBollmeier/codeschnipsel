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

/* Variables used in template:

$baseUrl
$currentUser
$mainContent
$scripts

*/

?>

<!DOCTYPE html>
<html>
    <head lang="de">
        <meta charset="utf-8">
        <title>Codeschnipsel</title>
        <link rel="stylesheet" href="<?= $baseUrl ?>/css/bootstrap-slate.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="<?= $baseUrl ?>/css<?= $baseUrl ?>.css">
        <link rel="stylesheet" href="<?= $baseUrl ?>/css/codemirror/codemirror.css">
        <link rel="stylesheet" href="<?= $baseUrl ?>/css/codemirror/theme/monokai.css">
    </head>
    <body>
        
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button"
                            class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#navbar"
                            aria-expanded="false"
                            aria-controls="navbar">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= $baseUrl ?>">Codeschnipsel</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right"
                          method="post"
                          action=<?= $currentUser ? "$baseUrl/signout" : "$baseUrl/signin"?> >
                        
                        <?php if (!$currentUser): ?>
                    
                        <div class="form-group">
                            <input type="text"
                                   name="email"
                                   placeholder="Email"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password"
                                   name="password"
                                   placeholder="Password"
                                   class="form-control">
                        </div>
                        <button type="submit"
                                class="btn btn-success">Anmelden</button>

                        <?php else: ?>
                        
                        <button type="submit"
                                class="btn btn-success">Abmelden</button>
                    
                        <?php endif ?>
                    </form>
                    <?php if ($currentUser): ?>
                    
                    <p class="cs-username navbar-text navbar-right"><?= $currentUser->getName() ?></p>
                
                    <?php endif ?>
                </div><!--/.navbar-collapse -->
            </div>
        </nav>

        <script src="<?= $baseUrl ?>/js/codemirror/codemirror.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/clike/clike.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/clojure/clojure.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/javascript/javascript.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/php/php.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/python/python.js"></script>
        <script src="<?= $baseUrl ?>/js/codemirror/mode/ruby/ruby.js"></script>

        <?= $mainContent ?>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="<?= $baseUrl ?>/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
        <script src="<?= $baseUrl ?>/js/codeschnipsel.js"></script>

        <?= $scripts ?>
    
    </body>
</html>