<html>
    <head>
        <title>{{message}}</title>
    </head>
    <body>
    	<p>{{message}}</p>
        {{>foo}}
        <?php
        echo '<p>Our default homepage!</p>';
        ?>
        {{>bar}}
        
    </body>
</html>