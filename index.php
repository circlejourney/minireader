<?php require "getstory.php"; ?>
<!doctype html>
<html>

<head>
    <title>
        MiniReader <?php if ($storyData) echo " - " . $storyData["title"]; ?>
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
        <?php if(!$storyID): ?>
            <div id='main'>
                <h1>MiniReader</h1>
                <h2>Choose a story to read</h2>
                <div class='story-index center'>
                    <?php foreach (preg_grep("/^[^\.]+$/", scandir("./")) as $path): ?>
                        <?php if (is_dir($path) && file_exists($path . "/story.json")): ?>
                            <?php $thisStoryData = jsonFileToArray($path . "/story.json"); ?>
                            <p>
                                <a href='?story=<?php echo $thisStoryData["storyID"] ?>'>
                                    <?php echo $thisStoryData["title"]?>
                                </a>
                                <?php if($thisStoryData["homepage"]): ?>
                                    &bull; <a href='<?php echo $thisStoryData["homepage"]?>'>More info</a>
                                <?php endif ?>
                            </p>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
        
        <div class="links">
            <a href="/read">All stories</a>
            &bull;
            <?php
            if($storyData["homepage"]) {
                echo "<a href='{$storyData["homepage"]}'>{$storyData["title"]} homepage</a> &bull;";
            }?>
            <a href="#" onclick="toggleDark()">Toggle black/white</a>
        </div>
        
        <div id="main">
            <div class="nav-bar">
                <?php if ($lastCInt > 1 && $storyData && $storyID && sizeof($chaptersReturned)): ?>
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
                        <?php foreach ($storyData["chapters"] as $k => $chapter): ?>
                            <?php
                                $chapterIndex = $k + 1;
                                $selected = $thisCInt === $chapterIndex ? "selected" : "";
                            ?>
                            <option value='<?php echo $chapterIndex ?>' <?php echo $selected = $thisCInt === $chapterIndex ? "selected" : "" ?>>
                                <?php echo $chapter["headline"]; ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                <?php endif ?>
            </div>

            <div class="story-container">
                <?php if($_GET["story"] && !$storyData): ?>
                    <div class='center'>Story not found!</div>
                <?php elseif(count($chaptersReturned)): ?>
                    <?php $chapter_exists = true; ?>
                    <h1><?php echo $storyData["title"] ?></h1>
                    <?php foreach ($chaptersReturned as $chapternumber => $content): ?>
                        <?php echo $content ?>
                        <hr>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class='center'>Chapter doesn't exist!</div>
                <?php endif?>
            </div>

            <?php if ($chapter_exists): ?>

            <hr>
            <div id="disqus_thread"></div>
            <script>
                 // The code below is an example. Get the embed code from Disqus' website and paste it here.
                var disqus_config = function() {
                    this.page.url = location.host + location.pathname;
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

            <?php endif ?>
            
        </div>

    </div>

    </div>
</body>
</html>