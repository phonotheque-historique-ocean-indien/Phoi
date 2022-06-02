<?php
$user = $this->getVar("user");
?>

<h2>Devenir contributeur</h2>

<form action="/index.php/Phoi/Users/sendContributorProposal" method="POST">
    <label for="motivation" class="form-label">Motivation</label>
    <textarea name="motivation" class="form-control" rows='3'></textarea>

    <input class="button is-warning" type="submit">
</form>

<style type='text/css'>
    .button {
        margin-bottom: 10px;
    }

    .form-control {
        display: block;
        width: 100%;
        margin-bottom: 10px;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
</style>