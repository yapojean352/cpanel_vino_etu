<div class="ajouterCellier">

    <div class="nouveauCellier" vertical layout>
        
    <h3>Souhaitez-vous ajouter un nouveau cellier ?</h3>
    <form action="" method="post">
        <label for="cellier__nom"></label>
        <label for="cellier__nom">Nom du cellier :</label>
        <input type="text" name='cellier__nom'placeholder="Nom du cellier">
        <button id="submit" type="submit" value="submit">Valider</button>
    </form>
    <span id="confirmation">
     <?php echo $data['retourAjouter']?>
    </span>
    </div>
</div>
