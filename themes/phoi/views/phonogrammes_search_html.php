<form method="get" action="<?php print __CA_URL_ROOT__; ?>/index.php/Search/objects" id="search">
    <div style="margin: 40px 0;">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p("Search"); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="select">
                        <select>
                            <option>Albums</option>
                            <option>-</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label">Plage de temps</label>
            </div>
            <div class="field-body">
                <div style="line-height: 40px !important;padding:0 10px;">de</div>
                <div class="field">
                    <p class="control has-icons-right">
                        <input class="input" type="text" placeholder="jj/mm/aaaa">
                        <span class="icon is-small is-right">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                    </p>
                </div>
                <div style="line-height: 40px !important;padding:0 10px;">à</div>
                <div class="field">
                    <p class="control has-icons-right">
                        <input class="input" type="text" placeholder="jj/mm/aaaa">
                        <span class="icon is-small is-right">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label">Mots-clés</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" type="text" name="search" id="keywords" placeholder="">
                    </p>
                </div>

            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label">Avec item</label>
            </div>
            <div class="field-body">
                <label class="checkbox">
                    <input type="checkbox" />
                </label>
                <div style="line-height: 40px !important;margin-left:120px;padding:0 10px;font-weight: 700;">Producteur</div>
                <div class="select">
                    <select style="min-width:260px">
                        <option></option>
                        <option>-</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p("Groups"); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="select">
                        <select style="min-width:260px">
                            <option></option>
                            <option>-</option>
                        </select>
                    </div>
                </div>
                <div style="line-height: 40px !important;margin-left:120px;padding:0 10px;font-weight: 700;"><?php _p("Title"); ?></div>
                <div class="select">
                    <select style="min-width:260px">
                        <option></option>
                        <option>-</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p("Labels"); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="select">
                        <select style="min-width:260px">
                            <option></option>
                            <option>-</option>
                        </select>
                    </div>
                </div>
                <div style="line-height: 40px !important;margin-left:120px;padding:0 10px;font-weight: 700;"><?php _p("Catalog #"); ?></div>
                <div class="select">
                    <select style="min-width:260px">
                        <option></option>
                        <option>-</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <button class="button is-normal" onclick="getResultsList('list');return false;"><?php _p("Search"); ?></button>
</form>
</div>
<hr/>
<div class="container" style="margin-bottom: 100px;">
    <div class="views-icons">
        <i class="mdi mdi-view-headline is-large" onclick="getResultsList('list');"></i>
        <i class="mdi mdi-view-module is-large" onclick="getResultsList('tiles');"></i>
    </div>
    <div style="text-align: center;font-weight: 700">Résultats albums</div>
    <div id="searchResults"></div>
</div>

<script>
    var display = "list";
    function getResultsList(disp) {
        display = disp;
        console.log("test");
       let additionals = "";
       if($("#keywords").val()) {
           additionals = "/keywords/"+$("#keywords").val();
       }
       if(disp == "tiles") { additionals += "/display/tiles"; } else { additionals += "/display/list"; }
       console.log("/index.php/Phoi/Phonogrammes/Results/country/reunion"+additionals);
       $.get("/index.php/Phoi/Phonogrammes/Results/country/reunion"+additionals, function(data) {
          $("#searchResults").html(data);
          $('#search-results-list').DataTable({
            "language": {"url": "/datatables_french.json"},
            "searching": false,
            "info": false,
            "lengthChange":false
          });
       });
    }

    $(document).ready(function() {
       getResultsList('list');
    });
</script>

<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
