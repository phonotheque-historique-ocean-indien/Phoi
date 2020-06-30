<div class="container">
    <!-- ca_objects_fonds_html.php -->
    <form method="get" action="/index.php/Search/objects" id="search">
        <div style="margin: 40px 0;">
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Search</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="select">
                            <select>
                                <option>Enquêtes</option>
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
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            </span>
                        </p>
                    </div>
                    <div style="line-height: 40px !important;padding:0 10px;">à</div>
                    <div class="field">
                        <p class="control has-icons-right">
                            <input class="input" type="text" placeholder="jj/mm/aaaa">
                            <span class="icon is-small is-right">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
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
                            <input class="input" type="text" name="search" placeholder="">
                        </p>
                    </div>

                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Type de collectage</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="select">
                            <select>
                                <option>1</option>
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Nature</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="select">
                            <select>
                                <option>1</option>
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Type de support</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="select">
                            <select>
                                <option>Type de support</option>
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Genre</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="select">
                            <select>
                                <option>Genre</option>
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <button class="button is-normal" onclick="$('#search').submit();">Rechercher</button>
    </form>
    <hr>
    <div class="columns enquetes-infos" style="min-height:500px;">
        <div class="column is-one-fifth">
            <div class="column-header">Fonds</div>
            <div id="detail1">
          <span class="fondsitem" onclick="loadChildren('detail2', 140);">
          <a href="/index.php/Contribuer/Do/EditForm/table/ca_objects/type/fonds/id/140" class="pull-right" aria-label="edit">
		    <span class="icon">
				<i class="mdi mdi-pencil is-large"></i>
	  		</span>
          </a>
              Mission patrimoine du PRMA
          </span>
            </div>
        </div>
        <div class="column is-one-fifth">
            <div class="column-header">Corpus</div>
            <div id="detail2"></div>
        </div>
        <div class="column is-one-fifth">
            <div class="column-header">Enquêtes</div>
            <div id="detail3"></div>
        </div>
        <div class="column">
            <div class="column-header">Collectages</div>
            <div id="detail4"></div>
        </div>
    </div>
    <script>
        $('img[data-enlargable]').addClass('img-enlargable').click(function(){
            var src = $(this).attr('src');
            var modal;
            function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
            modal = $('<div>').css({
                background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
                backgroundSize: 'contain',
                width:'100%', height:'100%',
                position:'fixed',
                zIndex:'10000',
                top:'0', left:'0',
                cursor: 'zoom-out'
            }).click(function(){
                removeModal();
            }).appendTo('body');
            //handling ESC
            $('body').on('keyup.modal-close', function(e){
                if(e.key==='Escape'){ removeModal(); }
            });
        });

        function loadChildren(target, id) {
            let current = parseInt(target.substr(target.length - 1));
            let next = current + 1;
            let rootname = target.slice(0, -1);
            target2 = rootname + next;
            if(target2 == "detail3") {
                $("#detail3").html("");
                $("#detail4").html("");
            }
            if(target2 == "detail4") {
                $("#detail4").html("");
            }
            console.log(target2);
            $.get("/get_fonds.php?parent="+id+"&target="+target2, function( data ) {
                $( "#"+target ).html( data );
            });
        }

    </script>
    <style>
        h1.titre-phonogramme {

        }
        .card {
            border:1px solid #e0e0e0;
            margin-bottom:10px;
        }
        .infosprincipales {
            max-height: 220px;
            overflow-y: scroll;
        }
        /***** MODIFS RACHEL *****/
        .card-content {
            padding: 1.5rem;
        }

        .card-cover-icon {
            z-index: 99;

        }
        /***** FIN MODIFS RACHEL *****/
        h1.titre-phonogramme {
            text-align: center;
            font-weight: 100;
            font-size: 1.8em;
            padding-top: 1em;
            padding-bottom: 0.5em;
        }
        .list-items .card {
            width: 220px;
            display:inline-block;
        }
        .content figure {
            margin:0;
        }
        .card .media:not(:last-child) {
            margin-bottom:0.5rem;
        }
        /***** MODIFS RACHEL *****/
        .content ul {
            list-style: none;
            margin: 0 !important;
        }

        .content ol {
            margin: 1.5rem;
            line-height: 2rem;
        }

        .player-title {
            display: flex;
            justify-content: space-between;
        }

        .card-header-title {
            align-items: baseline !important;
            justify-content: space-between;
        }

        .card-content-item {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        .card-content-item p{
            margin: 0 !important;
        }

        .cover {
            position: relative;
            max-width: 120px;
            z-index: 0;
        }

        .card-cover-icon {
            position: absolute;
            right: 0;
            top: 0;
            z-index: 1;
        }

        .player-covers {
            margin-top: 1rem;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }

        .tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.5rem;
            padding : 2rem;
            height: 2em;
            color: #EFEFEF;
            text-transform: uppercase;
        }

        .tag:link {
            background-color: #BFD7E3 !important;
            border-radius: 2px !important;
            color: #232425;
            border-bottom: 0.1rem solid #232425;
        }

        .tab:hover {
            color: #232425;
            background-color: #EFEFEF;
            border-bottom: none;
            padding : 2rem;
            cursor: pointer;
        }

        .tab:active {
            color: #232425;
            border-bottom: 0.1em solid #232425;
        }

        .is-active {
            color: #232425;
            border-bottom: 0.1em solid #232425;
        }

        .column-header {
            background-color: #598da5;
            text-align:center;
            color:white;
            font-size:1.2em;
            text-transform: uppercase;
            padding:12px 0;
        }
        .enquetes-infos .column {
            height:100%;
        }
        .enquetes-infos .column #detail1,
        .enquetes-infos .column #detail2,
        .enquetes-infos .column #detail3,
        .enquetes-infos .column #detail4 {
            cursor:pointer;
            font-weight: bold;

        }

        .detail4 div.gelule {
            border:1px solid #ccc;
            border-radius:4px;
        }

        .fondsitem {
            display:block;
            padding:8px 12px;
            box-shadow: 0 0.5em 1em -0.125em rgba(10, 10, 10, 0.1), 0 0px 0 1px rgba(10, 10, 10, 0.02);
            border: 1px solid #eee;
            margin-top:10px;
            margin-bottom:4px;
            border-radius: 4px;
        }
        .fondscollectageitem {
            display:inline-block;
            padding:8px 12px;
            width: calc(50% - 12px);
            box-shadow: 0 0.5em 1em -0.125em rgba(10, 10, 10, 0.1), 0 0px 0 1px rgba(10, 10, 10, 0.02);
            margin-bottom:12px;
            border:1px solid #eee;
        }
        .fondscollectageitem:nth-child(2n) {
            margin-left:16px;
        }

    </style>

</div>
