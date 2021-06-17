<?php
    $loggedin = $this->request->isLoggedIn();
?>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">

<script>
/**
 *  Plug-in offers the same functionality as `simple_numbers` pagination type 
 *  (see `pagingType` option) but without ellipses.
 *
 *  See [example](http://www.gyrocode.com/articles/jquery-datatables-pagination-without-ellipses) for demonstration.
 *
 *  @name Simple Numbers - No Ellipses
 *  @summary Same pagination as 'simple_numbers' but without ellipses
 *  @author [Michael Ryvkin](http://www.gyrocode.com)
 *
 *  @example
 *    $(document).ready(function() {
 *        $('#example').dataTable( {
 *            "pagingType": "simple_numbers_no_ellipses"
 *        } );
 *    } );
 */

$.fn.DataTable.ext.pager.simple_numbers_no_ellipses = function(page, pages){
   var numbers = [];
   var buttons = $.fn.DataTable.ext.pager.numbers_length;
   var half = Math.floor( buttons / 2 );

   var _range = function ( len, start ){
      var end;
   
      if ( typeof start === "undefined" ){ 
         start = 0;
         end = len;

      } else {
         end = start;
         start = len;
      }

      var out = []; 
      for ( var i = start ; i < end; i++ ){ out.push(i); }
   
      return out;
   };
    

   if ( pages <= buttons ) {
      numbers = _range( 0, pages );

   } else if ( page <= half ) {
      numbers = _range( 0, buttons);

   } else if ( page >= pages - 1 - half ) {
      numbers = _range( pages - buttons, pages );

   } else {
      numbers = _range( page - half, page + half + 1);
   }

   numbers.DT_el = 'span';

   return [ 'first', numbers, 'last' ];
};
</script>
<?php if ($loggedin) { ?>
<div style="padding:20px 0;">
    <a href="/index.php/Contribuer/Do/Form/table/ca_objects/type/Phonogramme"><button class="button is-primary"><i class="mdi mdi-plus-circle is-large"></i><?php _p('Ajouter'); ?></button></a>
</div>
<?php } ?>
<form method="get" action="<?php echo __CA_URL_ROOT__; ?>/index.php/Search/objects" id="search">
    <div style="margin: 54px 0 40px 0;">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Chercher'); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="select">
                        <select id="changeSearch">
                            <option><?php _p('Albums'); ?></option>
                            <option><?php _p('Enquêtes'); ?></option>
                            <option><?php _p('Créations musicales'); ?></option>
                            <option><?php _p('Interprétations'); ?></option>
                            <option><?php _p('Partitions'); ?></option>
                            <option selected><?php _p('Personnes'); ?></option>
                            <option><?php _p('Livres'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="field-label is-normal">
                    <label class="label"><?php _p('Pays'); ?></label>
                </div>
                <div class="field">
                    <div class="select">
                        <select id="form-pays">
                            <option selected="selected">-</option>
                            <option>Comores</option>
                            <option>Maurice</option>
                            <option>Madagascar</option>
                            <option>Mayotte</option>
                            <option>La Réunion</option>
                            <option>Rodrigues</option>
                            <option>Seychelles</option>
                            <option>Zanzibar</option>
                            </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Plage de temps'); ?></label>
            </div>
            <div class="field-body">
                <div style="line-height: 40px !important;padding:0 10px;"><?php _p('de'); ?></div>
                <div class="field">
                    <p class="control has-icons-right">
                        <input id="form-date" class="input" type="text" placeholder="aaaa OU jj/mm/aaaa">
                        <span class="icon is-small is-right">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                    </p>
                </div>
                <div style="line-height: 40px !important;padding:0 10px;"><?php _p('à'); ?></div>
                <div class="field">
                    <p class="control has-icons-right">
                        <input id="form-date_fin" class="input" type="text" placeholder="aaaa OU jj/mm/aaaa">
                        <span class="icon is-small is-right">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p("Titre de l'album"); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" type="text" name="title" id="form-titre" placeholder="">
                    </p>
                </div>

            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Mots-clés'); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" type="text" name="search" id="form-tag" placeholder="">
                    </p>
                </div>

            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Tous champs'); ?></label>
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
                <label class="label"><?php _p('Médias'); ?></label>
            </div>        
            <div class="field-body">
            <div class="is-normal">
                <label class="label"><?php _p('Avec audio'); ?></label>
            </div>
            <label class="checkbox" style="padding-left:4px;">
                <input type="checkbox" id="form-album_avec_audio"  style="margin-top:4px;"/>
            </label>
            <div class="is-normal" style="padding-left:20px;">
                <label class="label"><?php _p('Avec image'); ?></label>
            </div>
            <label class="checkbox" style="padding-left:4px;">
                <input type="checkbox" id="form-album_avec_image"  style="margin-top:4px;"/>
            </label>
            
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Groupes/Interprète'); ?></label>
            </div>
            <div class="field-body"> 
                <div class="field">
                    <div class="select" style="width:100%" />
                        <input class="input" id="form-groupes" style="width:100%" />
                    </div>
                </div>
                <div style="line-height: 40px !important;margin-left:120px;padding:0 10px;font-weight: 700;"><?php _p('Producteur'); ?></div>
            <div class="select">
                <input class="input" id="form-producteur" style="min-width:260px" />
            </div>
            </div>
            
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label"><?php _p('Labels'); ?></label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="select" style="width:100%">
                        <input class="input" id="form-labels"  style="width:100%" />
                    </div>
                </div>
                <div style="line-height: 40px !important;margin-left:120px;padding:0 10px;font-weight: 700;"><?php _p('N° catalogue'); ?></div>
                <div class="select">
                    <input id="form-num_catalogue" style="min-width:260px" class="input"/>
                </div>
            </div>
        </div>
    </div>

    <button class="button is-normal" onclick="getResultsList(display);return false;"><i class="mdi mdi-magnify is-large"></i> <?php _p('Chercher'); ?></button>
</form>
</div>
<hr/>
<div class="container" style="margin-bottom: 100px;">
    <div class="views-icons">
        <i class="mdi mdi-view-headline is-large" onclick="getResultsList('list');"></i>
        <i class="mdi mdi-view-module is-large" onclick="getResultsList('tiles');"></i>
    </div>
    <div style="text-align: center;font-weight: 700"><span id="nbresults"></span> <?php _p('résultats personnes'); ?></div>
    <div id="searchResults"></div>
    <table id="search-results-list">
	    <thead>
		  <tr>
		    <th>Titre</th>
		    <th>Date</th>
		    <th>Interprète</th>
		    <th>Producteur</th>
		    <th>Pays</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
    </table>
</div>

<script>
    var display = "list";
    function getResultsList(disp, page) {
        if(page == undefined) page=0;
        display = disp;
        console.log("test");
        console.log(disp);
		let additionals = "";
		if($("#keywords").val()) {
           additionals = additionals+"/keywords/"+$("#keywords").val();
           console.log(additionals);
       	}
        if($("#form-tag").val()) {
           additionals = additionals+"/tag/"+$("#form-tag").val();
           console.log(additionals);
       	}           
        if($("#form-titre").val()) {
           additionals = additionals+"/titre/"+$("#form-titre").val();
           console.log(additionals);
       	}           
        if($("#form-pays").val()) {
           additionals = additionals+"/pays/"+$("#form-pays").val();
           console.log(additionals);
       	}
        if($("#form-date").val()) {
           additionals = additionals+"/date/"+$("#form-date").val().replaceAll("/","_");
           console.log(additionals);
       	}
        if($("#form-date_fin").val()) {
           additionals = additionals+"/date_fin/"+$("#form-date_fin").val().replaceAll("/","_");
           console.log(additionals);
       	}
        if($("#form-producteur").val()) {
           additionals = additionals+"/producteur/"+$("#form-producteur").val();
           console.log(additionals);
       	}
        if($("#form-groupes").val()) {
           additionals = additionals+"/groupes/"+$("#form-groupes").val();
           console.log(additionals);
       	}
        if($("#form-labels").val()) {
           additionals = additionals+"/labels/"+$("#form-labels").val();
           console.log(additionals);
       	}
        if($("#form-num_catalogue").val()) {
           additionals = additionals+"/num_catalogue/"+$("#form-num_catalogue").val();
           console.log(additionals);
       	}
        if($("#form-album_avec_audio").is(":checked")) {
           additionals = additionals+"/album_avec_audio/1";
           console.log(additionals);
       	}
        if($("#form-album_avec_image").is(":checked")) {
           additionals = additionals+"/album_avec_image/1";
           console.log(additionals);
       	}                      
       	console.log(additionals);
	   	if(disp == "tiles") { 
	       	additionals += "/display/tiles"; 
	       	let url = "/index.php/Phoi/Personnes/Results"+additionals+"/page/"+page;
	       	console.log(url);
	       	$.get(url, function(data) {
		   		$("#searchResults").html(data);
        	});
        	$("#searchResults").show();
        	$("#search-results-list_wrapper").hide();
	   	} else { 
		    additionals += "/display/list"; 
		    $("#searchResults").hide();
		    $("#search-results-list_wrapper").show();
		    $("#search-results-list").show();
		    if ( $.fn.dataTable.isDataTable('#search-results-list') ) {
			    table = $('#search-results-list').DataTable();
			    table.ajax.url("/index.php/Phoi/Personnes/ResultsJson"+additionals).load();
			}
			else {
			    table = $('#search-results-list').DataTable({
                    "bStateSave": true,
					"processing": true,
					"serverSide": true,
                    'pagingType': 'simple_numbers_no_ellipses',
                    "ajax": {
                        "url" : "/index.php/Phoi/Personnes/ResultsJson"+additionals,
                        "dataSrc": function(res){
                            $("#nbresults").text(res.recordsTotal);
                            return res.data;
                        },
                    },
		            "language": {"url": "/datatables_french.json"},
		            "searching": false,
		            "info": false,
					"columns": [
			            { "data": "Titre" },
			            { "data": "Date" },
			            { "data": "Interprète" },
			            { "data": "Producteur" },
			            { "data": "Pays" },        
			        ]
		          });
                  
			}
            
		}
		console.log("/index.php/Phoi/Personnes/Results"+additionals);
    }

    $(document).ready(function() {
       console.log("getResultsList");
       getResultsList('list');
    });
</script>

