<?php require "getstory.php"; ?>
<!doctype html>
<html>

<head>
    <title>
        MiniReader <?php if ($storyID !== "") echo " || " . $storyData["title"]; ?>
    </title>
    <meta name="description" content="A reading app for novels, novellas and short stories.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://fonts.googleapis.com/css?family=Old+Standard+TT' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <script src="https://circlejourney.net/resources/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="style.css">

    <script>
        const storyID = location.search.match(/\&?story=([\w-]+)/)[1];
        const thisCStr = (location.search.match(/\&?c=([0-9]+)/) || [, "1"])[1];
        let thisCInt = parseInt(thisCStr);

        $(document).ready(function() {
            localStorage.darkmode = localStorage.darkmode || "false";
            if (localStorage.darkmode == "true") toggleDark();
            $(".nav-bar").clone().insertAfter(".story-container");
        });

        function toggleDark() {
            $("#container").toggleClass("dark");
            localStorage.darkmode = $("#container").hasClass("dark");
        }

        function gotoURL(caller) {
            var newpage;
            if (caller.value != "" + thisCInt) {
                location.search = "?story=" + storyID + "&c=" + caller.value;
            }
        }
    </script>
</head>

<body>
    <div id="container">
        <?php

        if (!$storyID) {
            echo "<div id='main'>";
            echo "<h1>MiniReader</h1>";
            echo "<h2>Choose a story to read</h2>";
            echo "<div class='story-index center'>";
            foreach (preg_grep("/^[^\.]+$/", scandir("./")) as $path) {
                if (is_dir($path) && file_exists($path . "/story.json")) {
                    $thisStoryData = jsonFileToArray($path . "/story.json");
                    echo "<p>";
                    echo "<a href='?story={$thisStoryData["storyID"]}'>{$thisStoryData["title"]}</a>";
                    if($thisStoryData["homepage"]) echo "|| <a href='{$thisStoryData["homepage"]}'>More info</a>";
                    echo "</p>";
                };
            };
            echo "</div>";
            echo "</div>";
            ob_start();
        } else if (!$storyData) {
            echo "<div class='center'>Story not found!</div>";
            ob_start();
        }

        ?>

        <div class="links">
            <a href="/read">All stories</a>
            |
            <?php
            if($storyData["homepage"]) {
                echo "<a href='{$storyData["homepage"]}'>{$storyData["title"]} homepage</a> |";
            }?>
            <a href="#" onclick="toggleDark()">Toggle black/white</a>
        </div>

        <?php if (!$storyData || !$storyID) ob_end_clean(); ?>

        <div id="main">
            <div class="nav-bar">
                <?php
                if ($lastCInt <= 1 || !$storyData || !$storyID) {
                    ob_start();
                }
                ?>
                <a class="nav-button first" <?php if ($thisCInt > 1) {
                                                echo 'href="?story=' . $storyID . '&c=1"';
                                            } ?>>
                    First
                </a>
                <a class="nav-button prev" <?php if ($prevCInt > 0) {
                                                echo 'href="?story=' . $storyID . '&c=' . strval($prevCInt) . '"';
                                            } ?>>
                    Previous
                </a>
                <a class="nav-button next" <?php if ($nextCInt <= $lastCInt) {
                                                echo 'href="?story=' . $storyID . '&c=' . strval($nextCInt) . '"';
                                            } ?>>
                    Next
                </a>
                <a class="nav-button last" <?php if ($lastCInt != $thisCInt) {
                                                echo 'href="?story=' . $storyID . '&c=' . strval($lastCInt) . '"';
                                            } ?>>Last</a>
                <a class="nav-button" href="?story=<?php echo $storyID; ?>&c=all">All chapters</a>
                Go to:
                <select class="chapselect" onchange="gotoURL(this)">
                    <?php
                    foreach ($storyData["chapters"] as $k => $chapter) {
                        $chapterIndex = $k + 1;
                        $selected = $thisCInt === $chapterIndex ? "selected" : "";
                        echo "<option value='{$chapterIndex}' {$selected}>";
                        echo $chapter["headline"];
                        echo "</option>";
                    }
                    ?>
                </select>

                <?php if ($lastCInt <= 1 || !$storyData || !$storyID)  ob_end_clean(); ?>
            </div>

            <div class="story-container">
                <?php
                if (count($chaptersReturned)) {
                    $chapter_exists = true;
                    echo "<h1>{$storyData["title"]}</h1>";
                    foreach ($chaptersReturned as $chapternumber => $content) {
                        echo $content;
                        echo "<hr>";
                    }
                } else {
                    echo "Chapter doesn't exist!";
                }
                ?>
            </div>

            <?php if (!$chapter_exists) ob_start(); ?>

            <hr>

            <div id="disqus_thread"></div>

            <script>
                var disqus_config = function() {
                    this.page.url = location.href;
                    this.page.identifier = "<?php echo $_GET["story"] . $_GET["c"]; ?>"
                };

                (function() {
                    var d = document,
                        s = d.createElement('script');
                    s.src = '#DISQUS-SOURCE';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <hr>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            <script id="dsq-count-scr" src="#DISQUS-SCRIPT" async></script>

            <?php if (!$chapter_exists) ob_end_clean(); ?>
        </div>
        <?php if (!$storyData) ob_end_clean(); ?>
    </div>

    </div>
</body>
</html>