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

$currentUser
$mainContent

*/

?>

<!DOCTYPE html>
<html>
    <head lang="de">
        <meta charset="utf-8">
        <title>Codeschnipsel</title>
        <link rel="stylesheet" href="/codeschnipsel/css/bootstrap.min.css">
        <link rel="stylesheet" href="/codeschnipsel/css/codeschnipsel.css">
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
                    <a class="navbar-brand" href="/codeschnipsel">Codeschnipsel</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right"
                          method="post"
                          action=<?= $currentUser ? "/codeschnipsel/signout" : "/codeschnipsel/signin"?> >
                        
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

        <?= $mainContent ?>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="/codeschnipsel/js/bootstrap.min.js"></script>
    
    </body>
</html>