$(document).ready(function() {
    var root;
    console.log('/'+window.location.pathname.split("/")[1]+'/config.json');
    //Agafem la direcció de la carpeta arrel
    $.ajax({
        url: '/'+window.location.pathname.split("/")[1]+'/config.json',
        dataType: 'json',
        success:function(json){
            root=json.root;
        },
        async:false
        
    });
    //Esborrar l'error provocat al introduïr un usuari incorrecte
    $("#login form input").focus(function(e) {
        $('div.error').addClass('result');
        $('div.result').empty();
        $('input').removeClass('error');
    });
    //Funció AJAX per a validar el login d'usuari
    $("button.login").click(function(e) {
        e.preventDefault();
        user = $('input[name=user]').val();
        pass = $('input[name=pass]').val();
        $.ajax({
            type: "POST",
            url: root + "/agencia/login",
            data: {
                user: user,
                pass: pass
            },
            success: function(result) {
                if (result == true) {
                    window.location.href = root + "/agencia";
                } else {
                    $('div.result').html("Usuari o contrassenya incorrectes");
                    $('div.result').addClass('error');
                    $('input').addClass('error');
                    $('div.result').removeClass('result');
                }
            }
        });
    });
    //Funció AJAX per a enviar un paràmetre POST a l'hora d'eliminar un servei
    $('.delserv').click(function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('td').eq(1).html();
        $.ajax({
            type: "POST",
            url: root + "/cistella/eliminar",
            data: {
                id: id
            },
            success: function(result) {
                window.location = root + "/cistella";

            }
        });

    });
    //Elimina el valor de les zones dels serveis que no són els que corresponen
    $('select[name=servei]').change(function(e) {
        $('input[name=ciutat]').val('');
    });
    //Afegeix un servei a la cistella mitjançant POST
    $('#afegir').click(function(e) {
        e.preventDefault();
        var tipus = $('input[name=tipus]').val();
        var servei = $('input[name=servei]').val();
        var places = $('input[name=places]').val();
        $.ajax({
            type: "POST",
            url: root + "/cistella/afegir",
            data: {
                tipus: tipus,
                servei: servei,
                places: places
            },
            success: function(result) {
                if (result == true) {
                    window.location = root + "/cistella";
                } else {
                    alert(result);
                }
            }
        });
    });
    //Agafa l'id del servei i fa un POST al controlador per pagar
    $('button.pagar').click(function(e) {
        e.preventDefault();

        var id = $(this).siblings('input').val();
        $.ajax({
            type: "POST",
            url: root + "/cistella/pagar",
            data: {
                id: id
            },
            success: function(result) {
                if (result == true) {
                    window.location = root + "/cistella/reserves";
                } else {
                    alert(result);
                }
            }
        });

    });
    //Mapa dels hotels. Agafa l'adreça dinàmicament.
    $('#mapa').ready(function() {
        var id = $('#mapa').siblings('div').attr('id');
        $.ajax({
            type: "POST",
            url: root + "/ajax/getadreca",
            data: {
                id: id
            },
            success: function(result) {


                var adreca = result;
                var lat;
                var lon;
                var gmap = new google.maps.Geocoder();
                gmap.geocode({
                    'address': adreca
                }, function(result, status) {
                    lat = result[0].geometry.location.k;
                    lon = result[0].geometry.location.A;

                    $('#mapa').gmap3({
                        map: {
                            options: {
                                center: [lat, lon],
                                zoom: 14
                            }
                        },
                        marker: {
                            latLng: [lat, lon]

                        }

                    });
                });
            }
        });



    });
    //Autocompletador Jquery
    $('.typ').autocomplete({
        source: function(request, response) {
            var tipus = $('select[name=servei]').val();
            $.ajax({
                type: "POST",
                url: root + "/ajax/data",
                data: {
                    tipus: tipus
                },
                success: function(result) {
                    result = $.parseJSON(result);
                    response(result);
                }
            });
        },
        minLength: 0
    }).focus(function() {
        //Mostra les ciutats/zones d'un servei instantàniament
        $(this).autocomplete("search");
    });
    //Format de data del datepicker, per a evitar conflictes amb php
    $(".dp").datepicker({
        dateFormat: "yy-mm-dd"
    });
    //La data d'avui per defecte al datepicker
    var d = new Date();
    $(".dp").val(d.getFullYear() + '-' + (d.getMonth() < 10 ? "0" : "") + (d.getMonth() + 1) + '-' + (d.getDate() < 10 ? "0" : "") + d.getDate());

    //RSS
    $('#rss').ready(function() {
        var url = root + "/ajax/rss/";
        setTimeout(function() {
            load_page(url, "#rss");
        }, 500);
    });

    function load_page(url, id_contenidor) {
        var xml = $.ajax({
            url: url,
            success: function(xml) {
                $(id_contenidor).html("");
                load_rss(xml, id_contenidor);
            }
        });
    }
    //Funció personalitzada per extreure elements XML amb jQuery

    function load_rss(xml, id_contenedor) {

        $(xml).find('item').each(function(index, el) {

            titol = $(el).find('title').html();
            link = $(el).contents('link').get(0).nextSibling.nodeValue;
            desc = $(el).find('description').text().replace("]]>", "");
            data = $(el).find('pubDate').html();

            html = '<div class="item">' +
                '<h3><a href="' + link + '">' + titol + '</a></h3>' +
                '<span>' + data + '</span>' +
                '<p>' + desc + '</p>' +
                '</div>';
            $(id_contenedor).append(html);
        });
    }
});
