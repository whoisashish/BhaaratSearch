<?php
class ImageResultsProvider {

	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	public function getNumResults($term) {

		$query = $this->con->prepare("SELECT COUNT(*) as total 
										 FROM images WHERE ( title LIKE :term 
										 OR alt LIKE :term ) AND broken = 0");

        $term = strtr($term, array('"' => '', "'" => ''));
		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];

	}

	public function getResultsHtml($page, $pageSize, $term) {

		$fromLimit = ($page - 1) * $pageSize;

		$query = $this->con->prepare("SELECT * 
										 FROM images WHERE ( title LIKE :term 
										 OR alt LIKE :term ) AND broken = 0
										 ORDER BY clicks DESC
										 LIMIT :fromLimit, :pageSize");

        $term = strtr($term, array('"' => '', "'" => ''));
		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
		$query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
		$query->execute();


		$resultsHtml = "<div class='imageResults'><div class='grid-sizer'></div>";

        $count = 0;
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $count++;
			$id = $row["id"];
			$imageUrl = $row["imageUrl"];
			$siteUrl = $row["siteUrl"];
			$title = $row["title"];
			$alt = $row["alt"];
            $imageUrl = str_replace("\"","'", $imageUrl);
            
            if($title){
                $displayText = $title;
            }
            else if($alt){
                $displayText = $alt;
            }
            else{
                $displayText = $imageUrl;
            }
			
			$resultsHtml .= "<div class='gridItem image$count'>

                                <a class='result' href='$siteUrl' data-linkId='$id'>
                                    
                                    <script>
                                            var timer;
                                            function loadImage(src, className) {

                                                var image = $(\"<img>\");

                                                image.on(\"load\", function () {
                                                    $(\".\" + className + \" a\").append(image);
                                                    clearTimeout(timer);
                                                    timer = setTimeout(function(){
                                                        $(\".imageResults\").masonry();
                                                    }, 300);

                                                });

                                                image.on(\"error\", function () {

                                                });

                                                image.attr(\"src\", src);

                                            }
									$(document).ready(function() {
										loadImage(\"$imageUrl\", \"image$count\");
									});
									</script>
                                    
                                    <span class='details'>$displayText</span>
                                </a>

							</div>";


		}


		$resultsHtml .= "</div>";

		return $resultsHtml;
	}


}
?>




<!--
$term = strtr($term, array('"' => '', "'" => ''));
$resultsHtml .= "<div class='resultContainer'>

                                <a class='result' href='$url' data-linkId='$id'>
                                    <h3 class='title'>
                                        <span class='url'>$url</span><br>
                                            <span class='titleText'>$title</span>
                                    </h3>
                                </a>
								<span class='description'>$description</span>

							</div>";
							-->
