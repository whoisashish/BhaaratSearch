<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/favwithcircle.png">

    <title>Bhaarat</title>
</head>

<body>
    <header id="indexHeader">
        <a href="index.php">All Search</a>
    </header>
    <div class="wrapper indexPage">
        <div class="mainSection">
            <div class="logoContainer">
                <img src="assets/images/bhaaratImagesLogo.png" alt="">
            </div>
            <div class="searchContainer">
                <form action="searchI.php" method="GET" id="mainSearch">
                    <span class="searchBox1"><label class="emptyFiller search" for="search"><img src="assets/images/search.png" alt=""></label><input type="text" name="search" id="search" class="searchBox" autocomplete="off" spellcheck="false"><label for="search" class="emptyFiller close tooltip" onclick="reset();"><span id="clear-img"><img src="assets/images/clear.png" alt=""><span class="tooltiptext">Clear</span></span></label>
                        <label for="search" class="emptyFiller speech tooltip" onclick="startDictation()"><img src="assets/images/mic.png" id="mic"><span class="tooltiptext">Search by voice</span></label></span>
                    <input type="submit" value="Search" class="searchButton">
                </form>
            </div>
        </div>
    </div>
    <footer>
        <div class="makeInIndia stylish">
            <span id="userCountry"></span>&nbsp;|&nbsp;<span id="userCity"></span>&nbsp;|&nbsp;<span id="userRegion"></span>
        </div>
        <div class="makeInIndia">
            <img src="assets/images/makeInIndia.png" alt="">
        </div>
    </footer>


    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-body">
                <div class="soundAnimation">&nbsp;</div><img src="assets/images/activeMic.png" alt="" class="micSearch">
            </div>
        </div>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
