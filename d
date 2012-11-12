[1mdiff --git a/protected/@documentation/Tips.txt b/protected/@documentation/Tips.txt[m
[1mdeleted file mode 100644[m
[1mindex 66dfa2d..0000000[m
[1m--- a/protected/@documentation/Tips.txt[m
[1m+++ /dev/null[m
[36m@@ -1,4 +0,0 @@[m
[31m-- Id field of all tables should be called "id"[m
[31m-- foreing key of all tabler should be called "foreingTable_id"[m
[31m-- Los modelos heredan de una clase donde las propiedades son de solo lectura.[m
[31m-- La propiedad tiene que ser declarada como PROTECTED y deben llevar un guión bajo (_) delante. Ej.: $_tableName[m
\ No newline at end of file[m
[1mdiff --git a/protected/@documentation/Warnings.txt b/protected/@documentation/Warnings.txt[m
[1mdeleted file mode 100644[m
[1mindex 2413ecf..0000000[m
[1m--- a/protected/@documentation/Warnings.txt[m
[1m+++ /dev/null[m
[36m@@ -1,4 +0,0 @@[m
[31m-﻿- Ordenar ConopioSet.[m
[31m-- Revisar la función de addFile()[m
[31m-[m
[31m-Arnaldo: 4641-9036[m
\ No newline at end of file[m
[1mdiff --git a/protected/@documentation/database_structure.ods b/protected/@documentation/database_structure.ods[m
[1mdeleted file mode 100644[m
[1mindex c57439b..0000000[m
Binary files a/protected/@documentation/database_structure.ods and /dev/null differ
[1mdiff --git a/protected/extends/CInstallation.class.php b/protected/extends/CInstallation.class.php[m
[1mindex 24f2d08..a5d681e 100644[m
[1m--- a/protected/extends/CInstallation.class.php[m
[1m+++ b/protected/extends/CInstallation.class.php[m
[36m@@ -468,7 +468,7 @@[m
 		*/[m
         final private function menu()[m
         {[m
[31m-            /*[m
[32m+[m[41m            [m
             $menu = array(); // Initialice menu.[m
             $menu['menu']['home'] = array('link' => FRAMEWORK_URL); // [m
             $menu['menu']['tables']['menu']['All tables'] = array('link' => 'index.php');[m
[36m@@ -476,7 +476,12 @@[m
             {[m
                 $menu['menu']['tables']['menu'][$tableData['table_caption']] = array('link' => 'index.php?table_id=' . $id);[m
             }[m
[32m+[m[41m            [m
[32m+[m[41m            [m
[32m+[m[32m            /*[m[41m[m
[32m+[m[32m            * TEST MENU[m[41m[m
             */[m
[32m+[m[32m            /*[m[41m[m
             $menu = array([m
                 'menu' => array ([m
                     'home' => array([m
[36m@@ -513,7 +518,8 @@[m
                     ),[m
                 )[m
             );[m
[31m-            [m
[32m+[m[32m            *[m[41m [m
[32m+[m[32m            */[m[41m            [m
             Html::createMenu($menu);[m
         }[m
         [m
