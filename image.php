<?php

    function image($emplacement,$first){

        $bdUser = ""; // Utilisateur de la base de données
        $bdPasswd = ""; // Son mot de passe
        $dbname = ""; // nom de la base de données
        $host = "localhost"; // Hôte
        try {
            $Bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $bdUser, $bdPasswd); // SE CONNECTER A LA BDD
            $Bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // METTRE LE MODE OBJET PAR DEFAUT
        } catch (PDOException $e) {
            echo ("Erreur : impossible de se connecter à la bdd");
        }

        
        /*$filehtml="test.html";
        file_put_contents($filehtml,"");*/
        if ($first==1){
            $fichierhtml="detail_fichier.html";
            if (file_exists ($fichierhtml)){
                $msg="Ce fichier existe. ";
            }else{
                $fichierhtml = @fopen ($fichierhtml, "w+") or die ("Impossible de créer ce fichier"  );
                $msg= "Fichier créé! " ;
                fclose ($fichierhtml);
            }
            echo "$msg"; 
        }

        $fichierhtml="detail_fichier.html";


        $f=fopen("date.txt","r");
        $lastUpdate=fread($f,filesize("date.txt"));
        //echo "$lastUpdate";
    
        /*$filename="$image";
        echo "$image a été modifié le : " . date ("Y-m-d H:i:s.", filectime($image));*/
    
        //print_r($emplacement) ;



        //file_put_contents($fichierhtml,"");

        $verifdossier=glob("$emplacement/*");
        $fichier=glob("$emplacement/*.jpg");
        //print_r($fichier);
        $len=count($fichier);
        $i=0;
        $lenverif=count($verifdossier);
        $iv=0;

    
            while($i<$len){
    
                
                $image=$fichier[$i];

                $crerimage = imagecreatefromjpeg($image);
                $size = getimagesize($image); //indique un tableau avec [0]=largeur de l'image, [1]=hauteur de l'image, [2]=type de l'image, [3]=info html de type width="468" height="60"
                $largeur= $size[0];
                $hauteur = $size[1]; 
                //echo "$image <br>";
                //file_put_contents("test.html","$image",FILE_APPEND);
                //echo "la largeur de l'image est : $largeur sa hauteur est de $hauteur";
                $f=fopen("date.txt","r");
                $lastUpdate=fread($f,filesize("date.txt"));
    
    
                //echo "$lastUpdate";
               $saveImage=date("Y-m-d H:i:s", filectime($image));
               //echo "$saveImage <br>";
               //echo "test";
                
               if($saveImage > $lastUpdate){
                    //echo "a traiter";
                    $file = basename($image); //récuperer le nom de l'image
                    //echo $file;
                    $test=$image;
                    copy("$image","images/corbeille/$file");


                    $octetav=filesize("$image");//AVOIR LA TAILLE D'UN FICHIER EN OCTET
                    $tailleav=$octetav/1024;// AVOIR LA TAILLE EN KILO OCTET
                    //echo $tailleav;

                    //echo $image;
                    //file_put_contents($fichierhtml,"la photo $image de $tailleav KO", FILE_APPEND);// ECRIRE A LA SUITE DU FICHIER

                    //echo $image;
                
                    $req4="SELECT image FROM tslew_icagenda_events WHERE image IN ('$image') ";
                    $Ores4= $Bdd->query($req4);

                    $req5 = "SELECT images FROM tslew_content ";
                    $Ores5 = $Bdd->query($req5);
                    
                    $bol=false;

                    
                    if($images4=$Ores4->fetch()){
                        $bol=true;
                        if ($image==$images4->image){
                            //echo "il est dans icagenda";
                            if ($hauteur>$largeur){
                                if($largeur>750){
                                    if($hauteur>400){
                                        $resized=imagescale($crerimage,750,400);
                                        imagejpeg($resized,$image,75);
                                    }
                                    else{
                                        $resized=imagescale($crerimage,750,$hauteur);
                                        imagejpeg($resized,$image,75);
                                    }
                                    //echo "hauteur";

                                }
                        
                            }
                            elseif($largeur>$hauteur){
                                if( $largeur>"1136"){
                                    if($hauteur>400){
                                        $resized=imagescale($crerimage,1136,400);
                                        imagejpeg($resized,$image,75);

                                    }
                                    else{
                                        $resized=imagescale($crerimage,1136,$hauteur);
                                        imagejpeg($resized,$image,75);
                                    }
                                    //echo "plus que 1136";
                                }
                        
                                elseif("750"<$largeur && $largeur<"1136"){
                                    if($hauteur>400){
                                        $resized=imagescale($crerimage,750,400);
                                    }
                                    else{
                                        $resized=imagescale($crerimage,750,$hauteur);
                                        imagejpeg($resized,$image,75);
                                        
                                    }
                                    //echo "l'image est de bonne dimention";

                                }
                                    //echo "l'image est trop petite";
                                
                                //echo "largeur";
                            }

                            
                            //$lieutraiter=$test;
                            //$octetap=filesize("$test");//AVOIR LA TAILLE D'UN FICHIER EN OCTET
                            //$tailleap=$octetap/1024;// AVOIR LA TAILLE EN KILO OCTET
                            //echo $tailleap;
                            //echo $lieutraiter;

                        }
                    }
                    while($images=$Ores5->fetch()){
                        //print_r($images);
                        if($bol==true){
                            //break;
                        }
                        
                        $imagejson=$images->images;
                        $obj = json_decode($imagejson);
                        $json= $obj->{'image_fulltext'};
                        //echo "$json";

                        if($json==$image){
                            $bol=true;
                            //echo "oui";
                            if ($hauteur>$largeur){
                                if($largeur>750){
                                    //echo "hauteur";
                                    $resized=imagescale($crerimage,750,$hauteur);
                                    imagejpeg($resized,$image,75);
                                    
                                }
                            
                            }
                            elseif($largeur>$hauteur){
                                if( $largeur>"1136"){
                                    //echo "plus que 1136";
                                    $resized=imagescale($crerimage,1136,$hauteur);
                                    imagejpeg($resized,$image,75);
                                    
                                }
                        
                                elseif("750"<$largeur && $largeur<"1136"){
                                    //echo "l'image est de bonne dimention";
                                    $resized=imagescale($crerimage,750,$hauteur);
                                    imagejpeg($resized,$image,75);
                                    
                                }
                                    //echo "l'image est trop petite";
                                
                                //echo "largeur";
                            }
                        }
                         
                        else{
                            //echo "non il n'y a pas de fichier";
                        }      
                    }
                    


                    if($bol==false){
                        rename ("$image", "images/corbeille/$file");
                        $lieutraiter = "images/corbeille/$file";
                        $octetap=filesize($image);//AVOIR LA TAILLE D'UN FICHIER EN OCTET
                        $tailleap=$octetap/1024;// AVOIR LA TAILLE EN KILO OCTET
                        //echo "mis dans la corbeille";
                        
                    }
                    elseif($bol==true){

                        clearstatcache();
                        $lieutraiter = $image;                        
                        $octetap=filesize($image);//AVOIR LA TAILLE D'UN FICHIER EN OCTET
                        $tailleap=$octetap/1024;// AVOIR LA TAILLE EN KILO OCTET
                    }

                    
                
                    file_put_contents($fichierhtml,"la photo $image de $tailleav KO a etait deplacee dans $lieutraiter et pese maintenant $tailleap KO ,   ", FILE_APPEND);

                }
                else{
                    //echo"a ne pas traiter";
                }
                //echo "$image";
                //$filehtml="test.html";
                //file_put_contents($filehtml,"$image", FILE_APPEND);
                
                
                $i=$i+1;

            }

            while($iv<$lenverif){
                //echo $first;
    
                if (is_dir($verifdossier[$iv])){
                    if($first==1 && $verifdossier[$iv]=="images/corbeille" || $verifdossier[$iv]=="images/headers" || $verifdossier[$iv]=="images/icagenda" ){
                        //echo "test/";
                    }
                    else{
                        
                        $lieu=$verifdossier[$iv];
                        $indexhtml="$lieu/index.html";
                        if (!file_exists($indexhtml)){
    
                            file_put_contents($fichierhtml,"Le fichier $indexhtml n'existe pas.  ", FILE_APPEND);
                            //echo "l'index html $indexhtml n'éxiste pas";
            
                            //echo "Le fichier $filename n'existe pas. <br>";
                        } 

                        //echo "$lieu<br>";
                        image($lieu,0);  
                    }              
                
                }
                $iv=$iv+1;

            }

            $req4="SELECT image FROM tslew_icagenda_events WHERE modified>'$lastUpdate'";
            $Ores4= $Bdd->query($req4);
    
            $reqCount="SELECT COUNT(image) AS nombre FROM tslew_icagenda_events WHERE modified>'$lastUpdate'";
            $OresCount=$Bdd->query($reqCount);
    
            $nombre = $OresCount ->fetch();
            $nbImage = $nombre->nombre ;
            //echo $nbImage;
    
            $fichierhtml="detail_fichier.html";
            $i=0;
            while($i<$nbImage){
                $test = $Ores4 ->fetch();
                $test1=$test->image;
                //echo $test1;
                //echo "<br>";
    
                $filename = $test1;
                
    
                if (!file_exists($filename)){
    
                    file_put_contents($fichierhtml,"L'image' $filename n'existe pas.  ", FILE_APPEND);
    
                    //echo "Le fichier $filename n'existe pas. <br>";
                } 
    
            $i=$i+1;
            } 
    
            //CONTENT
    
            $req5="SELECT images FROM tslew_content";
            $Ores5= $Bdd->query($req5);
    
            $reqContent = "SELECT COUNT(images) AS nombre FROM tslew_content";
            $OresCountent=$Bdd->query($reqContent);
    
            $nbContent= $OresCountent ->fetch();
            $nbImageContent = $nbContent->nombre ;
            //echo $nbImageContent;
    
            $ic=0;
    
            while($ic<$nbImageContent){
                $test2=$Ores5 -> fetch();
                $test3=$test2->images;
                $obj = json_decode($test3);
                $azerty= $obj->{'image_fulltext'};
    
                if(!empty($obj->{'image_intro'})){
                    file_put_contents($fichierhtml,"image_fulltext n'est pas vide dans $azerty.  ", FILE_APPEND);
    
                    //echo "il n'est pas vide dans $azerty";
                }
    
                
                $filename1 = $azerty;
                //echo $intro;
    
                if (!file_exists($filename1)){
    
                    file_put_contents($fichierhtml,"Le fichier $filename1 n'existe pas.  ", FILE_APPEND);
    
                    //echo "Le fichier $filename1 n'existe pas. <br>";
                } 
    
    
                //echo $azerty;
                //echo "<br>";
                $ic=$ic+1;
            }

            $reqPlsCompagnie="SELECT tslew_content.id, tslew_content.title, Count(tslew_fields_values.value) AS CompteDevalue FROM tslew_content LEFT JOIN tslew_fields_values ON tslew_content.id = tslew_fields_values.item_id WHERE ((tslew_content.catid)=9) AND ((tslew_fields_values.field_id)=2) GROUP BY tslew_content.id, tslew_content.title HAVING (((Count(tslew_fields_values.value))>1))";
            $OresPlsCompagnie=$Bdd->query($reqPlsCompagnie);
           
            while($PlsCompagnie = $OresPlsCompagnie ->fetch()){
                $spectacle = $PlsCompagnie->title ;
                file_put_contents($fichierhtml,"La fiche spectacle $spectacle a plusieurs compagnies affecteés. ", FILE_APPEND);

            }


            $reqAucune="SELECT tslew_content.title FROM tslew_content WHERE ((tslew_content.catid)=9) AND tslew_content.id NOT IN (SELECT tslew_fields_values.item_id FROM tslew_fields_values WHERE ((tslew_fields_values.field_id)=2))";
            $OresAucune=$Bdd->query($reqAucune);
            while($aucune = $OresAucune ->fetch()){
                $spectacle = $aucune->title ;
                file_put_contents($fichierhtml,"La fiche spectacle $spectacle n'a aucune compagnie rattachée à l'utilisateur.  ", FILE_APPEND);

            }

        }

    function enregistrer()
    {
        $date = new DateTime();
        $nom_file = "date.txt";
    
        // création du fichier
        $f = fopen($nom_file, "w+");
        //echo $f;
        // écriture
        fputs($f, $date->format('Y-m-d H:i:s'));
        // fermeture
        fclose($f);
    }



    function sendMail(){
        $octet=filesize("detail_fichier.html");
        if ($octet!=0)
        {
            $file_name = "detail_fichier.html";
            $boundary = "_".md5(uniqid(rand()));
            $email = "nono.lecorne@gmail.com"; //mail du receveur
            $subject = "Test"; //OBJET DU MESSAGE
            $message = "test de l'image"; //MESSAGE

            $attached_file = file_get_contents("$file_name");
            $attached_file = chunk_split(base64_encode($attached_file));

            $attached = "\n\n". "--" .$boundary . "\nContent-Type: application/rtf; name=$file_name\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=$file_name\r\n\n".$attached_file . "--" . $boundary . "--";

            $headers ="From: Lecorne Noemie <nono.lecorne@gmail.com> \r\n"; //mail et nom de l'expéditeur
            $headers .= "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=$boundary\r\n";

            $body = "--". $boundary ."\nContent-Type: text/plain; charset=ISO-8859-1\r\n".$message . $attached;

            mail($email, $subject, $body, $headers);

            unlink($file_name);

        }

    }

    function dump_MySQL($serveur, $login, $password, $base, $mode)
    {

        $bdUser = ""; // Utilisateur de la base de données
        $bdPasswd = ""; // Son mot de passe
        $dbname = ""; // nom de la base de données
        $host = "localhost"; // Hôte
        try {
            $Bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $bdUser, $bdPasswd); // SE CONNECTER A LA BDD
            $Bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // METTRE LE MODE OBJET PAR DEFAUT
        } catch (PDOException $e) {
            echo ("Erreur : impossible de se connecter à la bdd");
        }


        $ftp_host     = 'sijr.dyndns.tv'; // nom du serveur ftp
        $ftp_user     = 'a2lrlogs'; // nom d'utilisateur sur le serveur ftp
        $ftp_pass     = 'A2Lr-sijr'; //mdp sur le serveur ftp


        $f=fopen("date.txt","r");
        $lastUpdate=fread($f,filesize("date.txt"));
        echo $lastUpdate;

        //$req="SELECT COUNT(tslew_content.modified) AS content, COUNT(tslew_icagenda_events.modified) AS icagenda_events FROM tslew_content, tslew_icagenda_events WHERE tslew_content.modified>'$lastUpdate' AND tslew_icagenda_events.modified>'$lastUpdate'";
        $req="SELECT COUNT(tslew_content.modified) AS content FROM tslew_content WHERE tslew_content.modified>'$lastUpdate'";
        $Ores=$Bdd->query($req);
        $nb=$Ores->fetch();
        $nb1=$nb->content;
        $nb2=0;

        $req1="SELECT COUNT(tslew_icagenda_events.modified) AS icagenda_events FROM tslew_icagenda_events WHERE tslew_icagenda_events.modified>'$lastUpdate'";
        $Ores1=$Bdd->query($req1);
        $nb2=$Ores1->fetch();
        $nb3=$nb2->icagenda_events;

        //$nb1=2;

        if($nb1>0 or $nb3>0){
            echo "plus que 0 ";

        $conn = mysqli_connect($serveur,$login, $password, $base);

        
        $entete  = "-- ----------------------\n";
        $entete .= "-- dump de la base ".$base." au ".date("d-M-Y")."\n";
        $entete .= "-- ----------------------\n\n\n";
        $creations = "";
        $insertions = "\n\n";
        
        $listeTables = $conn->query("show tables");
        while($table = mysqli_fetch_array($listeTables))
        {
            // structure ou la totalité de la BDD
            if($mode == 1 || $mode == 2)
            {
                $creations .= "-- -----------------------------\n";
                $creations .= "-- Structure de la table ".$table[0]."\n";
                $creations .= "-- -----------------------------\n";
                $listeCreationsTables = $conn->query("show create table ".$table[0])
    ;
                while($creationTable = mysqli_fetch_array($listeCreationsTables))
                {
                $creations .= $creationTable[1].";\n\n";
                }
            }
            // données ou la totalité
            if($mode > 1)
            {
                $donnees = $conn->query("SELECT * FROM ".$table[0]);
                $insertions .= "-- -----------------------------\n";
                $insertions .= "-- Contenu de la table ".$table[0]."\n";
                $insertions .= "-- -----------------------------\n";
                while($nuplet = mysqli_fetch_array($donnees))
                {
                    $insertions .= "INSERT INTO ".$table[0]." VALUES(";
                    for($i=0; $i < mysqli_num_fields($donnees); $i++)
                    {
                    if($i != 0)
                        $insertions .=  ", ";
                    if(mysqli_fetch_field_direct($donnees, $i) == "string" || 
    mysqli_fetch_field_direct($donnees, $i) == "blob")
                        $insertions .=  "'";
                    $insertions .= addslashes($nuplet[$i]);
                    if(mysqli_fetch_field_direct($donnees, $i) == "string" || 
    mysqli_fetch_field_direct($donnees, $i) == "blob")
                        $insertions .=  "'";
                    }
                    $insertions .=  ");\n";
                }
                $insertions .= "\n";
            }
        }
    
        mysqli_close($conn);
    
        $fichierDump = fopen("sauvegarde.sql", "wb");
        fwrite($fichierDump, $entete);
        fwrite($fichierDump, $creations);
        fwrite($fichierDump, $insertions);
        fclose($fichierDump);


        $conn_id = ftp_connect($ftp_host);
        // on se connecte en tant qu'utilisateur
        $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
        // on active le mode passif 
        ftp_pasv($conn_id, true);
        ftp_chdir($conn_id,'/Volume_2/Backup');
        // si on est connecté avec succès, on transfère le fichier
        if($login_result && ftp_put($conn_id, 'sauvegarde.sql', 'sauvegarde.sql', FTP_BINARY, 0)){
            // si le transfert a fonctionné, on supprime le fichier local
            unlink('sauvegarde.sql');
        }
        // on clos la connexion
        ftp_close($conn_id);
        }


        $fichier=glob("images/corbeille/*.jpg");
        //print_r($fichier);
        $len=count($fichier);


        $corbeille=filesize("images/corbeille");
        if ($corbeille>0){
            $icorbeille=0;
            while ($icorbeille<$len){
                $local_file = $fichier[$icorbeille]; //chemin vers le fichier local;
                $file = basename($local_file);

                // on établit la connexion au serveur
                $conn_id = ftp_connect($ftp_host);
                // on se connecte en tant qu'utilisateur
                $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
                // on active le mode passif 
                ftp_pasv($conn_id, true);
                ftp_chdir($conn_id,'/Volume_2/Corbeille');
                // si on est connecté avec succès, on transfère le fichiers
                if($login_result && ftp_put($conn_id, $file, $local_file, FTP_BINARY,0)){
                    // si le transfert a fonctionné, on supprime le fichier local
                    unlink($local_file);
                }
                // on clos la connexion
                ftp_close($conn_id);
                $icorbeille=$icorbeille+1;

            }

        }

    }


    
    $db_server = 'localhost'; // Adresse du serveur MySQL
    $db_name = '';            // Nom de la base de données
    $db_user_login = '';  // Nom de l'utilisateur
    $db_user_pass = '';       // Mot de passe de l'utilisateur

    $bdUser = ""; // Utilisateur de la base de données
    $bdPasswd = ""; // Son mot de passe
    $dbname = ""; // nom de la base de données
    $host = "localhost"; // Hôte


    image('images',1);
    enregistrer();
    sendMail();
    dump_MySQL($db_server, $db_user_login, $db_user_pass, $db_name, 2);


?>