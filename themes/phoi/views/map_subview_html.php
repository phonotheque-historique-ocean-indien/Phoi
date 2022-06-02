<?php
$pays = $this->getVar("pays");
$country_code = $this->getVar("country_code");
$nb_collectages = $this->getVar("nb_collectages");
$nb_phonogrammes = $this->getVar("nb_phonogrammes");
$nb_inds = $this->getVar("nb_inds");
$nb_groups = $this->getVar("nb_groups");
$nb_representations = $this->getVar("nb_representations");
$description = "<p>".implode("</p><p>", explode("\n", $this->getVar("description")))."</p>";
$nb_autres = $this->getVar("nb_autres");
$nb_oeuvres = $this->getVar("nb_oeuvres");

?>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <?= $pays; ?>
            </p>
        </header>
        <div class="card-content">
            <div class="content">
                <ul>
                    <li><a href="/index.php/Phoi/Phonogrammes/Search/country/<?= $country_code ?>">Phonogrammes (<?= $nb_phonogrammes; ?>)</a></li>
                    <li><a href="/index.php/Phoi/Collectages/Search/country/<?= $country_code ?>">Collectages (<?= $nb_collectages; ?>)</a></li>
                    <li><a href="/index.php/Phoi/Partitions/Search/country/<?= $country_code ?>">Partitions (<?= $nb_autres ?>)</a></li>
                    <li><a href="/index.php/Phoi/Oeuvres/Search/country/<?= $country_code ?>">Créations musicales (<?= $nb_oeuvres ?>)</a></li>
                    <li><a href="/index.php/Phoi/Interpretations/Search/country/<?= $country_code ?>">Interprétations (<?= $nb_autres ?>)</a></li>
                    <li><a href="/index.php/Phoi/Personnes/Search/country/<?= $country_code ?>">Personnes (<?= $nb_inds ?>)</a></li>
                    <li><a href="/index.php/Phoi/Groupes/Search/country/<?= $country_code ?>">Groupes (<?= $nb_groups ?>)</a></li>
                    <li><a href="/index.php/Phoi/Livres/Search/country/<?= $country_code ?>">Livres (<?= $nb_autres ?>)</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Description:
            </p>
        </header>
        <div class="card-content">
            <div class="content">
                <?= $description ?>
            </div>
        </div>
    </div>
