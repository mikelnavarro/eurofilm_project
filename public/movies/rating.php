<div id="rating-box">
    <span class="label">Tu valoración</span>
    <form id="form-rating" method="POST">
        <div class="form-group">
            <input type="text" name="review-title" id="review-title" placeholder="Escribe titulo de la reseña">
        </div>
        <div class="form-group">
            <select name="visibility" class="visibility" id="visibility">
                <option id="private" value="Privada">PRIVADA</option>
                <option id="public" value="Publica">PÚBLICA</option>
            </select>
        </div>
        <div class="form-group">
            <select name="rating" class="puntos">
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
        </div>
        <div class="form-group checkbox-group">
            <input type="checkbox" name="spoiler" id="spoiler" value="1">
            <label for="spoiler">Contiene Spoiler</label>
        </div>
        <textarea name="comment" placeholder="Escribe"></textarea> 
        <button type="submit" id="btn-rate">Enviar reseña</button>
    </form>

    <p id="rating-msg"></p>
</div>
<style>
:root {
    --primary: #4f46e5;
    --primary-hover: #4338ca;
    --bg-card: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --border: #d1d5db;
    --disabled-bg: #f3f4f6;
}

/* Rating RESEÑAS */
#rating-box {
    max-width: 500px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: 12px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', system-ui, sans-serif;
}


.label-main {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-group {
    margin-bottom: 1.2rem;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.4rem;
}

/* Estilo para Inputs, Selects y Textarea */
input[type="text"], select, textarea {
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s ease;
    background-color: #fff;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}



.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.checkbox-group {
    flex-direction: row;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}
button {
    flex: 1;
    padding: 0.8rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.1s, background 0.2s;
    border: none;
}


button:active { transform: scale(0.98); }

/* Responsivo */
@media (max-width: 480px) {
    .form-row { grid-template-columns: 1fr; }
    #rating-box { margin: 1rem; padding: 1.5rem; }
}
</style>