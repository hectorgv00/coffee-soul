<?php
class AtributoEstilo
{
    public $atributo;
    public $valor;

    public function __construct($nuevoatributo, $nuevovalor)
    {
        $this->atributo = $nuevoatributo;
        $this->valor = $nuevovalor;
    }
}

class Estilo
{
    public $estilo;

    public function __construct($estilos)
    {
        $this->estilo = $estilos;
    }
}

abstract class Bloque
{
    protected $titulo;
    protected $subtitulo;
    protected $texto;
    protected $imagen;
    protected $imagenfondo;
    protected $estilo;
    protected $ratings;

    public function __construct(
        $nuevotitulo,
        $nuevosubtitulo = "",
        $nuevotexto = "",
        $nuevaimagen = "",
        $nuevaimagenfondo = "",
        $nuevoestilo = [],
        $ratings = ""
    ) {
        $this->titulo = $nuevotitulo;
        $this->subtitulo = $nuevosubtitulo;
        $this->texto = $nuevotexto;
        $this->imagen = $nuevaimagen;
        $this->imagenfondo = $nuevaimagenfondo;
        $this->estilo = $nuevoestilo;
        $this->ratings = $ratings;
    }

    public function buildStyles()
    {
        $cadena = "";
        if (!is_array($this->estilo)) return "";
        foreach ($this->estilo as $clave => $valor) {
            $cadena .= "$clave:$valor;";
        }
        return $cadena;
    }
}

class BloqueCompleto extends Bloque
{
    public function getBloque()
    {
        $cadena = $this->buildStyles();
        $fondo = $this->imagenfondo;
        $imagenHtml = $this->imagen ? "<img src='{$this->imagen}' alt='Imagen'>" : "";

        //echo  $fondo;
        return "
        <div class='bloque completo' style='$cadena;background:url(\"/static/{$fondo}\");background-size:cover;background-position:center center;'>
                <h3>{$this->titulo}</h3>
                <h4>{$this->subtitulo}</h4>
                <p>{$this->texto}</p>
                $imagenHtml
            </div>
        ";
    }
}

class BloqueCaja extends Bloque
{
    public function getBloque()
    {
        $cadena = $this->buildStyles();
        $imagenHtml = $this->imagen ? "<img src='/static/{$this->imagen}' alt='Imagen'>" : "";

        return "
            <div class='bloque caja' style='$cadena'>
                <h3>{$this->titulo}</h3>
                <h4>{$this->subtitulo}</h4>
                <p>{$this->texto}</p>
                $imagenHtml
            </div>
        ";
    }
}

class BloqueCajaDosColumnas extends Bloque
{
    public function getBloque()
    {
        // var_dump($this);
        $cadena = $this->buildStyles();
        echo $this->texto;
        $textojson = json_decode($this->texto);
        $imagenHtml = $this->imagen ? "<img src='{$this->imagen}' alt='Imagen'>" : "";
        return "
        <div class='bloque caja' style='$cadena'>
            <h3>{$this->titulo}</h3>
            <h4>{$this->subtitulo}</h4>
            <div class='doscolumnastexto'>
                <p>{$textojson->columna1}</p>
                <p>{$textojson->columna2}</p>
                $imagenHtml
            </div>
        </div>
    ";
    }
}

class BloqueCajaPasaFotos extends Bloque
{
    public function getBloque()
    {
        $cadena = $this->buildStyles();
        $cadena = "";
        $textojson = json_decode($this->texto);
        $cadena .= "
            <div class='bloque caja cajapasafotos' style='$cadena'>
                <div class='contenedorpasafotos' style='width:" . (count($textojson) * 900 + 100) . "px;'>";


        foreach ($textojson as $clave => $valor) {
            $cadena .= "
        				<article style='background:url(\"../static/" . $valor->imagen . "\");background-size:cover;'>
                		<div class='texto'>
                		<h3>" . $valor->titulo . "</h3>
                		<p>" . $valor->texto . "</p>
                		</div>
                	</article>
        ";
        }


        $cadena .= "
                </div>
                <div class='controlador'>";
        for ($i = 1; $i <= count($textojson); $i++) {
            $cadena .= "<button value='" . $i . "'>" . $i . "</button>";
        }


        $cadena .= "    	
                </div>
            </div>
        ";
        return $cadena;
    }
}

class BloqueCajaYoutube extends Bloque
{
    public function getBloque($video)
    {
        $cadena = $this->buildStyles();
        return '
            <div class="bloque caja" style="' . $cadena . '">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video . '?si=iJbR_XbLmg8-dssi" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        ';
    }
}

class BloqueCajaPropiedades extends Bloque
{
    private function generateRatings(int $ratingNumber)
    {
        // Calculamos la diferencia entre el número de estrellas y el máximo
        $difference = 5 - $ratingNumber;

        // Inicializamos la cadena
        $cadena = "";
        // Por cada estrella, añadimos una estrella llena
        for ($i = 0; $i < $ratingNumber; $i++) {
            $cadena .= "<span class='ratingStar'>★</span>";
        }

        // Si hay diferencia, añadimos estrellas vacías
        if ($difference) {
            // Por cada estrella de diferencia, añadimos una estrella vacía
            for ($i = 0; $i < $difference; $i++) {
                $cadena .= "<span class='ratingStar'>☆</span>";
            }
        }

        // Devolvemos la cadena
        return $cadena;
    }

    public function getBloque()
    {
        $styles = $this->buildStyles(); // Construye los estilos CSS para el bloque
        $cadena = "";
        // Decodifica el JSON a un array de objetos
        $textojson = json_decode($this->ratings);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Si hay un error en la decodificación del JSON, muestra un mensaje de error
            echo "JSON decode error: " . json_last_error_msg();
            return "";
        }
        // Inicia la cadena HTML para el bloque con los estilos aplicados
        $cadena .= "
            <div class='bloque caja' style='$styles'>
            ";
        // Recorre cada objeto en el array decodificado
        foreach ($textojson as $clave => $valor) {
            // Añade una línea de propiedad para cada calificación
            $cadena .= "
            <div class='propiedadLine'>
    
                <div class='propiedad'>
                    <p>" . htmlspecialchars($valor->name, ENT_QUOTES, 'UTF-8') . "</p> 
                    <span class='ratingBox' >"
                . $this->generateRatings(intval(htmlspecialchars($valor->value, ENT_QUOTES, 'UTF-8'))) . "</span>
                </div>
                
                </div>
            
        ";
        }
        // Cierra el div del bloque
        $cadena .= "    	
                </div>
    
        ";
        return $cadena; // Devuelve la cadena HTML generada
    }
}

class BloqueMosaico extends Bloque
{
    private $mosaicos;

    public function __construct(
        $nuevotitulo,
        $nuevosubtitulo = "",
        $nuevotexto = "",
        $nuevaimagen = "",
        $nuevaimagenfondo = "",
        $mosaicos = []
    ) {
        parent::__construct(
            $nuevotitulo,
            $nuevosubtitulo,
            $nuevotexto,
            $nuevaimagen,
            $nuevaimagenfondo
        );
        $this->mosaicos = $mosaicos;
    }

    public function getBloque()
    {
        $cadena = $this->buildStyles();
        $contenido = "
            <div class='bloque mosaico' style='$cadena'>
                <h3>{$this->titulo}</h3>
                <h4>{$this->subtitulo}</h4>
                
					<div class='rejilla'>
                ";

        $this->mosaicos = json_decode($this->texto);

        foreach ($this->mosaicos as $clave => $valor) {
            $contenido .= "
                		<div class='celda'>
                		{$valor}
                		</div>
                	";
        }


        $contenido .= "
        		</div>
            </div>
        ";

        return $contenido;
    }
}
