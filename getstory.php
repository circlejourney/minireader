<?php
    error_reporting(0);
    $sep = DIRECTORY_SEPARATOR;
    
    function jsonFileToArray($filepath){
        return json_decode( file_get_contents( $filepath ), true );
    }
    
    $storyID = isset($_GET["story"]) ? $_GET["story"] : false;
    
    if($storyData = jsonFileToArray( $storyID . $sep . "story.json" )) {
        
        $thisCStr = $_GET["c"] ? $_GET["c"] : "1";
        if(!$storyData['chapterNomenclature']) $storyData['chapterNomenclature'] = "Chapter";
        $storyIsEnumerated = ($storyData["enumerated"] || !isset($storyData["enumerated"]));
        $lastCInt = count( $storyData["chapters"] );
        $chaptersReturned = array();
        
        $chapterEnumerator = 1;
        foreach($storyData["chapters"] as $k => $chapter) {
            $chapterIsEnumerated = (!isset($chapter["enumerated"]) || $chapter["enumerated"]);
            if($storyIsEnumerated && $chapterIsEnumerated) {
                $storyData["chapters"][$k]["headline"] = $storyData['chapterNomenclature'] . " " . $chapterEnumerator . ": " . $chapter["title"];
                $chapterEnumerator++;
            } else $storyData["chapters"][$k]["headline"] = $chapter["title"];
        }
        
        if($thisCStr == "all") {
            foreach($storyData["chapters"] as $k => $chapter) {
                $chaptersReturned[$k] = 
                    ($chapter["headline"] ? "<h2>{$chapter["headline"]}</h2>" : "").
                    file_get_contents( $storyID . $sep . ($k+1) . ".html" );
            }
        } else {
            $thisCInt = intval($thisCStr);
            $prevCInt = $thisCInt - 1;
            $nextCInt = $thisCInt + 1;
            
            if($thisCHTML = file_get_contents($storyID . $sep . $thisCInt . ".html")) {
                $chaptersReturned[$thisCInt] = "<h2>{$storyData["chapters"][$thisCInt-1]["headline"]}</h2>" . $thisCHTML;
            }

            $chapterEnumerator = 1;
        }
        
    }
?>