<?php

include("config.php");
include("classes/SiteResultsProvider.php");
include("classes/ImageResultsProvider.php");

if(isset($_GET['search']) && $_GET['search'] != ""){
    $term = $_GET['search'];
    $new_str = str_replace(' ', '', $term);
    if($new_str == ""){
        header("Location: index.php");
    }
    
    if(isset($_GET['type'])){
       $type = $_GET['type'];
    }
    else{
        $type = 'images';
    }
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
}
else{
    header("Location: index.php");
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style-m.css">
    <link rel="icon" href="assets/images/favwithcircle.png">

    <title><?php echo $term; ?> | Bhaarat</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body>
    <div class="wrapper searchWrapper">


        <div class="header">
            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/bhaaratLogo.png" alt="">
                    </a>
                </div>
                <div class="mainSection">
                    <div class="searchContainer">
                        <form action="search.php" method="GET" id="mainSearch">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <span class="searchBox1"><input type="text" name="search" id="search" class="searchBox" autocomplete="off" spellcheck="false" value='<?php echo $term; ?>'><label for="search" class="emptyFiller close tooltip" onclick="reset();"><span id="clear-img"><img src="assets/images/clear.png" alt=""><span class="tooltiptext">Clear</span></span></label>
                                <label for="search" class="emptyFiller speech tooltip" onclick="startDictation()"><img src="assets/images/mic.png" id="mic"><span class="tooltiptext">Search by voice</span></label><span class="emptyFiller voiceSearch tooltip">
                                    <img src="assets/images/searchButton.png" alt="" onclick="submit()">
                                </span></span>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tabsContainer">
                <ul class="tabsList">
                    <li class="<?php echo $type == 'all' ? 'activeTab' : '' ?>">
                        <a href='<?php echo "search.php?search=$term&type=all"; ?>' class="tabLink">
                            <img src="<?php echo $type == 'all' ? 'assets/images/searchTabActive.png' : 'assets/images/searchTab.png' ?>" alt="">All
                        </a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'activeTab' : '' ?>">
                        <a href='<?php echo "search.php?search=$term&type=images"; ?>' class="tabLink">
                            <img src="<?php echo $type == 'images' ? 'assets/images/imagesTabActive.png' : 'assets/images/imagesTab.png'; ?>" alt="">Images
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mainResultsSection">
            <?php
        if($type == 'all'){
            $resultsProvider = new SiteResultsProvider($con);
            $pageLimit = 15;
        }
        else{
              $resultsProvider = new ImageResultsProvider($con);
              $pageLimit = 30;  
        }
        $numResults = $resultsProvider->getNumResults($term);
        $random = (float)rand() / (float)getrandmax();
        $random = number_format($random, 2);
            if($numResults < 2 && $numResults != 0) $res = "$numResults result";
            else if($numResults == 0) $res = "$numResults results";
            else $res = "About $numResults results";
        echo "<p class='resultsCount'>$res found ($random seconds)</p>";
            echo $resultsProvider->getResultsHtml($page, $pageLimit, $term);
        ?>
        </div>


        <div class="paginationContainer">
            <div class="pageButtons">
               <?php
                
                $pagesToShow = 10;
                $numPages = ceil($numResults/$pageLimit);
                $pagesLeft = min($pagesToShow, $numPages);
                $currentPage = $page - floor($pagesToShow / 2);
                if($currentPage < 1 || $numPages < $pagesToShow){
                    $currentPage = 1;
                }
//                if($numPages > 10){
//                    if($currentPage + $pagesLeft > $numPages + 1){
//                    $currentPage = $numPages - $pagesLeft;
//                }
//                }
                
                if($numPages>1){
                    echo '<div class="pageNumberContainer">
                               
                                    <img src="assets/images/pageStart.png" alt="">
                              
                             </div>';
                    while($pagesLeft != 0 && $currentPage <= $numPages){
                    if($currentPage == $page){
                        echo '<div class="pageNumberContainer">
                              <img src="assets/images/pageSelected.png" alt="">
                              <span class="pageNumber">' . $currentPage . '</span>
                          </div>';
                    }
                    else{
                        echo '<div class="pageNumberContainer">
                                <a href="search.php?search=' . $term . '&type=' . $type . '&page=' . $currentPage . '">
                                    <img src="assets/images/page.png" alt="">
                                    <span class="pageNumber">' . $currentPage . '</span>
                                </a>
                             </div>';
                    }
                    $currentPage++;
                    $pagesLeft--;
                }
                    echo '<div class="pageNumberContainer">
                                <a href="#">
                                    <img src="assets/images/pageEnd.png" alt="">
                                </a>
                             </div>';
                }
                else{
                    echo '<div class="pageNumberContainer">
                             
                                    <img src="assets/images/bhaaratLogo.png" alt="">
                               
                             </div>';
                }
                
                ?>

            </div>

        </div>


    </div>
    <footer>
        <div class="makeInIndia stylish">
            <span id="userCountry" class="stylish"></span>&nbsp;|&nbsp;<span id="userCity" class="stylish"></span>&nbsp;|&nbsp;<span id="userRegion" class="stylish"></span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
