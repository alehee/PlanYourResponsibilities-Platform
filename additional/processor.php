<?php
    session_start();

    require_once('func.php');

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    // DODAWANIE NOWEGO ZADANIA OGÓLNEGO
    if(isset($_GET['the_job'])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '
        <b>NOWE ZADANIE</b><br><br>
        <form method="POST" action="additional/newjob.php">
        <div class="okno" style="padding:10px;">
            <div style="font-size:100%; text-align:center;"><b>Tytuł:</b> <input type="text" name="new_title" style="font-size:100%; width:400px;" required/> <span style="padding-left:10px;"></span> <b>Deadline:</b> <input type="date" style="font-size:100%;" name="new_deadline" required/></div>
            <div style="font-size:100%; text-align:center;"><b>Długość zadania:</b> 
            <select name="new_length" style="font-size:100%; width:100px; margin:10px;" required>
                <option value="3">Krótkie</option>
                <option value="2">Średnie</option>
                <option value="1">Długie</option>
            </select> </div>

            <div style="font-size:100%; text-align:center;"><b>Dodatkowe informacje:</b><br>
            <textarea name="new_info" style="font-size:100%; min-height:200px; width:80%; padding:5px;" /></textarea></div>

            <div style="font-size:100%; text-align:center;"><b>Dla kogo:</b></div>
        <div id="new_job_forwho" style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; padding-top:10px; padding-bottom:10px;">
        <div id="new_job_forwho_peoplenumber_img"><img src="icons/users.png"/></div><div id="new_job_forwho_peoplenumber_text">1</div>
        <div id="new_job_forwho_toggle" onclick="new_job_forwho_toggle()">PRZEŁĄCZ WIDOK</div>
        <div id="new_job_forwho_toggle" onclick="new_job_forwho_open_close()">OTWÓRZ/ZAMKNIJ ZAKŁADKI</div>
        <div style="clear:both;"></div>';

        // PODZIAŁ OSÓB NA DZIAŁY LEWO
        echo '<div class="new_job_forwho_dzial_left">';

        // INESIS
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ines' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_inesis" onclick="$(\'#new_job_forwho_inesis_list\').slideToggle(1);">INESIS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_inesis_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // DOMYOS
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='domy' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_domyos" onclick="$(\'#new_job_forwho_domyos_list\').slideToggle(1);">DOMYOS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_domyos_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // QUECHUA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='quec' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_quechua"onclick="$(\'#new_job_forwho_quechua_list\').slideToggle(1);">QUECHUA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_quechua_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KALENJI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kale' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kalenji"onclick="$(\'#new_job_forwho_kalenji_list\').slideToggle(1);">KALENJI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kalenji_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // SUBEA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='sube' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_subea"onclick="$(\'#new_job_forwho_subea_list\').slideToggle(1);">SUBEA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_subea_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';



        // KIEROWNICY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='kier' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_kierownicy"onclick="$(\'#new_job_forwho_kierownicy_list\').slideToggle(1);">KIEROWNICY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_kierownicy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // STAŻYŚCI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='staz' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_stazysci"onclick="$(\'#new_job_forwho_stazysci_list\').slideToggle(1);">STAŻYŚCI</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_stazysci_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // INNA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='inna' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_inna"onclick="$(\'#new_job_forwho_inna_list\').slideToggle(1);">INNA</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_inna_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY LEWO
        echo '
                </div>
        ';

        // PODZIAŁ OSÓB NA DZIAŁY PRAWO
        echo '<div class="new_job_forwho_dzial_right">';

        // WYSOKI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='wskl' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_wskl" onclick="$(\'#new_job_forwho_wskl_list\').slideToggle(1);">WYSOKI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_wskl_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // B'TWIN
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='btwn' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_btwin" onclick="$(\'#new_job_forwho_btwin_list\').slideToggle(1);">B\'TWIN</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_btwin_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // E-COMMERCE
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ecom' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_ecommerce" onclick="$(\'#new_job_forwho_ecommerce_list\').slideToggle(1);">E-COMMERCE</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_ecommerce_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // RAMPA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ramp' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_rampa" onclick="$(\'#new_job_forwho_rampa_list\').slideToggle(1);">RAMPA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_rampa_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // GEOLOGIC
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='geol' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_geologic" onclick="$(\'#new_job_forwho_geologic_list\').slideToggle(1);">GEOLOGIC</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_geologic_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KADRY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kadr' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kadry" onclick="$(\'#new_job_forwho_kadry_list\').slideToggle(1);">KADRY</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kadry_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';



        // SZKOLENIOWCY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='szko' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_szkoleniowcy"onclick="$(\'#new_job_forwho_szkoleniowcy_list\').slideToggle(1);">SZKOLENIOWCY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_szkoleniowcy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // PRACOWNICY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='prac' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_pracownicy"onclick="$(\'#new_job_forwho_pracownicy_list\').slideToggle(1);">PRACOWNICY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_pracownicy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY PRAWO
        echo '
                </div>
        ';

        echo '
            <div style="clear:both;"></div>
            <div style="margin:10px;">
                <input type="button" class="new_job_dzial_butt" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Inesis" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Wysoki" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Domyos" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="B\'Twin" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Quechua" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="E-commerce" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Kalenji" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Rampa" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Subea" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Geologic" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Kadry" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Kierownicy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Szkoleniowcy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Stażyści" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Pracownicy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Inna" onclick="new_job_toggle(this.value)"/>
            </div>
        </div>
            <input class="new_job_butt" type="submit" value="UTWÓRZ ZADANIE"/>
        </form>
        </div>
        ';

        // DALSZA CZĘŚĆ W NEWJOB.PHP

        unset($_GET['the_job']);
    }

    // DODAWANIE NOWEGO ZADANIA KADROWEGO
    if(isset($_GET['the_staffjob'])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '
        <b>NOWE ZADANIE KADROWE</b><br><br>
        <form method="POST" action="additional/newstaffjob.php">
        <div class="okno" style="padding:10px;">
            <div style="font-size:100%; text-align:center;"><b>Tytuł:</b> <input type="text" name="new_title" style="font-size:100%; width:400px;" required/> <span style="padding-left:10px;"></span> <b>Deadline:</b> <input type="date" style="font-size:100%;" name="new_deadline" required/></div>
            <div style="font-size:100%; text-align:center;"><b>Długość zadania:</b> 
            <select name="new_length" style="font-size:100%; width:100px; margin:10px;" required>
                <option value="3">Krótkie</option>
                <option value="2">Średnie</option>
                <option value="1">Długie</option>
            </select> </div>

            <div style="font-size:100%; text-align:center;"><b>Dodatkowe informacje:</b><br>
            <textarea name="new_info" style="font-size:100%; min-height:200px; width:80%; padding:5px;" /></textarea></div>

            <div style="font-size:100%; text-align:center;"><b>Dla kogo:</b></div>
        <div id="new_job_forwho" style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; padding-top:10px; padding-bottom:10px;">
        <div id="new_job_forwho_peoplenumber_img"><img src="icons/users.png"/></div><div id="new_job_forwho_peoplenumber_text">1</div>
        <div id="new_job_forwho_toggle" onclick="new_job_forwho_toggle()">PRZEŁĄCZ WIDOK</div>
        <div id="new_job_forwho_toggle" onclick="new_job_forwho_open_close()">OTWÓRZ/ZAMKNIJ ZAKŁADKI</div>
        <div style="clear:both;"></div>';

        // PODZIAŁ OSÓB NA DZIAŁY LEWO
        echo '<div class="new_job_forwho_dzial_left">';

        // INESIS
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ines' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_inesis" onclick="$(\'#new_job_forwho_inesis_list\').slideToggle(1);">INESIS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_inesis_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // DOMYOS
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='domy' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_domyos" onclick="$(\'#new_job_forwho_domyos_list\').slideToggle(1);">DOMYOS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_domyos_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // QUECHUA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='quec' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_quechua"onclick="$(\'#new_job_forwho_quechua_list\').slideToggle(1);">QUECHUA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_quechua_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KALENJI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kale' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kalenji"onclick="$(\'#new_job_forwho_kalenji_list\').slideToggle(1);">KALENJI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kalenji_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // SUBEA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='sube' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_subea"onclick="$(\'#new_job_forwho_subea_list\').slideToggle(1);">SUBEA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_subea_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';



        // KIEROWNICY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='kier' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_kierownicy"onclick="$(\'#new_job_forwho_kierownicy_list\').slideToggle(1);">KIEROWNICY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_kierownicy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // STAŻYŚCI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='staz' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_stazysci"onclick="$(\'#new_job_forwho_stazysci_list\').slideToggle(1);">STAŻYŚCI</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_stazysci_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // INNA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='inna' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_inna"onclick="$(\'#new_job_forwho_inna_list\').slideToggle(1);">INNA</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_inna_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY LEWO
        echo '
                </div>
        ';

        // PODZIAŁ OSÓB NA DZIAŁY PRAWO
        echo '<div class="new_job_forwho_dzial_right">';

        // WYSOKI
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='wskl' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_wskl" onclick="$(\'#new_job_forwho_wskl_list\').slideToggle(1);">WYSOKI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_wskl_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // B'TWIN
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='btwn' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_btwin" onclick="$(\'#new_job_forwho_btwin_list\').slideToggle(1);">B\'TWIN</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_btwin_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // E-COMMERCE
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ecom' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_ecommerce" onclick="$(\'#new_job_forwho_ecommerce_list\').slideToggle(1);">E-COMMERCE</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_ecommerce_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // RAMPA
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ramp' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_rampa" onclick="$(\'#new_job_forwho_rampa_list\').slideToggle(1);">RAMPA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_rampa_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // GEOLOGIC
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='geol' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_geologic" onclick="$(\'#new_job_forwho_geologic_list\').slideToggle(1);">GEOLOGIC</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_geologic_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KADRY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kadr' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kadry" onclick="$(\'#new_job_forwho_kadry_list\').slideToggle(1);">KADRY</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kadry_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            if($res["Rola"]=="kier")
                echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
            else 
                echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';



        // SZKOLENIOWCY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='szko' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_szkoleniowcy"onclick="$(\'#new_job_forwho_szkoleniowcy_list\').slideToggle(1);">SZKOLENIOWCY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_szkoleniowcy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // PRACOWNICY
        $sql = "SELECT ID, Imie, Nazwisko, Dzial, Rola FROM users WHERE Rola='prac' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_rola" id="new_job_forwho_pracownicy"onclick="$(\'#new_job_forwho_pracownicy_list\').slideToggle(1);">PRACOWNICY</div>
                <div class="new_job_forwho_rola_list" id="new_job_forwho_pracownicy_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" onchange="new_job_forwho_check('.$res["ID"].', this.checked);" class="'.$res["Rola"].' '.$res["ID"].' new_job_forwho_checkbox" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY PRAWO
        echo '
                </div>
        ';

        echo '
            <div style="clear:both;"></div>
            <div style="margin:10px;">
                <input type="button" class="new_job_dzial_butt" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Inesis" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Wysoki" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Domyos" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="B\'Twin" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Quechua" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="E-commerce" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Kalenji" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Rampa" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Subea" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Geologic" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Kadry" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Kierownicy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Szkoleniowcy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Stażyści" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Pracownicy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_rola_butt" value="Inna" onclick="new_job_toggle(this.value)"/>
            </div>
        </div>
            <input class="new_job_butt" type="submit" value="UTWÓRZ ZADANIE"/>
        </form>
        </div>
        ';

        // DALSZA CZĘŚĆ W NEWSTAFFJOB.PHP

        unset($_GET['the_staffjob']);
    }

    // OKNO ZADANIA
    else if(isset($_GET['elem'])){

        $the_id_processor = $_GET['elem'];
        $user_id_processor = $_SESSION['id'];
        $whoadd_processor;

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        // WYŚLIJ INFORMACJE ŻE ODWIEDZIŁ ZADANIE
        $sql = "UPDATE job SET Visited=CURRENT_TIMESTAMP WHERE The_ID=$the_id_processor AND ForWho=$user_id_processor";
        mysqli_query($conn, $sql);

        $sql = "SELECT * FROM job WHERE The_ID=$the_id_processor LIMIT 1";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){

            // WYSYŁA INFORMACJE ŻE TWÓRCA ZADANIA ODWIEDZIŁ JE
            if($user_id_processor == $res["WhoAdd"]){
                $temp_sql = "UPDATE job SET Visited_Admin=CURRENT_TIMESTAMP WHERE The_ID=$the_id_processor";
                mysqli_query($conn, $temp_sql);
            }
        
            $processor_forwho_array = array();
            $whoadd_processor = $res["WhoAdd"];

            // ZMIENIA STYL OKNA
            $days_left = how_many_days_left($res["End"]);
            if($days_left<=0){
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="red";
                    document.getElementById("okno_job").style.border="5px solid red";
                </script>
                ';
            }
            else if($days_left<3){
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="#ffbf00";
                    document.getElementById("okno_job").style.border="5px solid #ffbf00";
                </script>
                ';
            }
            else{
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="#0082C3";
                    document.getElementById("okno_job").style.border="5px solid #0082C3";
                </script>
                ';
            }

            // TYTUŁ
            $topic = $res["Topic"];
            $bufor = "";
            if(strlen($topic)>200)
            {
                    for($i=0; $i<200; $i++)
                    {
                        if($i>180 && $topic[$i]==" ")
                        {
                            echo "...";
                            $i=199;
                        }
                        else 
                            echo $topic[$i];
                    }
            }
            else
                $bufor=$topic;

            echo "<b>".$bufor."</b><br><br>";
            // -----

            echo "<div class='okno'>";
            
            // DATA KOŃCA ZADANIA
            echo "<div class='okno_element'><img src='icons/hourglass.png'/><span>".proper_date($res["End"])."</span></div>";
            // -----

            // ILOŚĆ OSÓB W ZADANIU
            $the_id = $res["The_ID"];
            $how_many_per=0;
            $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
            $temp_que=mysqli_query($conn, $temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $how_many_per++;
            }
            $temp_sql="SELECT ForWho FROM done WHERE The_ID=$the_id";
            $temp_que=mysqli_query($conn, $temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $how_many_per++;
            }
            echo "<div class='okno_element_s'><img src='icons/users.png'/>".$how_many_per."</div>";
            // -----

            // DŁUGOŚĆ ZADANIA
            if($res["Length"]==1){
                echo "<div class='okno_element_s'><img src='icons/speed-1.png'/>Długie</div>";
            }
            else if($res["Length"]==2){
                echo "<div class='okno_element_s'><img src='icons/speed-2.png'/>Średnie</div>";
            }
            else{
                echo "<div class='okno_element_s'><img src='icons/speed-3.png'/>Krótkie</div>";
            }
            // -----

            // LICZNIK ZAŁĄCZNIKÓW
            $how_many_atta=0;
            $the_id = $res["The_ID"];
            $string = $res["Info"];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            $bufor = $string;
            while($pos = strpos($bufor, "a href=")){
                $bufor[$pos]="x";
                $how_many_atta++;
            }
            $temp_sql="SELECT Message FROM chat WHERE The_ID='$the_id'";
            $temp_que=mysqli_query($conn, $temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $string=$temp_res["Message"];
                $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
                $bufor = $string;
                while($pos = strpos($bufor, "a href=")){
                    $bufor[$pos]="x";
                    $how_many_atta++;
                }
            }
            echo "<div class='okno_element_s'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
            // -----

            // KTO DODAŁ ZADANIE
            echo "<div class='okno_element'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
            // -----
            echo "<div style='clear:both;'></div>";

            // INFORMACJE DODATKOWE W ZADANIU
                $string = $res["Info"];
                $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);

                echo '<div style="padding:30px;"><b>'.nl2br($string).'</b></div>';
                echo '<div style="clear:both; text-align:right; font-size:60%; color:gray; margin-right:30px;">ID: '.$the_id_processor.'</div>';
            //

            // OSOBY BIORĄCE UDZIAŁ
            echo "<div style='background-color:#e6e6e6; border-radius:20px; width:98%; min-height:50px; margin:1%; padding:1%; padding-top:0.5%; font-size:80%;'>";
            echo "<ul>";
            $temp_sql = "SELECT ForWho FROM job WHERE The_ID='$the_id_processor'";
            $temp_que = mysqli_query($conn, $temp_sql);
            while($temp = mysqli_fetch_array($temp_que)){
                $other_forwho = $temp["ForWho"];
                echo "<li>".name_by_id($other_forwho)."</li>";
				array_push($processor_forwho_array, $other_forwho);
            }
            $temp_sql = "SELECT ForWho FROM done WHERE The_ID='$the_id_processor'";
            $temp_que = mysqli_query($conn, $temp_sql);
            while($temp = mysqli_fetch_array($temp_que)){
                $other_forwho = $temp["ForWho"];
                echo "<li class='done_job_li'>".name_by_id($other_forwho)."</li>";
            }
            echo "</ul>";
            echo "<div style='clear:both;'/></div>";
            // -----

            // PANEL PRZYCISKÓW
            echo '<div id="div_panel">';
                echo '<div id="'.$the_id_processor.'" class="okno_addperson" onclick="job_addperson(this.id)">DODAJ OSOBĘ</div>';
                $processor_forme=0;
                foreach($processor_forwho_array as $x){
                    if($x == $_SESSION['id'])
                        $processor_forme=1;
                }
                if($processor_forme==1){
                echo '<div id="'.$the_id_processor.'" class="okno_done" onclick="job_done(this.id)">ZAKOŃCZ</div>';
                }

                $temp_sql="SELECT ID FROM job WHERE The_ID=$the_id_processor AND WhoAdd=$user_id_processor LIMIT 1";
                $temp_que = mysqli_query($conn, $temp_sql);
                while($temp = mysqli_fetch_array($temp_que)){
                    echo '<div id="'.$the_id_processor.'" class="okno_edit" onclick="job_edit(this.id)">EDYTUJ ZADANIE</div>';
                    echo '<div id="okno_more_options_button" onclick="$(\'#okno_more_options\').slideToggle(\'slow\');">WIĘCEJ OPCJI</div>';
                    echo '<div id="okno_more_options">';
                        echo '<div id="'.$the_id_processor.'" class="okno_delperson" onclick="job_delperson(this.id)">USUŃ OSOBĘ</div>';
                        echo '<div id="'.$the_id_processor.'" class="okno_deljob" onclick="job_deljob(this.id)">USUŃ CAŁE ZADANIE</div>';
                    echo '</div>';
                }

            echo '</div>';
            echo '<div style="clear:both;"></div>';
            // -----
        }
		
        // CHAT
        echo "<div style='padding-top:30px;'>";

		$sql="SELECT * FROM chat WHERE The_ID=$the_id_processor ORDER BY Date ASC";
		$que = mysqli_query($conn, $sql);
		while($res = mysqli_fetch_array($que)){
            echo '<div id="okno_chat_message"';

            // ZMIENIA KOLOR OKIENKA CHATU W ZALEŻNOŚCI OD TEGO ILE DNI DO KOŃCA
            if($days_left<=0)
                echo ' style="border:2px solid red"><br>';
            else if($days_left<3)
                echo ' style="border:2px solid #ffbf00"><br>';
            else
                echo '><br>';

            if($res["SentFrom"]==$_SESSION["id"]){
                echo '<input type="button" value="x" id="'.$res["ID"].'" class="okno_chat_delmsg" onclick="job_chatmsqdelete(this.id)" title="Usuń wiadomość"/>';
                echo '<div style="clear:both;"></div>';
            }
            
            // KONWERTER LINKÓW, DZIĘKI STACKOVERFLOW!
            $string = $res['Message'];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            echo nl2br($string);
            
            $processor_chat_id=$res['SentFrom'];
            $processor_chat_realname = name_by_id($processor_chat_id);
            
            echo '<div style="clear:both;"></div>';
            echo '<span style="float:right; text-align:right; font-size:60%; color:gray; margin-right:30px;">'.$processor_chat_realname.', '.proper_date($res['Date']).'</span>';
            echo '<div style="clear:both;"></div>';
            echo '</div>';
        }
        
        echo '<textarea style="width:90%; min-height:60px; margin:10px 5%; padding:5px; font-size:100%;" id="okno_chat_chatbox" class="okno_chat_style"/>';
        echo '<div id="'.$the_id_processor.'" class="okno_chat_butt" onclick="okno_sentmessage(this.id)">WYŚLIJ WIADOMOŚĆ</div>';
        echo "</div>";
        // -----

        echo "</div>";

        unset($_GET['elem']);
    }
	
	// OKNO DODANIA NOWEJ OSOBY DO ZADANIA
	else if(isset($_GET["addperson_id"])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		$addperson_id = $_GET['addperson_id'];
		$_SESSION["the_job"]=$addperson_id;
        $addperson_user_id = $_SESSION["id"];
        $addperson_topic;
		$is_in = array();
		
		$sql="SELECT ForWho, Topic FROM job WHERE The_ID='$addperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
            $addperson_topic = $res["Topic"];
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
        }
        echo "<b>$addperson_topic</b><br><br>";
        echo '<div class="okno">';
		echo '<form action="additional/addperson.php" method="POST">';
        echo '<div style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; font-weight:800; padding-top:10px; font-size:150%;">DODAJ NOWĄ OSOBĘ DO ZADANIA</div>';
        echo '<div id="new_job_forwho">';



        $anyone=0;

        echo '<div id="new_job_forwho_toggle" onclick="new_job_forwho_open_close()">OTWÓRZ/ZAMKNIJ ZAKŁADKI</div>
        <div style="clear:both;"></div>';

        // PODZIAŁ OSÓB NA DZIAŁY LEWO
        echo '<div class="new_job_forwho_dzial_left">';

        // INESIS
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ines' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_inesis" onclick="$(\'#new_job_forwho_inesis_list\').slideToggle(1);">INESIS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_inesis_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // DOMYOS
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='domy' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_domyos" onclick="$(\'#new_job_forwho_domyos_list\').slideToggle(1);">DOMYOS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_domyos_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // QUECHUA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='quec' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_quechua"onclick="$(\'#new_job_forwho_quechua_list\').slideToggle(1);">QUECHUA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_quechua_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KALENJI
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kale' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kalenji"onclick="$(\'#new_job_forwho_kalenji_list\').slideToggle(1);">KALENJI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kalenji_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // SUBEA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='sube' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_subea"onclick="$(\'#new_job_forwho_subea_list\').slideToggle(1);">SUBEA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_subea_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY LEWO
        echo '
                </div>
        ';

        // PODZIAŁ OSÓB NA DZIAŁY PRAWO
        echo '<div class="new_job_forwho_dzial_right">';

        // WYSOKI
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='wskl' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_wskl" onclick="$(\'#new_job_forwho_wskl_list\').slideToggle(1);">WYSOKI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_wskl_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // B'TWIN
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='btwn' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_btwin" onclick="$(\'#new_job_forwho_btwin_list\').slideToggle(1);">B\'TWIN</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_btwin_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // E-COMMERCE
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ecom' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_ecommerce" onclick="$(\'#new_job_forwho_ecommerce_list\').slideToggle(1);">E-COMMERCE</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_ecommerce_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // RAMPA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ramp' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_rampa" onclick="$(\'#new_job_forwho_rampa_list\').slideToggle(1);">RAMPA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_rampa_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // GEOLOGIC
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='geol' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_geologic" onclick="$(\'#new_job_forwho_geologic_list\').slideToggle(1);">GEOLOGIC</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_geologic_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KADRY
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kadr' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kadry" onclick="$(\'#new_job_forwho_kadry_list\').slideToggle(1);">KADRY</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kadry_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="addperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY PRAWO
        echo '
                </div>
        ';

        echo '<div style="clear:both;"></div>';
        if($anyone==0){
            echo '<div style="text-align:center; font-size:100%; font-weight:800; width:100%; height:20px;">WSZYSCY UCZESTNICZĄ W ZADANIU!</div>';
            echo '</div>';
        }
        else{
            echo '</div>';
            echo '<input type="submit" class="okno_addperson_butt" value="DODAJ OSOBĘ"/>';
        }
        echo '</form>';
        //KONIEC DIVA OKNO
        echo '</div>';
		
		unset($_GET['addperson_id']);
    }

    // OKNO USUWANIA OSOBY Z ZADANIA
    else if(isset($_GET["delperson_id"])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $delperson_id = $_GET['delperson_id'];
		$_SESSION["the_job"]=$delperson_id;
		$delperson_user_id = $_SESSION["id"];
        $is_in = array();
        $delperson_topic;
		
		$sql="SELECT ForWho, Topic FROM job WHERE The_ID='$delperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
            $delperson_topic = $res["Topic"];
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
        }
        echo "<b>$delperson_topic</b><br><br>";
        echo '<div class="okno">';
		echo '<form action="additional/delperson.php" method="POST">';
        echo '<div style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; font-weight:800; padding-top:10px; font-size:150%;">USUŃ OSOBĘ Z ZADANIA</div>';
        echo '<div id="new_job_forwho">';


        /*
        $sql = "SELECT ID, Login, Imie, Nazwisko FROM users  ORDER BY Nazwisko ASC";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
		    }
			
			if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" name="delperson_who" value="'.$res["ID"].'"/> '.$res["Imie"]." ".$res["Nazwisko"]."</div>";
                
                array_push($is_in, $res["Login"]);
			}
        }
        */


        echo '<div id="new_job_forwho_toggle" onclick="new_job_forwho_open_close()">OTWÓRZ/ZAMKNIJ ZAKŁADKI</div>
        <div style="clear:both;"></div>';

        // PODZIAŁ OSÓB NA DZIAŁY LEWO
        echo '<div class="new_job_forwho_dzial_left">';

        // INESIS
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ines' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_inesis" onclick="$(\'#new_job_forwho_inesis_list\').slideToggle(1);">INESIS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_inesis_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // DOMYOS
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='domy' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_domyos" onclick="$(\'#new_job_forwho_domyos_list\').slideToggle(1);">DOMYOS</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_domyos_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // QUECHUA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='quec' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_quechua"onclick="$(\'#new_job_forwho_quechua_list\').slideToggle(1);">QUECHUA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_quechua_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KALENJI
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kale' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kalenji"onclick="$(\'#new_job_forwho_kalenji_list\').slideToggle(1);">KALENJI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kalenji_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // SUBEA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='sube' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_subea"onclick="$(\'#new_job_forwho_subea_list\').slideToggle(1);">SUBEA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_subea_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY LEWO
        echo '
                </div>
        ';

        // PODZIAŁ OSÓB NA DZIAŁY PRAWO
        echo '<div class="new_job_forwho_dzial_right">';

        // WYSOKI
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='wskl' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_wskl" onclick="$(\'#new_job_forwho_wskl_list\').slideToggle(1);">WYSOKI</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_wskl_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // B'TWIN
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='btwn' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_btwin" onclick="$(\'#new_job_forwho_btwin_list\').slideToggle(1);">B\'TWIN</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_btwin_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // E-COMMERCE
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ecom' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_ecommerce" onclick="$(\'#new_job_forwho_ecommerce_list\').slideToggle(1);">E-COMMERCE</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_ecommerce_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // RAMPA
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='ramp' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_rampa" onclick="$(\'#new_job_forwho_rampa_list\').slideToggle(1);">RAMPA</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_rampa_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // GEOLOGIC
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='geol' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_geologic" onclick="$(\'#new_job_forwho_geologic_list\').slideToggle(1);">GEOLOGIC</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_geologic_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KADRY
        $sql = "SELECT ID, Imie, Login, Nazwisko, Dzial, Rola FROM users WHERE Dzial='kadr' ORDER BY Rola ASC";
        echo '
            <div class="new_job_forwho_dzial" id="new_job_forwho_kadry" onclick="$(\'#new_job_forwho_kadry_list\').slideToggle(1);">KADRY</div>
                <div class="new_job_forwho_dzial_list" id="new_job_forwho_kadry_list">';

        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            $is_out=1;

            foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}

            if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" class="'.$res["Dzial"].' '.$res["ID"].' new_job_forwho_checkbox" name="delperson_who" value="'.$res["ID"].'"';

                if($res["ID"]==$_SESSION["id"])
                    echo "checked";

                if($res["Rola"]=="kier")
                    echo '/> <b>'.$res['Imie']." ".$res["Nazwisko"]."</b></div>";
                else 
                    echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";

                $anyone=1;
                array_push($is_in, $res["Login"]);
            }
        }
        echo '<div style="clear:both;"></div></div>';

        // KONIEC DZIAŁY PRAWO
        echo '
                </div>
        ';

        echo '<div style="clear:both;"></div>';
        echo '</div>';
		echo '<input type="submit" class="okno_delperson_butt" value="USUŃ OSOBĘ"/>';
        echo '</form>';
        echo '</div>';

        unset($_GET["delperson_id"]);
    }

    // EDYTOWANIE ZADANIA
    else if(isset($_GET["edit_id"])){
        
        $edit_id = $_GET["edit_id"];
        $_SESSION["The_ID"] = $edit_id;

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '<b>EDYTUJ ZADANIE</b><br><br>';

        echo '<div class="okno" style="padding:10px;">';
        echo '<form action="additional/edit.php" method="POST">';

        $sql="SELECT Topic, Info, Length, End FROM job WHERE The_ID=$edit_id LIMIT 1";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="font-size:100%; text-align:center;"><b>Tytuł:</b> <input type="text" name="edit_title" style="width:400px; max-width:70%; font-size:100%;" value="'.$res["Topic"].'" required/> <span style="padding-left:10px;"></span> <b>Deadline:</b> <input type="date" style="font-size:100%;" name="edit_deadline" value="'.$res["End"].'" required/></div>

            <div style="font-size:100%; text-align:center;"><b>Długość zadania:</b> 
            <select name="edit_length" style="font-size:100%; width:100px; margin:10px;" required>';
            if($res["Length"]==3){
                echo '
                <option value="3">Krótkie</option>
                <option value="2">Średnie</option>
                <option value="1">Długie</option>
                ';
            }
            else if($res["Length"]==2){
                echo '
                <option value="2">Średnie</option>
                <option value="3">Krótkie</option>
                <option value="1">Długie</option>
                ';
            }
            else{
                echo '
                <option value="1">Długie</option>
                <option value="3">Krótkie</option>
                <option value="2">Średnie</option>
                ';
            }
            echo '</select> </div>

            <div style="font-size:100%; text-align:center; margin:20px width:80%"><b>Dodatkowe informacje:</b><br>
            <textarea name="edit_info" style="font-size:100%; min-height:200px; width:80%; padding:5px;">'.$res["Info"].'</textarea></div>

            <input class="okno_edit_butt" type="submit" value="ZAKOŃCZ EDYCJĘ"/>
            ';
        }

        echo '</form>';
        echo '</div>';

        unset($_GET['edit_id']);
    }

    mysqli_close($conn);
?>