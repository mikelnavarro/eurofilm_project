<div id="rating-box">
    <span class="label">Tu valoración</span>
    <form id="form-rating">
        <div class="stars">
            <!-- radio ocultos + labels (estrellas) -->
            <input type="radio" name="rating" id="star1" value="10">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="9">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="8">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="7">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="6">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="5">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="4">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="3">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="2">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
            <input type="radio" name="rating" id="star1" value="1">
            <label for="star1">★</label> <input type="radio" name="rating" id="star1" value="1">
        </div>
        <select class="puntos">
            <option id="1" value="1">Muy Poco</option>
            <option id="2" value="2">Algo - 2</option>
            <option id="3" value="3">Insuficiente - 3</option>
            <option id="4" value="4">Insuficiente - 4</option>
            <option id="5" value="5">Suficiente - 5</option>
            <option id="6" value="6">Buena - 6</option>
            <option id="7" value="7">Notable - 7</option>
            <option id="8" value="8">Notable - 8</option>
            <option id="9" value="9">Me encanto - 9</option>
            <option id="10" value="10">Excelente - 10</option>

        </select>
        <textarea id="comment"></textarea>
        <button type="submit" id="btn-rate">Enviar reseña</button>
    </form>

    <p id="rating-msg"></p>
</div>