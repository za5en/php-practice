<?php
 error_reporting(E_ALL);

 ini_set("display_errors", 1);
    session_start();
    $step = $_GET["step"] ?? 0;
    $_GET["step"] = $step;
?><!DOCTYPE html>

<head>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>оформление заказа</title>
</head>

<body>
<form method="POST" action="/index.php?step=<?php echo ++$step; ?>">
    <?php
    $step = $_GET["step"];
    switch($step) {
        case 0:
            ?> <div>
        
            <div>
                <input type="radio" id="fiz" name="type" value="P">
                <label for="fiz">Физ. лицо</label>
            </div>
            <div>
                <input type="radio" id="ur" name="type" value="U">
                <label for="ur">Юр. лицо</label>
            </div>
            <div>
                <input type="submit" id="ok" name="button" onClick="typeValidation()" value="ОК">
                <script>
                    function typeValidation()
                    {
                        var userType = document.getElementsByName('type');
                        for (var i = 0; i < userType.length; i++) {
                            if (userType[i].checked){
                                break;
                            }
                        }
                        if (i == userType.length) {
                            return document.getElementById("error").innerHTML = "Please check any radio button";
                        }
                      return document.getElementById("error").innerHTML = "You select option " + (i + 1);
                    }
                </script>
                <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $step = $step + 1;
            $_GET["step"] = $step;
        }
        break;
        ?>
            </div>
    </div>
        

    <?php
        case 1:
            if (isset($_POST['type'])) {                 
                $type = $_POST['type'];
           
                if ($type == "P") {
                    $_SESSION['Phys'] = true;
                } else {
                    $_SESSION['Phys'] = false;
                }

            } else {
                $step = $step - 1;
            }

            if ($_SESSION['Phys'])
            {
            ?>
                <div class="field">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" required minlength="1">
                </div>
                <div class="field">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" name="phone" required pattern="[0-9]{11}">
                </div>
                <div>
                    <input type="submit" id="ok" name="button"  value="ОК">
                </div>
            <?php
            } else {
                ?>
                <div class="field">
                    <label for="name">Название</label>
                    <input type="text" id="name" name="name" required minlength="1">
                </div>
                <div class="field">
                    <label for="inn">ИНН</label>
                    <input type="text" id="inn" name="inn" required pattern="[0-9]{10|12}">
                </div>
                <div class="field">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" name="phone" required pattern="[0-9]{11}">
                </div>
                <div>
                    <input type="submit" id="ok" name="button" value="ОК">
                </div>
            <?php
            }
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $step = $step + 1;
                $_GET["step"] = $step;
            }
            break;
        case 2:
            if ($_SESSION['Phys'])
            {
                if (isset($_POST['phone']))
                {
                    $_SESSION["name"] = $_POST["name"];
                    $_SESSION["phone"] = $_POST["phone"];
                } else {
                    $step = $step - 1;
                }
            } else {
                if (isset($_POST['phone']))
                {
                    $_SESSION["name"] = $_POST["name"];
                    $_SESSION["phone"] = $_POST["phone"];
                    $_SESSION["inn"] = $_POST["inn"];
                } else {
                    $step = $step - 1;
                }
            }
            ?>
                <div>
                    <input type="radio" id="shop" name="where" value="M">
                    <label for="shop">Магазин</label>
                    <select name="shops">
                        <option selected disabled></option>
                        <option value="м. Юго-Западная">м. Юго-Западная</option>
                        <option value="м. Пятницкое шоссе">м. Пятницкое шоссе</option>
                        <option value="м. Измайловская">м. Измайловская</option>
                        <option value="м. Люблино">м. Люблино</option>
                        <option value="м. Крылатское">м. Крылатское</option>
                    </select>
                </div>
                <div>
                    <input type="radio" id="addr" name="where" value="D">
                    <label for="addr">Доставка</label>
                    <input type="text" id="addres" name="addres">
                </div>
                <div>
                    <input type="submit" id="ok" name="button" value="ОК">
                </div>
            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $step = $step + 1;
                $_GET["step"] = $step;
            }   
            break;
        case 3:
            if (isset($_POST['where']))
            {
                $where = $_POST["where"];
                if ($where == "M") {
                    $_SESSION["pickup"] = true;
                    $_SESSION["address"] = $_POST["shops"];
                } else {
                    $_SESSION["pickup"] = false;
                    $_SESSION["address"] = $_POST["addres"];
                }
            } else {
                $step = $step - 1;
                // header("Location: /index.php?step=$step");
                // exit();
            }
            ?>
            <div>
                <?php if ($_SESSION['Phys']) {
                    ?>  
                <p>Вы: Физ. лицо</p>  
                <p>Имя: <?php echo $_SESSION["name"];?>
                <br>Телефон: <?php echo $_SESSION["phone"];?>
                </p>
                <?php } else {
                    ?>
                <p>Вы: Юр. лицо</p> 
                <p>Название: <?php echo $_SESSION["name"];?>
                <br>ИНН: <?php echo $_SESSION["inn"];?>
                <br>Телефон: <?php echo $_SESSION["phone"];?>
                </p>
                <?php } if ($_SESSION['pickup']) {?>
                <p>Доставка: Самовывоз</p>
                <p>Адрес: <?php echo $_SESSION["address"];?></p>
                <?php } else {
                    ?>
                <p>Доставка: По адресу</p>
                <p>Адрес: <?php echo $_SESSION["address"];?></p>
                <?php } ?>
            </div>
            </form>
            <div>

            <form method="POST" action="/index.php?step=0">
                <input type="submit" id="reset" name="reset" value="Сброс">
                </form>
            </div>
            <?php
            if (isset($_POST['reset']))
            {
                $step = 0;
                $_SESSION["step"] = 0;
                $_GET["step"] = 0;
                session_destroy();
            }
            break;
    }
?>
 
</body><html>