<?php

$accepted = $this->getVar("accepted");

if ($accepted){
    ?>
    <h2>
        L'utilisateur a bien été ajouté au groupe des nouveaux contributeurs.
    </h2>
    <?php
} else {
    ?>
    <h2>
        La candidature de l'utilisateur a bien été refusée.
</h2>
    
    <?php
}?>