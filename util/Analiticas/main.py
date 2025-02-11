from JVAnaliticas import *
        
sesion = JVAnaliticas()
sesion.graficaTarta(
    """
    SELECT 
    COUNT(country) AS numero,
    country as valor
    FROM logs
    WHERE country != ""
    GROUP BY country
    ORDER BY numero DESC
    LIMIT 20;
    """, "paises.jpg","Paises visitantes"
)

sesion.graficaTarta(
    """
    SELECT 
    COUNT(browser_language) AS numero,
    browser_language as valor
    FROM logs
    WHERE browser_language != ""
    GROUP BY browser_language
    ORDER BY numero DESC
    LIMIT 20;
    """, "idiomas.jpg","Idiomas"
)
sesion.graficaBarras(
    """
    SELECT 
    COUNT(year) AS numero,
    year AS valor
    FROM logs
    GROUP BY year
    """, "visitasporaño.jpg","Visitas por año"
)

sesion.graficaBarras(
    """
    SELECT 
    COUNT(month) AS numero,
    month AS valor
    FROM logs
    GROUP BY month
    """, "visitaspormes.jpg","Visitas por mes"
)
sesion.graficaBarras(
    """
    SELECT 
    COUNT(day) AS numero,
    day AS valor
    FROM logs
    GROUP BY day
    """, "visitaspordia.jpg","Visitas por día"
)
sesion.graficaBarras(
    """
    SELECT 
    COUNT(hour) AS numero,
    hour AS valor
    FROM logs
    GROUP BY hour
    """, "visitasporhora.jpg","Visitas por hora"
)
sesion.graficaTarta(
    """
    SELECT
    CASE
        WHEN user_agent LIKE '%Windows%' THEN 'Windows'
        WHEN user_agent LIKE '%Macintosh%' THEN 'Macintosh'
        WHEN user_agent LIKE '%Linux%' THEN 'Linux'
        WHEN user_agent LIKE '%Android%' THEN 'Android'
        WHEN user_agent LIKE '%iPhone%' THEN 'iPhone'
        WHEN user_agent LIKE '%iPad%' THEN 'iPad'
        ELSE 'Other'
    END AS valor,
    COUNT(*) AS numero
    FROM logs
    GROUP BY valor
    ORDER BY numero DESC;
    """, "sistemasoperativos.jpg","Sistemas operativos"
)

sesion.graficaTarta(
    """
    SELECT
    CASE
        WHEN user_agent LIKE '%Chrome%' THEN 'Chrome'
        WHEN user_agent LIKE '%Safari%' AND user_agent NOT LIKE '%Chrome%' THEN 'Safari'
        WHEN user_agent LIKE '%Firefox%' THEN 'Firefox'
        WHEN user_agent LIKE '%Edge%' THEN 'Edge'
        WHEN user_agent LIKE '%Opera%' OR user_agent LIKE '%OPR%' THEN 'Opera'
        WHEN user_agent LIKE '%MSIE%' OR user_agent LIKE '%Trident%' THEN 'Internet Explorer'
        ELSE 'Other'
    END AS valor,
    COUNT(*) AS numero
    FROM logs
    GROUP BY valor
    ORDER BY numero DESC;
    """, "navegadores.jpg","Navegadores web"
)

sesion.graficaTarta(
    """
    SELECT 'bots' AS valor, COUNT(*) AS numero
    FROM logs
    WHERE user_agent LIKE '%bot%'
    UNION ALL
    SELECT 'no bots' AS category, COUNT(*) AS numero
    FROM logs
    WHERE user_agent NOT LIKE '%bot%';
    """, "bots.jpg","Bots"
)

sesion.graficaTarta(
    """
    SELECT 
        CONCAT(screenx, 'x', screeny) AS valor,
        COUNT(*) AS numero
    FROM 
        logs
    GROUP BY 
        screenx, screeny
    ORDER BY 
        numero DESC
    LIMIT 10;
    
    """, "pantallas.jpg","Resolucion de pantalla"
)
sesion.graficaBarrasHorizontales(
    """
    SELECT 
        page_visited as valor,
        count(page_visited) as numero
        FROM logs
        GROUP BY page_visited
        ORDER BY numero DESC
        LIMIT 10
    
    """, "paginasvisitadas.jpg","Páginas visitadas"
)
