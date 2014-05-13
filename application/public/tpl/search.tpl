        <section id="easysearch">
        <form action="{APP_W}/agencia/buscar" method="post">
        <span>Busco</span>
	        <select name="servei">
	        	<option value="vol">un Vol</option>	
	        	<option value="hotel">un Hotel</option>	
	        	<option value="escapada">una Escapada</option>
	        </select>
        <span>A</span>
        <input type="text" name="ciutat" class="typ" placeholder="Barcelona,Par&iacute;s..." required></input>
        <span>Del</span>
	        <input type="text" id="data_inici" name="data_inici" class="dp" value="" required></input>
	     <span>Al </span>
	        <input type="text" id="data_fi" name="data_fi" class="dp" value="" required></input>
	     <button value="trobar">Trobar!</button>
        </form>
        </section>
        <div id="noticies">
        <h3>Not&iacute;cies Turt&iacute;stiques</h3>
        <div id="rss">Carregant...</div>
        </div>
    </body>
</html>