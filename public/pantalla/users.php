<link rel="stylesheet" href="../css/users.css">
<?php include '../inc/header.php'; ?>
<div class="users-search">

    <h2>Buscar usuarios</h2>

    <input 
        type="text" 
        id="search-user"
        placeholder="Buscar usuario..."
    >

    <select id="filter-country">
        <option value="">Todos los países</option>
        <option value="Spain">España</option>
        <option value="France">Francia</option>
        <option value="Italy">Italia</option>
    </select>

    <div id="users-container"></div>
</div>
<?php include '../inc/footer.php'; ?>
<script defer src="../js/users.js" type="module"></script>