<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Säffle PK</title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/own.css">
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body class="d-flex flex-column h-100">

    <?php
    include 'conn/conn.php';

    if (isset($_POST["inputEmailLog"])) {
      $stmt = $conn->prepare("SELECT losenord FROM anvandare WHERE epost == ?");
      $stmt->bind_param("s", $_POST["inputEmailLog"]);
      $stmt-> execute();
      $error = $stmt->errno;

      //Om användaren inte finns så öppnar den login rutan igen
      @$pass = $res_row[1];
      if (!password_verify($_POST["inputPassword"], $pass)) {
        alert('<button class="dropdown-item text-dark" data-toggle="modal" data-target="#loginForm">Fel vid inloggningen</button>');
      }

      if ($error) { //Skriver om det blir något fel
        die("Donnection failed: ".$error);
      }

      $stmt->close();

    } elseif (isset($_POST["inputEmailReg"])) {
      $namn = $_POST["inputFörnamn"].$_POST["inputEfternamn"];
      $epost = $_POST["inputEmailReg"];
      $password = password_hash($_POST["inputPassword"], PASSWORD_DEFAULT);

      //Registrerar ny användare
      $stmt = $conn->prepare("INSERT INTO anvandare (namn, epost, losenord) VALUES(?,?,?)");
      $stmt-> bind_param('sss', $namn, $epost, $password);
      $stmt->execute();
      $error = $stmt->errno;

      if ($error) {
        if ($error == 1062) {
          echo "Eposten finns redan.";
        }
      } else {
        die("Connection failed: " . $error);
      }
      $stmt->close();
    }
    ?>

    <header class="page-header">
      <img id="logo" src="https://www8.idrottonline.se/globalassets/saffle-pistolskytteklubb---skyttesport/webben/spk2.png" alt="">
    </header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <!--knapp när sidan blir liten-->
      <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.html"><i>Startsida</i><span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Konto</a>
            <div class="dropdown-menu bg-light dropdown-menu-right" aria-labelledby="navbarDropdown">
              <button class="dropdown-item text-dark" data-toggle="modal" data-target="#loginForm">Logga in</button>
              <?php
                if (isset($_POST["inputEmailLog"])) {
                  echo "<button class='dropdown-item text-dark' data-toggle='modal' data-target='#aktivForm'>Lägg till ny aktivitet</button>";
                } else {
                  echo "<button class='dropdown-item text-dark' data-toggle='modal' data-target='#mostLogIn'>Lägg till ny aktivitet</button>";
                }
               ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-dark" href="404.html">Logga ut</a>
            </div>
          </li>
        </ul><!--nav buttons-->
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" aria-label="Search" type="text" placeholder="Sök">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Sök</button>
        </form><!--sök-->
      </div><!--nav slut-->
    </nav>

    <main>
      <div class="container-fluid">
        <!--Login modal-->
        <div class="modal" id="loginForm" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title">Logga in</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div><!--modal-header-->
              <div class="modal-body">
                <form action="index.php" method="post" class="login">
                  <div class="form-group">
                    <input class="form-control" type="email" name="inputEmailLog" placeholder="Epost" id="logEmail" required>
                    <input class="form-control" type="password" name="inputPassword" placeholder="Lösenord" id="logPassword" required>
                  </div><!--modal-body-->
                  <div class="modal-footer">
                    <input class="form-control btn-primary" value="Logga in" type="submit" id="submitLog"><br>
                    <button class="form-control btn" type="button" data-toggle="modal" data-target="#regForm">Registrera dig</button>
                  </div><!--modal-footer-->
                </form>
              </div><!--modal-body-->
            </div><!--modal-content-->
          </div><!--modal-dialog-->
        </div><!--modal-->

        <!--Registrera modal-->
        <div class="modal" id="regForm" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title">Logga in</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div><!--modal-header-->
              <div class="modal-body form-group">
                <form action="index.php" method="post" class="registrera">
                  <h4>Förnamn</h4>
                  <input class="form-control" type="text" name="inputFörnamn" placeholder="" id="förnamn" required>
                  <h4>Efternamn</h4>
                  <input class="form-control" type="text" name="inputEfternamn" placeholder="" id="efternamn" required>
                  <h4>Epost</h4>
                  <input class="form-control" type="email" name="inputEmailReg" placeholder="" id="regEmail" required>
                  <h4>Lösenord</h4>
                  <input class="form-control" type="password" name="inputPassword" placeholder="" id="password" required>
                </div><!--modal-body-->
                <div class="modal-footer">
                  <input class="form-control btn-primary" value="Registrera" type="submit" id="submitReg">
                </form>
              </div><!--modal-footer-->
            </div><!--modal-content-->
          </div><!--modal-dialog-->
        </div><!--modal-->

        <!--Ny aktivitet modal-->
        <div class="modal" id="aktivForm" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title">Ny aktivitet</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div><!--modal-header-->
              <div class="modal-body form-group">
                <form action="index.php" method="post" class="aktivitet">
                  <h4>Dag</h4>
                  <input class="form-control" type="number" name="inputDag" placeholder="" id="dag" required>
                  <h4>Månad</h4>
                  <input class="form-control" type="text" name="inputMånad" placeholder="" id="månad" required>
                  <h4>Vad som ska göras</h4>
                  <input class="form-control" type="text" name="inputAktivitet" placeholder="" id="aktivitet" required>
                </div><!--modal-body-->
                <div class="modal-footer">
                  <input class="form-control btn-primary" value="Skicka" type="submit" id="submitAktiv">
                </form>
              </div><!--modal-footer-->
            </div><!--modal-content-->
          </div><!--modal-dialog-->
        </div><!--modal-->

        <!--Måste logga in modal-->
        <div class="modal" id="mostLogIn" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <div class="modal-body">Du måste logga in först</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div><!--modal-header-->
            </div><!--modal-content-->
          </div><!--modal-dialog-->
        </div><!--modal-->

        <div class="row">
          <div id="myCarousel" class="carousel slide col-md-9" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1" class=""></li>
              <li data-target="#myCarousel" data-slide-to="2" class=""></li>
            </ol><!--number of slides-->
            <div class="carousel-inner">
              <!--first slide-->
              <div class="carousel-item active">
                <img class="first-slide w-100" src="covid-19.png" alt="First slide">
                <div class="container">
                  <div class="carousel-caption">
                    <h1>Covid-19</h1>
                    <a class="btn btn-lg text-light" href="#" role="button">Information om våra aktiviteter i corona-tider</a>
                  </div><!--text-->
                </div>
              </div><!--slide-->
              <div class="carousel-item">
                <img class="second-slide w-100" src="skyttar.jpg" alt="Second slide">
                <div class="container">
                  <div class="carousel-caption text-light">
                    <h1>Nyfiken?</h1>
                    <a class="btn btn-lg text-light" href="#" role="button">Kolla här hur du kan börja med pistolskytte.</a>
                  </div><!--text-->
                </div>
              </div><!--slide-->
              <!--third slide-->
              <div class="carousel-item">
                <img class="third-slide w-100" src="covid-19.png" alt="Third slide">
                <div class="container">
                  <div class="carousel-caption">
                    <h1>Något mer, måste fixa något.</h1>
                  </div>
                </div><!--text-->
              </div>
            </div><!--slide-->
            <!--next button-->
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <!--previous button-->
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div><!--carousel-->

          <!--Kalender-->
          <div class="col-md-3">
            <h5>Uppkommande aktiviteter</h5>
            <br>
            <ul class="list-group flex-column ml-auto">
              <li class="list-group-item item1">
                <div class="kalender col">
                  <span>16</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item2">
                <div class="kalender col">
                  <span>17</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item3">
                <div class="kalender col">
                  <span>18</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item4">
                <div class="kalender col">
                  <span>19</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item5">
                <div class="kalender col">
                  <span>20</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item6">
                <div class="kalender col">
                  <span>21</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
              <li class="list-group-item item7">
                <div class="kalender col">
                  <span>22</span>
                  <span>Maj</span>
                </div>
                <a class="nav-link text-dark aktivitet" href="#">Hello</a>
              </li>
            </ul>
          </div><!--Kalender-->
        </div><!--row-->

        <br><hr class="featurette-divider"><br>

        <div class="row justify-content-around featurette">
          <div class="col-md-5">
            https://varmland.skyttesport.se/Nyheter/SkytteportalensNyheter/?rss=True
            <br><br>
            This is hard
          </div>
          <div class="col-md-5">
            <div id="fb-root">
              <div class="fb-page" data-href="https://www.facebook.com/safflepk" data-tabs="timeline, events" data-width="500" data-height="550" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
                <blockquote cite="https://www.facebook.com/safflepk" class="fb-xfbml-parse-ignore">
                  <a href="https://www.facebook.com/safflepk">Säffle Pistolskytteklubb</a>
                </blockquote>
              </div>
            </div>
          </div>
        </div>
      </div><!--container-fluid-->
    </main>

    <footer class="footer fixed-bottom bg-light py-3">
      <div class="container">
        <div class="row">
          <h5 class="col-1">Kontakt:</h5>
          <p class="col-3">
            Tel: 072547440
          </p>
          <p class="col-3 bg-light">
            E-post: info@safflepk.se
          </p>
        </div>
      </div>
    </footer>
  </body>
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
  <script src="js/bootstrap/bootstrap.js"></script>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v7.0"></script>
  <!--<script src="js/own.js"></script>-->
</html>
