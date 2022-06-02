<h1 class="title">Password</h1>
<h1 class="subtitle" style="font-size: 2.25rem;
    font-weight: 200;">Change your password</h1>
    <form method="post" action="/index.php/Phoi/Users/savePassword">
        <p><label>Please enter your new password</label></p>
        <p><input name="password" type="password" placeholder="you new password" /></p>
        <p><label>and confirm this new password when retyping it.</label></p>
        <p><input name="confirmation" type="password" placeholder="password confirmation" /></p>
        <button type="submit" class="button action-btn add-new is-uppercase has-text-centered">
        <span class="icon"><i class="mdi mdi-check"></i></span>&nbsp; Enregistrer</button>
    </form>

<style>
input {
    box-shadow: inset 0 0.0625em 0.125em rgb(10 10 10 / 5%);
    max-width: 100%;
    width: 100%;
    background-color: white;
    border-radius: 4px;
    color: #363636;
    -webkit-appearance: none;
    align-items: center;
    border: 1px solid transparent;
    border-color: #dbdbdb;
    display: inline-flex;
    font-size: 1rem;
    height: 2.5em;
    justify-content: flex-start;
    line-height: 1.5;
    padding-bottom: calc(0.5em - 1px);
    padding-left: calc(0.75em - 1px);
    padding-right: calc(0.75em - 1px);
    padding-top: calc(0.5em - 1px);
    position: relative;
    vertical-align: top;
}
form p {
    margin:12px 0;
}
form {
    margin-bottom : 100px;
}
</style>