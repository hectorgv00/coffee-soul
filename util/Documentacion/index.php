
<!doctype html>
<html>
	<head>
		<style>
			/* General styling */
			@page {
				margin: 1in;
			}

			body {
				font-family: 'Georgia', serif;
				line-height: 1.5;
				color: #333;
				margin: 0;
				padding: 0;
				background: #fff;
			}

			h1, h2, h3 {
				font-family: 'Times New Roman', serif;
				text-align: center;
				margin: 20px 0;
				color: #111;
			}

			h1 {
				font-size: 2.5em;
			}

			h2 {
				font-size: 1.8em;
			}

			h3 {
				font-size: 1.5em;
			}

			p {
				font-size: 12pt;
				margin: 10px 0;
				text-align: justify;
				text-indent: 1em;
			}

			ul {
				list-style-type: none;
				padding: 0;
				margin: 0;
			}

			li {
				margin: 5px 0;
			}

			pre {
				font-family: 'Courier New', monospace;
				font-size: 11pt;
				background: #f9f9f9;
				border: 1px solid #ddd;
				border-radius: 5px;
				padding: 10px;
				overflow-x: auto;
				margin-bottom: 10px;
			}

			/* Book-like styling */
			.container {
				width: 6in;
				margin: auto;
				padding: 1in;
				background: #fafafa;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			}

			.page-break {
				page-break-before: always;
			}

			/* Table of contents styling */
			ul.toc {
				padding: 0;
				margin: 0 0 20px 0;
				list-style-type:none;
			}

			ul.toc li {
				font-size: 12pt;
				margin: 10px 0;
				list-style-type:none;
				display:block;
				padding-left:20px;
			}

			

			/* Special styling for inline notes and comments */
			.comment {
				font-style: italic;
				color: #555;
				background: #f1f1f1;
				border-left: 4px solid #ccc;
				padding: 5px 10px;
				margin: 10px 0;
			}

			/* Footer styling */
			footer {
				text-align: center;
				font-size: 10pt;
				color: #888;
				margin-top: 20px;
				border-top: 1px solid #eee;
				padding-top: 10px;
			}
			@media print{
				@page {
					margin: 1cm;
				}
				body {
					font-family: 'Georgia', serif;
					line-height: 1.5;
					color: #333;
					margin: 0;
					padding: 0;
					background: white;
				}
				.container {
				width: 100%;
				margin: auto;
				padding:0px;
				background: white;
				box-shadow: none;
			}
			}
		</style>
	</head>
	<body>
	<div class="container">
	<h1>Manual del desarrollador: <br>
	Proyecto: OldLace</h1>
<?php


function processFolderForHtml($source,$indice) {
    $html = "<ul class='toc'>\n";
    $items = scandir($source);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $sourcePath = $source . DIRECTORY_SEPARATOR . $item;
        if (is_dir($sourcePath)) {
            $folderIcon = strpos($item, '.') === false ? "📁" : "📄"; // Folder or File Icon
            $html .= "<li><pre>$folderIcon $item</pre>\n";

            $html .= processFolderForHtml($sourcePath,$indice);
            $html .= "</li>\n";
        } else if (is_file($sourcePath) && pathinfo($item, PATHINFO_EXTENSION) === 'txt') {
        	if($indice == "no"){
            $contents = htmlspecialchars(file_get_contents($sourcePath));
            $html .= "<li><p>".nl2br($contents)."</p></li>\n";
         }
         
        }
        
    }
    $html .= "</ul>\n";
    return $html;
}


		$sourceFolder = 'docs/admin'; // Replace with the path to your target folder
	
		?>
		<h2>Tabla de contenido</h2>
		<?php
		echo processFolderForHtml($sourceFolder,"si");
		?>
		<div class="page-break"></div>
		<h2>Contenido de la documentación</h2>
		<?php
		echo processFolderForHtml($sourceFolder,"no");
		?>
		</div>
		<footer>
			<p>&copy; 2024 Manual del desarrollador. Todos los derechos reservados.</p>
		</footer>
	</body>
</html>
