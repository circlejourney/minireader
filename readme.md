# MiniReader: A minimal(ish) templater for self-publishing literature.
A templater for self-publishing a collection of stories. It generates a listing of all stories in the gallery that can be viewed at the reader's homepage. It supports single-chaptered and multi-chaptered works, as well as alternate chapter naming (e.g. naming chapters as "Episodes").

The entire MiniReader directory can be uploaded as-is to a web host and it should run out of the box. Two examples are included to demonstrate folder and `story.json` structure. A live example can be viewed at [circlejourney.net/read](https://circlejourney.net/read).

# Quick start
To add a story to the collection, add a sub-folder with a unique name with no spaces, then upload the contents of individual chapters (without titles) as numbered HTML files, e.g. `1.html`, `2.html`, `3.html`... Finally, upload a `story.json` file (details on how to format it are below).

To view a preview, install [XAMPP](https://www.apachefriends.org/) and add the `php` sub-directory from the install folder to your PATH variable. After that, start a PHP server by running the command `php -S localhost:8000` inside the MiniReader directory, and you can view the MiniReader at localhost:8000.

# Files
`index.php` is the reader. Add a search query i.e. `?story=<story-ID>&c=<chapter-number>` to the URL to fetch specific stories and chapters.

`getstory.php` is a helper that scans directories, filters for selected chapters, and retrieves story/chapter content and metadata.

`style.css` contains all the styling for the story. It includes a `.dark` class that is responsible for setting all dark mode styling.

# story.json
`story.json` contains all metadata for the story in that folder.
- `"title": "Your Story Title"`: The display title that is shown in the HTML page title and the story header (required).
- `"storyID": "your-story-id"`: Unique story ID, containing letters, numbers, hyphens and underscores. This should be the same as the folder name (required).
- `"homepage": "https://story.website.com/"`: A URL for readers to find out more about the story (optional).
- `"enumerated": true|false`: Whether the chapters should be displayed with numbers and nomenclature in their titles (optional). Defaults to true if unspecified. If set to false, all chapters titles will be displayed without the "Chapter X:" label in front.
- `"chapterNomenclature": "Chapter"`: The terminology used for chapters. Can be useful if you want the chapters to be called "episodes", for example. Defaults to "Chapter" if unspecified.
- `"chapters": [ ... ]`: Array of chapter objects. The web app can only "see" chapters that are added here, so HTML files that don't have a corresponding chapter object added here cannot be accessed. Chapter object properties:
  - `"title": "Chapter Title"`: Title of the chapter
  - `"enumerated": true|false`: Whether the chapter should be displayed with a number and nomenclature in its title (optional). Defaults to true. If set to false, the chapter title will be displayed without the "Chapter X:" label in front.