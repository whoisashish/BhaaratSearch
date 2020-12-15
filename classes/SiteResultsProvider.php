<?php
class SiteResultsProvider {

	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	public function getNumResults($term) {

		$query = $this->con->prepare("SELECT COUNT(*) as total 
										 FROM sites WHERE title LIKE :term 
										 OR url LIKE :term 
										 OR keywords LIKE :term 
										 OR description LIKE :term");

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
										 FROM sites WHERE title LIKE :term 
										 OR url LIKE :term 
										 OR keywords LIKE :term 
										 OR description LIKE :term
										 ORDER BY clicks DESC
										 LIMIT :fromLimit, :pageSize");

        $term = strtr($term, array('"' => '', "'" => ''));
		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
		$query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
		$query->execute();


		$resultsHtml = "<div class='siteResults'>";


		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
			$url = $row["url"];
			$title = $row["title"];
			$description = $row["description"];

			$title = $this->trimField($title, 55);
			$description = $this->trimField($description, 230);
			$urlTrimmed = $this->trimField($url, 45);
			
			$resultsHtml .= "<div class='resultContainer'>

                                <a class='result' href='$url' data-linkId='$id'>
                                    <h3 class='title'>
                                        <span class='url'>$urlTrimmed</span><br>
                                            <span class='titleText'>$title</span>
                                    </h3>
                                </a>
								<span class='description'>$description</span>

							</div>";


		}


		$resultsHtml .= "</div>";

		return $resultsHtml;
	}

	private function trimField($string, $characterLimit) {

		$dots = strlen($string) > $characterLimit ? "..." : "";
		return substr($string, 0, $characterLimit) . $dots;
	}




}
?>